<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSetting extends FormRequest
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
            'title'           => 'required',
            'vat_number'      => 'required',
            'address'         => 'required',
            'sub_address'     => 'required',
            'branch'          => 'required',
            'section'         => 'required',
        ];
    }
    public function messages(){
        return[
            'title.required'           => 'اسم الشركة مطلوب',
            'vat_number.required'      => 'يرجي ادخال رقم الإشهار',
            'address.required'         => 'يرجي ادخال العنوان',
            'sub_address.required'     => 'يرجي ادخال عنوان فرعي',
            'branch.required'          => 'يرجي ادخال اسم الفرع',
            'section.required'         => 'يرجي ادخال اسم القطاع',
        ];
    }
}
