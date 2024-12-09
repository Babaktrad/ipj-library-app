<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class SubscriberUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return resolve('api-auth')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $dateFormat = config('app.date_format');
        $expirationEnd = Carbon::now()->addDays(config('library.subscribers.subscription_minimum_length'));

        return [
            'id' => 'bail|required|string|exists:subscribers,id',
            'name' => 'bail|required|string|max:255',
            'national_id' => [
                'bail',
                'required',
                'string',
                'size:10',
                'regex:/^[0-9]{10}$/',
                "unique:subscribers,national_id,$this->id"
            ],
            'subscription_expire_date' => ['bail', 'required', "date_format:$dateFormat", "after:$expirationEnd"],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $expirationMinimumLength = config('library.subscribers.subscription_minimum_length', 30);

        return [
            'subscription_expire_date.after' => __(
                'messages.subscribers.expiration_minimum_length',
                ['days' => $expirationMinimumLength]
            ),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'national_id' => __('validation.attributes.national_id'),
            'subscription_expire_date' => __('validation.attributes.subscribers.subscription_expire_date'),
        ];
    }
}
