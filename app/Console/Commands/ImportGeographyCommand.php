<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Neighborhood;
use App\Models\Province;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportGeographyCommand extends Command
{
    protected $signature   = 'geo:import {--fresh : Mevcut verileri sil ve yeniden yükle}';
    protected $description = 'Türkiye il/ilçe/mahalle verilerini veritabanına yükler';

    private string $baseUrl = 'https://turkiyeapi.dev/api/v1';

    public function handle(): int
    {
        if (Province::count() > 0 && ! $this->option('fresh')) {
            $this->warn('Coğrafi veri zaten mevcut. Yeniden yüklemek için --fresh kullanın.');
            return self::SUCCESS;
        }

        if ($this->option('fresh')) {
            $this->warn('Mevcut coğrafi veriler siliniyor...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Neighborhood::truncate();
            District::truncate();
            Province::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('Türkiye coğrafi verileri yükleniyor...');
        $this->newLine();

        // ── 1. İller ─────────────────────────────────────────────────────────
        $this->info('İller alınıyor...');
        $provinces = $this->fetch('/provinces?fields=id,name&limit=100');

        if (empty($provinces)) {
            $this->error('İl verileri alınamadı. İnternet bağlantısını kontrol edin.');
            return self::FAILURE;
        }

        $provinceBar = $this->output->createProgressBar(count($provinces));
        $provinceBar->start();

        $provinceMap = []; // API id => DB id

        foreach ($provinces as $p) {
            $province = Province::create([
                'name' => $p['name'],
                'code' => $p['id'],
            ]);
            $provinceMap[$p['id']] = $province->id;
            $provinceBar->advance();
        }

        $provinceBar->finish();
        $this->newLine(2);
        $this->info(count($provinces) . ' il yüklendi.');

        // ── 2. İlçeler ───────────────────────────────────────────────────────
        $this->info('İlçeler alınıyor...');
        $districts = $this->fetch('/districts?fields=id,name,provinceId&limit=1000');

        if (empty($districts)) {
            $this->error('İlçe verileri alınamadı.');
            return self::FAILURE;
        }

        $districtBar = $this->output->createProgressBar(count($districts));
        $districtBar->start();

        $districtMap = []; // API id => DB id
        $districtChunks = array_chunk($districts, 100);

        foreach ($districtChunks as $chunk) {
            $rows = [];
            foreach ($chunk as $d) {
                $provinceDbId = $provinceMap[$d['provinceId']] ?? null;
                if (! $provinceDbId) continue;

                $rows[] = [
                    'province_id' => $provinceDbId,
                    'name'        => $d['name'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
            if ($rows) {
                District::insert($rows);
            }
            $districtBar->advance(count($chunk));
        }

        // Mapping için DB'den çek
        District::with('province')->get()->each(function ($d) use (&$districtMap, $districts) {
            foreach ($districts as $apiD) {
                if ($apiD['name'] === $d->name && isset($d->province)) {
                    $districtMap[$apiD['id']] = $d->id;
                    break;
                }
            }
        });

        $districtBar->finish();
        $this->newLine(2);
        $this->info(count($districts) . ' ilçe yüklendi.');

        // ── 3. Mahalleler ────────────────────────────────────────────────────
        $this->info('Mahalleler alınıyor (bu işlem birkaç dakika sürebilir)...');

        $page   = 1;
        $limit  = 1000;
        $total  = 0;
        $bar    = null;

        do {
            $response = Http::timeout(30)->get("{$this->baseUrl}/neighborhoods", [
                'fields' => 'id,name,districtId',
                'limit'  => $limit,
                'offset' => ($page - 1) * $limit,
            ]);

            if (! $response->successful()) break;

            $data       = $response->json();
            $items      = $data['data'] ?? [];
            $totalCount = $data['totalCount'] ?? 0;

            if ($bar === null && $totalCount > 0) {
                $bar = $this->output->createProgressBar($totalCount);
                $bar->start();
            }

            if (empty($items)) break;

            $rows = [];
            foreach ($items as $n) {
                $districtDbId = $districtMap[$n['districtId']] ?? null;
                if (! $districtDbId) continue;

                $rows[] = [
                    'district_id' => $districtDbId,
                    'name'        => $n['name'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            if ($rows) {
                foreach (array_chunk($rows, 500) as $chunk) {
                    Neighborhood::insert($chunk);
                }
            }

            $total += count($items);
            $bar?->advance(count($items));
            $page++;

            // API'yi yormamak için küçük bekleme
            usleep(100000); // 100ms

        } while (count($items) === $limit);

        $bar?->finish();
        $this->newLine(2);
        $this->info("{$total} mahalle yüklendi.");
        $this->newLine();
        $this->info('✓ Coğrafi veri yükleme tamamlandı.');

        return self::SUCCESS;
    }

    private function fetch(string $path): array
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . $path);
            if ($response->successful()) {
                return $response->json('data', []);
            }
        } catch (\Exception $e) {
            $this->error('API hatası: ' . $e->getMessage());
        }

        return [];
    }
}
