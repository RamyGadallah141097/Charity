<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationsRequest extends FormRequest
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

        $rules = [
            "donor_id" => "required",
            "donation_type" => ["required", "string"],
            "created_at" => "required",
            "donation_amount" => ['required', 'integer', 'min:1'],
        ];

        if ($this->donation_type == 3) {
            $rules['donation_amount'] = ['required', 'string', 'regex:/^[^\d]+$/'];
        }

        return $rules;

    }

    public function messages()
    {
        return [
            "donor_id.required" => "يجب تحديد المتبرع",
            "donation_amount.required" => "يجب تحديد المبلغ",
            "donation_amount.string" => "  يجب   ادخال  التبرع نصا ",
            "donation_amount.integer" => "  يجب   ادخال  التبرع رقما   ",
            "donation_type.required"=>"يجب تحديد النوع",
            "created.required"=>" يجب  تحديد التاريخ",
            'donation_amount.regex' => 'يجب   ادخال  التبرع نصا في حاله التبرع العيني.',
        ];
    }



}
