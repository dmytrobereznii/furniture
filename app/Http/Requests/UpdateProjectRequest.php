<?php

namespace App\Http\Requests;

use App\Models\CategoryProject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
        $project = CategoryProject::find(request('projectId'));

        return [
            'category' => 'required|string',
            'name' => 'nullable|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category_projects')->ignore($project->id),
                'regex:/^[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*$/'
            ],
            'meta_title' => 'nullable|string|max:50000',
            'meta_keywords' => 'nullable|string|max:50000',
            'meta_description' => 'nullable|string|max:50000',
            'description' => 'nullable|string',
            'backgroundImage' => $project->hasMedia('backgroundImage')
                ? ''
                : 'required|file|max:5120',
            'primaryImage' => $project->hasMedia('primaryImage')
                ? ''
                : 'required|file|max:5120',
            'secondaryImage.*' => 'nullable|image|max:5120',
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
            'name.max' => 'Максимальна довжина поля 255 символів',
            'description.max' => 'Максимальна довжина поля 50000 символів',
            'backgroundImage.required' => 'Додайте фонове фото',
            'primaryImage.required' => 'Додайте основне фото',
            'slug.required' => 'Додайте Slug',
            'slug.unique' => 'Slug має бути унікальним',
            'slug.regex' => 'Slug має бути у правильному форматі',
            'backgroundImage.max' => 'Максимальний розмір фото 5 МБ',
            'primaryImage.max' => 'Максимальний розмір фото 5 МБ',
            'secondaryImage.max' => 'Максимальний розмір фото 5 МБ',
            'meta_title.max' => 'Максимальна довжина мета-заголовка сторінки 50000 символів',
            'meta_keywords.max' => 'Максимальна довжина мета-ключових слів 50000 символів',
            'meta_description.max' => 'Максимальна довжина мета-опису 50000 символів',
        ];
    }
}
