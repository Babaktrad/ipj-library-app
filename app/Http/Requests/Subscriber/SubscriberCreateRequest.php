<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SubscriberCreateRequest extends FormRequest
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
        $expirationStart = Carbon::now()->addDays(config('library.subscribers.subscription_minimum_length'));

        return [
            'name' => 'bail|required|string|max:255',
            'national_id' => 'bail|required|string|size:10|unique:subscribers,national_id|regex:/^[0-9]{10}$/',
            'subscription_expire_date' => ['bail', 'required', "date_format:$dateFormat", "after:$expirationStart"],
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
