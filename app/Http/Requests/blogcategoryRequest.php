<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Sanitizer;
use Illuminate\Foundation\Http\FormRequest;

class blogcategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category'); // Assuming 'post_category' is passed as a route parameter

        return [
            //
            'title' => 'required|max:255|unique:blog_categories,title,'.$categoryId,
            'image' => 'nullable|string',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'status' => 'boolean',

        ];
    }

    protected function prepareForValidation()
    {
        // Define sanitization rules
        $sanitizer = new Sanitizer($this->all(), [
            'title' => 'trim|escape',

        ]);

        // Replace request data with sanitized data
        $this->merge($sanitizer->sanitize());
    }
}
