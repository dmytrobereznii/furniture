<?php

namespace App\Http\Requests;

use App\Rules\NotInArray;
use Illuminate\Foundation\Http\FormRequest;
use Throwable;

class CallbackRequest extends FormRequest
{
    private const BLACKLIST_NAME = [
        'henryvef',
    ];

    private const LINK_PATTERN = '/https?:\/\/|www\.|\b[a-z0-9-]+\.(com|net|org|ru|ua|info|biz|xyz|top|site|online|club|shop|store)\b/iu';

    private const MIN_FILL_SECONDS = 3;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->redirect = '/#contact-form';

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                new NotInArray(self::BLACKLIST_NAME)
            ],
            'phone' => 'required|string|max:255',
            'message' => [
                'nullable',
                'string',
                'max:50000',
                'not_regex:' . self::LINK_PATTERN,
            ],
        ];
    }

    /**
     * @return bool
     */
    public function isSpam(): bool
    {
        return $this->filled('website') || $this->wasSentTooFast();
    }

    /**
     * A missing or forged timestamp means the form was never loaded.
     *
     * @return bool
     */
    private function wasSentTooFast(): bool
    {
        try {
            return now()->timestamp - decrypt($this->input('started_at')) < self::MIN_FILL_SECONDS;
        } catch (Throwable $e) {
            return true;
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => "Вкажіть Ваше ім'я",
            'name.max' => 'Максимальна довжина поля 255 символів',
            'phone.required' => 'Вкажіть номер телефону',
            'phone.max' => 'Максимальна довжина поля 255 символів',
            'message.max' => 'Максимальна довжина поля 50000 символів',
            'message.not_regex' => 'Будь ласка, приберіть посилання з коментаря',
        ];
    }
}
