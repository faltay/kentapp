<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type'          => ['sometimes', 'string', 'in:urban_renewal,land'],
            'province'      => ['required', 'string', 'max:100'],
            'district'      => ['required', 'string', 'max:100'],
            'neighborhood'  => ['nullable', 'string', 'max:100'],
            'address'       => ['nullable', 'string', 'max:500'],
            'ada_no'        => ['nullable', 'string', 'max:50'],
            'parcel_no'     => ['nullable', 'string', 'max:50'],
            'pafta'         => ['nullable', 'string', 'max:50'],
            'area_m2'       => ['nullable', 'numeric', 'min:0'],
            'floor_count'   => ['nullable', 'integer', 'min:0'],
            'zoning_status'    => ['nullable', 'string', 'in:ada,a_lejantli,arazi,bag_bahce,depo_antrepo,egitim,enerji_depolama,konut,kulturel_tesis,muhtelif,ozel_kullanim,saglik,sanayi,sera,sit_alani,spor_alani,tarla,tarla_bag,ticari,ticari_konut,toplu_konut,turizm,turizm_konut,turizm_ticari,villa,zeytinlik'],
            'agreement_model'  => ['nullable', 'string', 'in:kat_karsiligi,para_karsiligi,karma_para_kat,hasilat_paylasimli,yap_islet_devret,kismi_satis_kat'],
            'taks'          => ['nullable', 'numeric', 'min:0', 'max:1'],
            'kaks'          => ['nullable', 'numeric', 'min:0'],
            'gabari'        => ['nullable', 'numeric', 'min:0'],
            'description'      => ['nullable', 'string'],
            'request_featured' => ['nullable', 'boolean'],
            'documents.*'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'photos.*'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}
