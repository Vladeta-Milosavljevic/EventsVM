<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
        return [
            'name' => ['required', 'max:50', 'min:5'],
            'category_id' => ['required'],
            'tags' => ['required', 'max:50', 'min:5'],
            'description' => ['required', 'max:300', 'min:25'],
            'image' => ['nullable','mimes:jpeg,jpg,png', 'max:1100'],
            'addImages' => ['nullable','array', 'max:5'],
            'addImages.*' => ['mimes:jpeg,jpg,png', 'max:1100'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id' => 'The category field is required',
            'addImages.max' => 'No more than five images can be uploaded',
            'addImages.*.max' => 'The image is too large',
            'addImages.*.mimes' => 'The image field must be a file of type: jpeg, jpg, png.',
        ];
    }
}
