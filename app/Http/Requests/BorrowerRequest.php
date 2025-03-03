<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'nationalID' => 'required',
            'address' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'guarantors.*.name' => 'required|string|max:255',
            'guarantors.*.phone' => 'required',
            'guarantors.*.nationalID' => 'required',
            'guarantors.*.address' => 'required|string|max:255',
            'guarantors.*.job' => 'required|string|max:255',
        ];

        if (request()->isMethod('post')) {
            $rules['phone'] .= '|unique:borrowers,phone';
            $rules['nationalID'] .= '|unique:borrowers,nationalID';
        }


        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'يرجى إدخال اسم المقترض.',
            'name.string' => 'يجب أن يكون الاسم نصيًا.',
            'name.max' => 'يجب ألا يزيد الاسم عن 255 حرفًا.',

            'phone.required' => 'يرجى إدخال رقم الهاتف.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصيًا.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 12 رقمًا.',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            'nationalID.required' => 'يرجى إدخال الرقم القومي.',
            'nationalID.string' => 'يجب أن يكون الرقم القومي نصيًا.',
            'nationalID.min' => 'يجب أن يحتوي الرقم القومي على 14 رقمًا على الأقل.',
            'nationalID.max' => 'يجب ألا يتجاوز الرقم القومي 15 رقمًا.',

            'address.required' => 'يرجى إدخال العنوان.',
            'address.string' => 'يجب أن يكون العنوان نصيًا.',
            'address.max' => 'يجب ألا يتجاوز العنوان 255 حرفًا.',

            'job.required' => 'يرجى إدخال المهنة.',
            'job.string' => 'يجب أن تكون المهنة نصًا.',
            'job.max' => 'يجب ألا تتجاوز المهنة 255 حرفًا.',

            //  الضامنين
            'guarantors.*.name.required' => 'يرجى إدخال اسم الضامن.',
            'guarantors.*.name.string' => 'يجب أن يكون اسم الضامن نصيًا.',
            'guarantors.*.name.max' => 'يجب ألا يتجاوز اسم الضامن 255 حرفًا.',

            'guarantors.*.phone.required' => 'يرجى إدخال رقم هاتف الضامن.',
            'guarantors.*.phone.max' => 'يجب ألا يتجاوز رقم هاتف الضامن 12 رقمًا.',

            'guarantors.*.nationalID.required' => 'يرجى إدخال الرقم القومي للضامن.',
            'guarantors.*.nationalID.string' => 'يجب أن يكون الرقم القومي للضامن نصيًا.',
            'guarantors.*.nationalID.max' => 'يجب ألا يتجاوز الرقم القومي للضامن 20 رقمًا.',

            'guarantors.*.address.required' => 'يرجى إدخال عنوان الضامن.',
            'guarantors.*.address.string' => 'يجب أن يكون العنوان نصيًا.',
            'guarantors.*.address.max' => 'يجب ألا يتجاوز العنوان 255 حرفًا.',

            'guarantors.*.job.required' => 'يرجى إدخال مهنة الضامن.',
            'guarantors.*.job.string' => 'يجب أن تكون المهنة نصًا.',
            'guarantors.*.job.max' => 'يجب ألا تتجاوز المهنة 255 حرفًا.',
        ];
    }

}
