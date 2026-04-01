<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'reviewed_id' => ['required', 'exists:users,id'],
            'listing_id'  => ['nullable', 'exists:listings,id'],
            'rating'      => ['required', 'integer', 'min:1', 'max:5'],
            'comment'     => ['nullable', 'string', 'max:1000'],
        ];
    }
}
