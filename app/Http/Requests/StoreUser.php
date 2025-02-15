<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'husband_name'     => 'required',
            'social_status'    => 'required|in:single,married,divorced,widow',
            'husband_birthday' => 'required|date|before:today',
            'work_type'        => 'required',
            'nearest_phone'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'husband_name.required'     => 'يرجي ادخال اسم الزوج',
            'social_status.required'    => 'يرجي ادخال الحالة الاجتماعية',
            'husband_birthday.required' => 'تاريخ الميلاد للزوج مطلوب',
            'work_type.required'        => 'يرجي ادخال نوع العمل',
            'nearest_phone.required'    => 'يرجي ادخال هاتف تواصل',
        ];
    }
}
