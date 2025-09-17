<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // En este ejemplo, permitimos que cualquier usuario autenticado
        // realice la acción. Considera agregar lógica de permisos de Spatie aquí
        // como: return $this->user()->can('create products');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_alert_threshold' => 'required|integer|min:0',
        ];
    }
}