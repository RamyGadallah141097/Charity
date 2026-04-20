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
            'phone' => 'required|digits_between:7,15|unique:donors,phone',
            'phone_second' => 'nullable|digits_between:7,15',
            'relative_phone' => 'nullable|digits_between:7,15',
            'address' => 'nullable|string',
            'detailed_address' => 'nullable|string',
            'burn_date' => 'nullable|date',
            'governorate_id' => 'nullable|exists:governorates,id',
            'center_id' => 'nullable|exists:centers,id',
            'village_id' => 'nullable|exists:villages,id',
            'preferred_donation_types' => 'nullable|array',
            'preferred_donation_types.*' => 'exists:donation_types,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'يرجي ادخال اسم المتبرع',
            'phone.required'       => 'يرجي ادخال هاتف المتبرع',
            'phone.digits_between' => 'رقم الهاتف لا يجب ان يقل عن 7 ارقام',
            'preferred_donation_types.*.exists' => 'نوع التبرع المختار غير صحيح',
        ];
    }
}
