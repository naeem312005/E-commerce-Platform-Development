<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product instanceof Product ? $product->id : (is_numeric($product) ? (int) $product : null);
        $isCreate = $this->isMethod('post');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => [
                'required',
                Rule::exists('subcategories', 'id')->where(function ($query) {
                    $query->where('category_id', $this->category_id);
                }),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId),
            ],
            'description' => ['required', 'string'],
            'image' => [
                $isCreate ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp,svg',
                'max:5120', // 5MB
            ],
            'old_price' => ['required', 'numeric', 'min:0'],
            'new_price' => [
                'required',
                'numeric',
                'min:0',
                'lte:old_price',
            ],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'gallery' => ['nullable', 'array', 'max:8'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
            'variants' => ['nullable', 'array'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.attribute_name' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.attribute_value' => ['required_with:variants', 'string', 'max:100'],
            'variants.*.image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a product category.',
            'category_id.exists' => 'The selected category does not exist.',
            'subcategory_id.required' => 'Please select a subcategory.',
            'subcategory_id.exists' => 'The selected subcategory does not belong to the chosen category.',
            'name.required' => 'Product name is required.',
            'name.unique' => 'A product with this name already exists.',
            'description.required' => 'Product description is required.',
            'image.required' => 'A product image is required.',
            'image.image' => 'The uploaded file must be a valid image.',
            'image.mimes' => 'Allowed image formats: jpeg, png, jpg, webp, svg.',
            'image.max' => 'Image size cannot exceed 5MB.',
            'old_price.required' => 'Original/Old price is required.',
            'old_price.numeric' => 'Old price must be a valid number.',
            'old_price.min' => 'Old price cannot be negative.',
            'new_price.required' => 'Current/New price is required.',
            'new_price.numeric' => 'New price must be a valid number.',
            'new_price.min' => 'New price cannot be negative.',
            'new_price.lte' => 'The new (sale) price cannot be greater than the old (original) price.',
            'stock_quantity.required' => 'Stock quantity is required.',
            'stock_quantity.integer' => 'Stock quantity must be a whole number.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'gallery.*.image' => 'Each gallery file must be a valid image.',
            'gallery.*.mimes' => 'Allowed gallery image formats: jpeg, png, jpg, webp, svg.',
            'gallery.*.max' => 'Each gallery image size cannot exceed 5MB.',
        ];
    }
}
