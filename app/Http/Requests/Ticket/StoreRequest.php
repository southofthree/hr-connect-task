<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->role === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => [
                'required',
                'string',
                'max:140'
            ],
            'message' => [
                'required',
                'string',
                'max:25000'
            ],
            'attachment' => [
                'nullable',
                'file',
                'max:2500'
            ]
        ];
    }
}
