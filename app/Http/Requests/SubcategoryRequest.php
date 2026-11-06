<?php

namespace App\Http\Requests;

use App\Models\Subcategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubcategoryRequest extends FormRequest
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
        $subcategory = $this->route('subcategory');
        $subcategoryId = $subcategory instanceof Subcategory ? $subcategory->id : (is_numeric($subcategory) ? (int) $subcategory : null);

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories', 'name')
                    ->where('category_id', $this->category_id)
                    ->ignore($subcategoryId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
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
            'category_id.required' => 'Please select a parent category.',
            'category_id.exists' => 'The selected parent category does not exist.',
            'name.required' => 'Subcategory name is required.',
            'name.unique' => 'This subcategory already exists under the selected category.',
            'name.max' => 'Subcategory name cannot exceed 255 characters.',
        ];
    }
}
