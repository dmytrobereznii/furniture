<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryMetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:50000',
            'keywords' => 'required|string|max:50000',
            'description' => 'required|string|max:50000',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Вкажіть заголовок сторінки',
            'title.max' => 'Максимальна довжина поля 50000 символів',
            'keywords.required' => 'Вкажіть ключові слова',
            'keywords.max' => 'Максимальна довжина поля 50000 символів',
            'description.required' => 'Вкажіть опис',
            'description.max' => 'Максимальна довжина поля 50000 символів',
        ];
    }
}
