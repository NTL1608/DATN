<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
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
            'star' => ['required', 'numeric'],
            'content'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'star.required' => 'Vui lòng chọn số sao đánh giá',
            'star.numeric' => 'Số sao đánh giá phải ở định dạng số',
            'content.required' => 'Vui lòng nhập nội dung đánh giá',
        ];
    }
}
