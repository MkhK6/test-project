<?php

namespace App\Product\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price'       => ['sometimes', 'integer', 'min:0'],
            'weight'      => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'category'    => ['sometimes', 'string', 'max:255'],
        ];
    }
}
