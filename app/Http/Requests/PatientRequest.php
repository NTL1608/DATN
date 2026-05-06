<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PatientRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validate = [
            'email' => 'required|email|max:191|unique:users,email,'.$this->id,
            'name'  => 'required|max:191',
            'phone'  => 'required',
            'citizen_id_number'  => 'nullable|unique:users,citizen_id_number,'.$this->id,
            'insurance_card_number'  => 'nullable|unique:users,insurance_card_number,'.$this->id,
            'images'  => 'nullable|image|mimes:jpeg,jpg,png',
        ];

        if ($request->submit !== 'update') {
            $validate['password'] = 'required | max:191 ';
        }

        return $validate;
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép để trống',
            'name.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'email.required' => 'Dữ liệu không được phép để trống',
            'email.unique' => 'Dữ liệu không thể trùng lặp',
            'email.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'password.required' => 'Dữ liệu không được phép để trống',
            'role.required' => 'Dữ liệu không được phép để trống',
            'phone.required' => 'Dữ liệu không được phép để trống',
            'images.image'               => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes'               => 'Vui lòng nhập đúng định dạng file ảnh',
            'citizen_id_number.unique' => 'Dữ liệu không thể trùng lặp',
            'insurance_card_number.unique' => 'Dữ liệu không thể trùng lặp',
        ];
    }
}
