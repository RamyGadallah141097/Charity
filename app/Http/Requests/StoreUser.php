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
            // 'husband_name'     => 'required',
            // 'husband_national_id' => 'required|numeric|digits:14',
            // 'social_status'    => 'required|in:0,1,2,3',
            // 'husband_birthday' => 'required|date|before:today',
            // 'work_type'        => 'required',
            // 'nearest_phone'    => 'required',
            // 'salary'           => 'nullable|numeric',
            // 'pension'          => 'nullable|numeric',
            // 'insurance'        => 'nullable|numeric',
            // 'dignity'          => 'nullable|numeric',
            // 'trade'            => 'nullable|numeric',
            // 'pillows'          => 'nullable|numeric',
            // 'other'            => 'nullable|numeric',
            // 'gross_income'     => 'nullable|numeric',
            // 'rent'             => 'nullable|numeric',
            // 'gas'              => 'nullable|numeric',
            // 'debt'             => 'nullable|numeric',
            // 'water'            => 'nullable|numeric',
            // 'electricity'      => 'nullable|numeric',
            // 'association'      => 'nullable|numeric',
            // 'food'             => 'nullable|numeric',
            // 'study'            => 'nullable|numeric',
            // 'gross_expenses'   => 'nullable|numeric',
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
