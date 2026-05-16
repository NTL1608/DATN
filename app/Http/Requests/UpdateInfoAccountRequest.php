<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInfoAccountRequest extends FormRequest
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
            'email' => 'required|email|max:191',
            'phone' => 'required',
            'citizen_id_number'  => 'nullable|unique:users,citizen_id_number,'.$this->id,
            'insurance_card_number'  => 'nullable|unique:users,insurance_card_number,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập vào họ tên',
            'email.required' => 'Vui lòng nhập vào email đăng nhập',
            'email.unique' => 'Email đăng nhập không thể trùng lặp',
            'email.max' => 'Email vượt quá số ký tự cho phép',
            'phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'citizen_id_number.unique' => 'Dữ liệu không thể trùng lặp',
            'insurance_card_number.unique' => 'Dữ liệu không thể trùng lặp',

        ];
    }
}
