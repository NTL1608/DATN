<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecialtyRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'image'  => 'nullable|image|mimes:jpeg,jpg,png,webp',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Dữ liệu không được phép để trống',
            'image.image'   => 'Vui lòng nhập đúng định dạng file ảnh',
            'image.mimes'   => 'Vui lòng nhập đúng định dạng file ảnh',
        ];
    }
}
