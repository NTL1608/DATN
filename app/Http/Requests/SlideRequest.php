<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'title' => 'required | max:191 | unique:slides,title,'.$this->id,
            'image'  => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'files.*'  => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'target' => 'required',
            'active' => 'required',
            'sort' => 'required|integer|unique:slides,sort,'.$this->id
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Dữ liệu không thể để trống',
            'title.unique' => 'Dữ liệu đã bị trùng',
            'title.max' => 'Vượt quá số ký tự cho phép',
            'images.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'images.max' => 'Vượt quá kích thước cho phép',
            'files.image' => 'Vui lòng nhập đúng định dạng file ảnh',
            'files.mimes' => 'Vui lòng nhập đúng định dạng file ảnh',
            'files.max' => 'Vượt quá kích thước cho phép',
            'target.required' => 'Dữ liệu không thể để trống',
            'active.required' => 'Dữ liệu không thể để trống',
            'sort.required' => 'Dữ liệu không thể để trống',
            'sort.integer' => 'Vui lòng nhập só nguyên',
            'sort.unique' => 'Dữ liệu đã bị trùng',
        ];
    }
}
