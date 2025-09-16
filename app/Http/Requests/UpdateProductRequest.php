<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Considera agregar lógica de permisos de Spatie aquí
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        // La validación 'unique' debe ignorar el ID del producto actual
        $productId = $this->route('product'); // Esto asume una ruta de tipo resource

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_alert_threshold' => 'required|integer|min:0',
        ];
    }
}