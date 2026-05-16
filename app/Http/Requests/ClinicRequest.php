<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicRequest extends FormRequest
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
            'name' => 'required|unique:clinics,name,'.$this->id,
            'email' => 'nullable|email|max:191|unique:clinics,email,'.$this->id,
            'phone'  => 'nullable',
            'address'  => 'nullable',
            'description'  => 'nullable|max:200',
            'image'  => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép để trống',
            'name.unique' => 'Dữ liệu không được phép trùng lặp',
            'email.required' => 'Dữ liệu không được phép để trống',
            'email.unique' => 'Dữ liệu không được phép trùng lặp',
            'email.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'phone.required' => 'Dữ liệu không được phép để trống',
            'address.required' => 'Dữ liệu không được phép để trống',
            'description.max' => 'Dữ liệu vượt quá số ký tự cho phép',
            'image.image'               => 'Vui lòng nhập đúng định dạng file ảnh',
            'image.mimes'               => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
