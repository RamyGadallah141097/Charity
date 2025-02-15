<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonate extends FormRequest
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
            'name'  => 'required|max:255',
            'phone' => 'required|max:255|digits_between:7,50|max:12',
            'address' => 'required',
            'burn_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'يرجي ادخال اسم المتبرع',
            'phone.required'       => 'يرجي ادخال هاتف المتبرع',
            'phone.max'       => 'يرجي ادخال هاتف صالح',
            'phone.digits_between' => 'رقم الهاتف لا يجب ان يقل عن 7 ارقام',
            'address.required'       => 'يرجي ادخال العنوان',
            'burn_date.required'       => 'يرجي ادخال تاريخ الميلاد',
        ];
    }
}
