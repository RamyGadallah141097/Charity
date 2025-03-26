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
            'wife_name'        => 'required',
            'husband_national_id' => 'required|numeric|digits:14|unique:users,husband_national_id',
            'wife_national_id' => 'required|numeric|digits:14|unique:users,wife_national_id',
            'social_status'    => 'required|in:0,1,2,3',
            'work_type'        => 'required',
            'nearest_phone'    => 'required|string|max:11|unique:users,nearest_phone',
            'salary'           => 'nullable|numeric',
            'pension'          => 'nullable|numeric',
            'insurance'        => 'nullable|numeric',
            'dignity'          => 'nullable|numeric',
            'trade'            => 'nullable|numeric',
            'pillows'          => 'nullable|numeric',
            'other'            => 'nullable|numeric',
            'gross_income'     => 'nullable|numeric',
            'rent'             => 'nullable|numeric',
            'gas'              => 'nullable|numeric',
            'debt'             => 'nullable|numeric',
            'water'            => 'nullable|numeric',
            'electricity'      => 'nullable|numeric',
            'association'      => 'nullable|numeric',
            'food'             => 'nullable|numeric',
            'study'            => 'nullable|numeric',
            'gross_expenses'   => 'nullable|numeric',
            'standard_living'  => 'nullable|numeric',
            'Case_evaluation'  => 'nullable|string',
//            'attachments' => 'nullable|array',
//            'attachments.*' => 'nullable|mimes:jpg,png,jpeg,pdf,doc,docx',
//            'child_names' => 'array',
//            'child_names.*' => 'required|string',
//            'children_national_id' => 'array',
//            'children_national_id.*' => 'required|numeric|digits:14',
//            'age' => 'nullable|array',
//            'age.*' => 'nullable|numeric',
//            'school' => 'nullable|array',
//            'school.*' => 'nullable|string',
//            'monthly_cost' => 'nullable|array',
//            'monthly_cost.*' => 'nullable|numeric',
//            'notes' => 'array',
//            'notes.*' => 'required|string',
//            'patient_name' => 'array',
//            'patient_name.*' => 'required|string',
//            'treatment' => 'array',
//            'treatment.*' => 'nullable|string',
//            'treatment_pay_by' => 'array',
//            'treatment_pay_by.*' => 'nullable|string',
//            'type' => 'array',
//            'type.*' => 'required|in:0,1',
//            'doctor_name' => 'array',
//            'doctor_name.*' => 'nullable|string',
//            'is_insurance' => 'array',
//            'note' => 'array',
//            'note.*' => 'nullable',

        ];
    }


    public function messages()
    {
        return [
            'husband_name.required'     => 'يرجي ادخال اسم الزوج',
            'wife_name.required'        => 'يرجي ادخال اسم الزوجة',
            'husband_national_id.required' => 'يرجي ادخال الرقم القومى للزوج',
            'husband_national_id.numeric' => 'الرقم القومى للزوج يجب أن يكون رقمًا',
            'husband_national_id.digits' => 'الرقم القومى للزوج يجب أن يتكون من 14 رقمًا',
            'husband_national_id.unique' => 'الرقم القومى للزوج موجود بالفعل',
            'wife_national_id.required' => 'يرجي ادخال الرقم القومى للزوجة',
            'wife_national_id.numeric' => 'الرقم القومى للزوجة يجب أن يكون رقمًا',
            'wife_national_id.digits' => 'الرقم القومى للزوجة يجب أن يتكون من 14 رقمًا',
            'wife_national_id.unique' => 'الرقم القومى للزوجة موجود بالفعل',
            'social_status.required'    => 'يرجي ادخال الحالة الاجتماعية',
            'social_status.in'          => 'الحالة الاجتماعية يجب أن تكون من أحد القيم التالية: أعزب، متزوج، مطلق، متوفى',
            'work_type.required'        => 'يرجي ادخال نوع العمل',
            'nearest_phone.required'    => 'يرجي ادخال هاتف تواصل',
            'nearest_phone.unique'      => 'هاتف تواصل موجود بالفعل',
            'nearest_phone.numeric'     => 'رقم الهاتف يجب أن يكون رقمًا',
            'nearest_phone.max'         => 'رقم الهاتف يجب ألا يتجاوز 11 رقمًا',
            'salary.numeric'            => 'الراتب يجب أن يكون رقمًا',
            'pension.numeric'           => 'المعاش يجب أن يكون رقمًا',
            'insurance.numeric'         => 'التأمين يجب أن يكون رقمًا',
            'dignity.numeric'           => 'كرامة يجب أن يكون رقمًا',
            'trade.numeric'             => 'التجارة يجب أن يكون رقمًا',
            'pillows.numeric'           => 'سادات يجب أن يكون رقمًا',
            'other.numeric'             => 'غير ذلك يجب أن يكون رقمًا',
            'gross_income.numeric'      => 'إجمالى الدخل يجب أن يكون رقمًا',
            'rent.numeric'              => 'الإيجار يجب أن يكون رقمًا',
            'gas.numeric'               => 'الغاز يجب أن يكون رقمًا',
            'debt.numeric'              => 'الديون يجب أن يكون رقمًا',
            'water.numeric'             => 'المياه يجب أن يكون رقمًا',
            'electricity.numeric'       => 'الكهرباء يجب أن يكون رقمًا',
            'association.numeric'       => 'الجمعية يجب أن يكون رقمًا',
            'food.numeric'              => 'الطعام يجب أن يكون رقمًا',
            'study.numeric'             => 'الدراسة يجب أن يكون رقمًا',
            'gross_expenses.numeric'    => 'إجمالى النفقات يجب أن يكون رقمًا',
            'standard_living.numeric'   => 'مستوى المعيشة يجب أن يكون رقمًا',
            'attachments.*.mimes'       => 'المرفقات يجب أن تكون من الأنواع التالية: jpg, png, jpeg, pdf, doc, docx',
            'child_names.*.required'    => 'اسم الطفل مطلوب',
            'children_national_id.*.required' => 'الرقم القومى للطفل مطلوب',
            'children_national_id.*.numeric'  => 'الرقم القومى للأطفال يجب أن يكون رقمًا',
            'children_national_id.*.digits'   => 'الرقم القومى للأطفال يجب أن يتكون من 14 رقمًا',
            'patient_name.array'        => 'أسماء المرضى يجب أن تكون مصفوفة',
            'patient_name.*.required'   => 'اسم المريض مطلوب',
            'type.*.required'           => 'نوع المريض مطلوب',
            'notes.*.required'          => 'ملاحظات المريض مطلوبة',

        ];
    }
}
