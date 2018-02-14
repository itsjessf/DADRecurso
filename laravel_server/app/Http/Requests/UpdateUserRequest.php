<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'nickname' => 'required|min:4|regex:/([A-Za-z0-9 ])+/|unique:users,nickname,'.$this->get('id'),
            'email' => 'required|email|unique:users,email,'.$this->get('id'),
            'name' => 'required|regex:/([A-Za-z0-9 ])+/|max:255',
            'password_old' => 'required',
            'password' => 'min:5|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'The :attribute is already taken.',
            'required' => 'The :attribute is required',
            'min' => 'The :attribute must be longer than :min.',
            'max' => 'The :attribute must be shorter than :max.',
            'regex' => 'The :attribute has invalid characters',
            'Email' => 'The :attribute is an invalid e-mail',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors'=>$validator->errors()], 422));
    }
}
