<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    public function rules() :array
    {
        $donationRoute = $this->route('Donation') ?? $this->route('donation');
        $donationId = is_object($donationRoute) ? $donationRoute->id : $donationRoute;

        return [
            'donor_id' => 'required|exists:donors,id',
            'received_at' => 'required|date',
            'donation_type_id' => 'required|exists:donation_types,id',
            'amount_value' => 'nullable|numeric|min:0',
            'donation_amount' => 'nullable|string|max:255',
            'donation_unit_id' => 'required|exists:donation_units,id',
            'receipt_number' => [
                'required',
                'max:255',
                Rule::unique('donations', 'receipt_number')->ignore($donationId),
            ],
            'received_by_admin_id' => 'required|exists:admins,id',
            'donation_month' => 'nullable|integer|between:1,12',
            'occasion' => 'nullable|string|max:255',
            'created_at' => 'nullable|date',
            'asset_id' => 'nullable',
            'asset_count' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'donor_id.required' => 'يجب تحديد المتبرع',
            'received_at.required' => 'يجب تحديد تاريخ الاستلام',
            'donation_type_id.required' => 'يجب تحديد تصنيف التبرع',
            'donation_unit_id.required' => 'يجب تحديد وحدة التبرع',
            'receipt_number.required' => 'يجب إدخال رقم الوصل',
            'receipt_number.unique' => 'رقم الوصل مستخدم من قبل',
            'received_by_admin_id.required' => 'يجب تحديد المسؤول عن الاستلام',
        ];
    }



}
