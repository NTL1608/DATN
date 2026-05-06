<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
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
            'role'  => 'required',
            'phone'  => 'required',
            'position'  => 'required',
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
            'email.required' => 'Dữ liệu không được phép để trống',
            'email.unique' => 'Dữ liệu không thể trùng lặp',
            'email.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'password.required' => 'Dữ liệu không được phép để trống',
            'role.required' => 'Dữ liệu không được phép để trống',
            'phone.required' => 'Dữ liệu không được phép để trống',
            'position.required' => 'Dữ liệu không được phép để trống',
            'images.image'               => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes'               => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
