<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            'image' => 'mimes:jpeg,jpg,png|max:10000',
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes' => 'Допустивый формат файлов: jpeg,jpg,png',
            'image.max' => 'Максимальный размер файла: "10 Мб"',
        ];
    }
}
