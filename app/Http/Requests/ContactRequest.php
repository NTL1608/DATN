<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            //
            'name' => ['required'],
            'email' => 'required|email',
            'phone' => ['required'],
            'message' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép trống',
            'email.required' => 'Dữ liệu không được phép trống',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Dữ liệu không được phép trống',
            'message.required' => 'Dữ liệu không được phép trống',
        ];
    }
}
