<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingAppointmentRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required',
            'birthday' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'specialty_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép để trống',
            'email.required' => 'Dữ liệu không được phép để trống',
            'email.email' => 'Email không đúng định dạng',
            'gender.required' => 'Dữ liệu không được phép để trống',
            'birthday.required' => 'Dữ liệu không được phép để trống',
            'phone.required' => 'Dữ liệu không được phép để trống',
            'address.required' => 'Dữ liệu không được phép để trống',
            'specialty_id.required' => 'Dữ liệu không được phép để trống',

        ];
    }
}
