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
        return [
            "donor_id"=>"required",
            "donation_amount"=>"required|min:1",
            "donation_type"=>"required",
            "created"=>"required",
        ];
    }

    public function messages()
    {
        return [
            "donor_id.required" => "يجب تحديد المتبرع",
            "donation_amount.required" => "يجب تحديد المبلغ",
            "donation_amount.min" => "  يجب تحديد مبلغ صالح ",
            "donation_type.required"=>"يجب تحديد النوع",
            "created.required"=>" يجب  تحديد التاريخ"
        ];
    }



}
