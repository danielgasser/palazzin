<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveReservation extends FormRequest
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
            'reservation_started_at' => 'bail|required|date|date_format:d.m.Y|before:reservation_ended_at|after:yesterday',
            'reservation_ended_at' => 'bail|required|date|date_format:d.m.Y|after:reservation_started_at',
        ];
    }

}
