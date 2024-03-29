<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed images
 */
class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:65000',
//            'images' => 'sometimes|array',
//            'images.*' => 'required|array',
//            'images.*.id' => 'required_without:images.*.name,images.*.src|integer',
//            'images.*.name' => 'required_without:images.*.id|string',
//            'images.*.src' => 'required_without:images.*.id|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле "заголовок" обязательно',
            'title.string' => 'Поле "заголовок" должно быть строкой',
            'title.max' => 'Поле "заголовок" должно быть не более 255 символов',
            'description.required' => 'Поле "описание" обязательно',
            'description.string' => 'Поле "описание" должно быть строкой',
            'description.max' => 'Поле "описание" должно быть не более 65000 символов',
        ];
    }
}
