<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidatePeople extends FormRequest
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
            'name' => 'required|string|max:200',
            'surname' => 'required|string|max:200',
            'id_or_passport' => 'required|in:i_d,passport',
            'valid_saps_clearance' => 'required|in:yes,no,requested',
            'valid_fingerprint' => 'required|in:yes,no,requested',
            'valid_certified_id' => 'required|in:yes,no,requested',
            'passport' => 'unique:people,passport',
            'identity_number' => 'unique:people,identity_number'
        ];
    }
}
