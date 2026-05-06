<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientByServiceRequest extends FormRequest
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
            'specialty_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
        ];
    }

    public function messages()
    {
        return [
            'specialty_id.required'            => 'Dữ liệu không được phép để trống',
            'start_date.required'            => 'Dữ liệu không được phép để trống',
            'end_date.required'            => 'Dữ liệu không được phép để trống',
            'start_date'            => 'Ngày bắt đầu phải lớn hơn ngày hiện tại',
            'end_date.after'            => 'Ngày kết thúc phải lớn hơn ngày bắt đầu',
        ];
    }
}
