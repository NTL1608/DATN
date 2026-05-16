<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'  => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $this->id,
            'citizen_id_number'  => 'required|unique:users,citizen_id_number,' . $this->id,
            'insurance_card_number'  => 'nullable|unique:users,insurance_card_number,' . $this->id,
            'gender' => 'required',
            'birthday' => 'required',
            'password' => 'required',
            'phone' => 'required|unique:users,phone,' . $this->id,
            'password_confirm' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép để trống.',
            'email.required' => 'Dữ liệu không được phép để trống.',
            'email.unique' => 'Dữ liệu không thể trùng lặp',
            'email.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'password.required' => 'Dữ liệu không được phép để trống.',
            'password_confirm.required' => 'Dữ liệu không được phép để trống.',
            'password_confirm.same' => 'Mật khẩu không trùng khớp',
            'gender.required' => 'Dữ liệu không được phép để trống.',
            'birthday.required' => 'Dữ liệu không được phép để trống.',
            'phone.required' => 'Dữ liệu không được phép để trống.',
            'phone.unique' => 'Dữ liệu không thể trùng lặp',
            'citizen_id_number.required' => 'Dữ liệu không được phép để trống.',
            'citizen_id_number.unique' => 'Dữ liệu không thể trùng lặp',
            'insurance_card_number.unique' => 'Dữ liệu không thể trùng lặp',

        ];
    }
}
