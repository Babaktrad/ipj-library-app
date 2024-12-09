<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationReserveRequest extends FormRequest
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
        return [
            'book_id' => 'bail|required|string|uuid|exists:books,id',
            'subscriber_id' => 'bail|required|string|uuid|exists:subscribers,id',
            'period' => 'bail|required|integer|min:1',
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
            'book_id' => __('validation.attributes.books.book_id'),
            'subscribers_id' => __('validation.attributes.subscribers.subscriber_id'),
            'period' => __('validation.attributes.reservations.period'),
        ];
    }
}
