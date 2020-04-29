<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class RespondRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $ticket = $this->route('ticket');
        $user = $this->user();

        return $user->id === $ticket->client_id || $user->id === $ticket->manager_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
