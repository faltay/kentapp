<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'user_id'       => ['required', 'exists:users,id'],
            'type'          => ['required', 'string', 'in:urban_renewal,land'],
            'status'        => ['required', 'string', 'in:draft,pending,active,rejected,passive'],
            'province'      => ['required', 'string', 'max:100'],
            'district'      => ['required', 'string', 'max:100'],
            'neighborhood'  => ['nullable', 'string', 'max:100'],
            'address'       => ['nullable', 'string', 'max:500'],
            'ada_no'        => ['nullable', 'string', 'max:50'],
            'parcel_no'     => ['nullable', 'string', 'max:50'],
            'area_m2'       => ['nullable', 'numeric', 'min:0'],
            'floor_count'   => ['nullable', 'integer', 'min:0'],
            'zoning_status' => ['nullable', 'string', 'in:residential,commercial,mixed,unplanned'],
            'taks'          => ['nullable', 'numeric', 'min:0', 'max:1'],
            'kaks'          => ['nullable', 'numeric', 'min:0'],
            'description'   => ['nullable', 'string'],
            'is_featured'   => ['boolean'],
            'expires_at'    => ['nullable', 'date'],
            'documents.*'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'photos.*'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_documents' => ['nullable', 'array'],
            'remove_documents.*' => ['integer'],
            'remove_photos'    => ['nullable', 'array'],
            'remove_photos.*'  => ['integer'],
            'parcel_geometry'  => ['nullable', 'string', 'json'],
        ];
    }
}
