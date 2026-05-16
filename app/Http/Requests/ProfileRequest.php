<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProfileRequest extends FormRequest
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
            'name'  => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$this->id,
            'phone'  => 'required',
            'images'  => 'nullable|image|mimes:jpeg,jpg,png',
        ];

        return $validate;
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập vào tên tài khoản',
            'email.unique' => 'Tên tài khoản không thể trùng lặp',
            'email.max' => 'Tên tài khoản vượt quá số ký tự cho phép',
            'images.image'               => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes'               => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
