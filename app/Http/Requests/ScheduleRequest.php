<?php

namespace App\Http\Requests;

use App\Rules\CheckDateSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ScheduleRequest extends FormRequest
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

        $rules = [
            'max_number' => 'required|integer|min:1',
            'jump' => 'required',
        ];

        if ($request->submit == 'update') {
            $rules['date_schedule'] = ['required'];
        } else {
            $rules['date_schedule'] = ['required', new CheckDateSchedule($request)];
        }
            return $rules;
    }

    public function messages()
    {
        return [
            'date_schedule.required' => 'Dữ liệu không được phép để trống',
            'max_number.required' => 'Dữ liệu không được phép để trống',
            'max_number.min' => 'Dữ liệu không được phép nhỏ hơn 1',
            'jump.required' => 'Dữ liệu không được phép để trống',
        ];
    }
}
