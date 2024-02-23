<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'name' => ['required', 'max:50', 'min:5', 'unique:events'],
            'category' => ['required', 'max:50', 'min:5'],
            'tags' => ['required', 'max:50', 'min:5'],
            'description' => ['required', 'max:300', 'min:25'],
            'image' => ['required', 'mimes:jpeg,jpg,png', 'max:1100'],
            'addImages' => ['required', 'array', 'max:5'],
            'addImages.*' => ['mimes:jpeg,jpg,png','max:1100'],
        ];
    }

    public function messages(): array
    {
        return [
            'addImages.max' => 'No more than five images can be uploaded',
            'addImages.*.max' => 'The image is too large',
            'addImages.*.mimes' => 'The image field must be a file of type: jpeg, jpg, png.',
        ];
    }
}
