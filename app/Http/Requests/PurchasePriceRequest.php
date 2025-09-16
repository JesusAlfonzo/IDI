<?php
// app/Http/Requests/PurchasePriceRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchasePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'supplier_id.required' => 'El campo proveedor es obligatorio.',
            'supplier_id.exists' => 'El proveedor seleccionado no es válido.',
            'purchase_price.required' => 'El campo precio de compra es obligatorio.',
            'purchase_price.numeric' => 'El precio de compra debe ser un número.',
            'purchase_price.min' => 'El precio de compra debe ser un valor positivo.',
        ];
    }
}