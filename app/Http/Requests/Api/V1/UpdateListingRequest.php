<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type'             => ['sometimes', 'string', 'in:urban_renewal,land'],
            'province'         => ['sometimes', 'string', 'max:100'],
            'district'         => ['sometimes', 'string', 'max:100'],
            'neighborhood'     => ['sometimes', 'nullable', 'string', 'max:100'],
            'address'          => ['sometimes', 'nullable', 'string', 'max:500'],
            'ada_no'           => ['sometimes', 'nullable', 'string', 'max:50'],
            'parcel_no'        => ['sometimes', 'nullable', 'string', 'max:50'],
            'pafta'            => ['sometimes', 'nullable', 'string', 'max:50'],
            'area_m2'          => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'floor_count'      => ['sometimes', 'nullable', 'integer', 'min:0'],
            'zoning_status'    => ['sometimes', 'nullable', 'string', 'in:ada,a_lejantli,arazi,bag_bahce,depo_antrepo,egitim,enerji_depolama,konut,kulturel_tesis,muhtelif,ozel_kullanim,saglik,sanayi,sera,sit_alani,spor_alani,tarla,tarla_bag,ticari,ticari_konut,toplu_konut,turizm,turizm_konut,turizm_ticari,villa,zeytinlik'],
            'agreement_model'  => ['sometimes', 'nullable', 'string', 'in:kat_karsiligi,para_karsiligi,karma_para_kat,hasilat_paylasimli,yap_islet_devret,kismi_satis_kat'],
            'taks'             => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:1'],
            'kaks'             => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'gabari'           => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'description'      => ['sometimes', 'nullable', 'string'],
            'status'           => ['sometimes', 'string', 'in:passive'],
            'expires_at'       => ['sometimes', 'nullable', 'date'],
            'documents.*'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'photos.*'         => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_documents.*' => ['integer'],
            'remove_photos.*'    => ['integer'],
        ];
    }
}
