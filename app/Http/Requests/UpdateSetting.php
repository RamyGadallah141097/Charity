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
            'title'   => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'logo'    => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ];
    }
    public function messages(){
        return[
            'title.required' => 'اسم المؤسسة مطلوب',
            'address.required' => 'يرجى إدخال العنوان',
            'logo.image' => 'اللوجو يجب أن يكون صورة',
            'logo.mimes' => 'صيغة اللوجو يجب أن تكون jpg أو jpeg أو png أو gif أو webp',
        ];
    }
}
