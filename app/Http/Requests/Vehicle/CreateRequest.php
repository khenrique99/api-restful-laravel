<?php

namespace App\Http\Requests\Vehicle;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CreateRequest',
    required: ['name', 'brand', 'model', 'year', 'color', 'price'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Toyota Corolla'),
        new OA\Property(property: 'brand', type: 'string', example: 'Toyota'),
        new OA\Property(property: 'model', type: 'string', example: 'Corolla'),
        new OA\Property(property: 'year', type: 'integer', example: 2020),
        new OA\Property(property: 'color', type: 'string', example: 'Red'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 85000.00),
    ]
)]
class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|string|max:10',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vehicle name is required.',
            'brand.required' => 'Vehicle brand is required.',
            'model.required' => 'Vehicle model is required.',
            'year.required' => 'Vehicle year is required.',
            'color.required' => 'Vehicle color is required.',
            'price.required' => 'Vehicle price is required.',
            'price.numeric' => 'Vehicle price must be a number.',
            'price.min' => 'Vehicle price must be greater than 0.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error('Validation failed.', $validator->errors(), 422)
        );
    }
}
