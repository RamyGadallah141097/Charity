<?php

namespace App\Http\Requests;

use App\Models\Asset;
use App\Models\Setting;
use App\Models\Subvention;
use Illuminate\Foundation\Http\FormRequest;

class SubventionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "user_ids" => ['required', 'array', 'min:1'],
            "user_ids.*" => 'exists:users,id',
            "donation_type_id" => 'required|exists:donation_types,id',
            "type" => ['required', 'in:monthly'],
            "comment" => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'user_ids.required' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.array' => 'المستفيدين يجب أن يكونوا في صورة قائمة.',
            'user_ids.min' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.*.exists' => 'أحد المستفيدين المختارين غير موجود.',
            'donation_type_id.required' => 'يرجى اختيار الخزنة التي سيتم الصرف منها.',
            'donation_type_id.exists' => 'الخزنة المختارة غير موجودة.',
            'type.in' => 'نوع الصرف غير صحيح.',
        ];
    }
}
