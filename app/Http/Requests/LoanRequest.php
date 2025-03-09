<?php

namespace App\Http\Requests;

use App\Models\Loan;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
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
            'borrower_id' => 'required|exists:borrowers,id',
            'borrower_phone' => 'nullable',
            'loan_amount' => [
                'required', 'numeric', 'max:100000',
                function ($attribute, $value, $fail) {
                    $maxLoan = Setting::latest()->first()? Setting::latest()->first()->maxLoan ?? 0 : 0;
                    $currentYear = now()->year;
                    $totalLoansThisYear = Loan::where("borrower_id", request()->borrower_id)
                        ->whereYear('created_at', $currentYear)
                        ->sum("loan_amount");
                    if (($totalLoansThisYear + $value) > $maxLoan) {
                        $fail("المبلغ الإجمالي للقروض في هذه السنة يجب ألا يتجاوز $maxLoan.");
                    }
                }
            ],
            'loan_date' => 'required|date',

        ];
    }

    public function messages()
    {
        return [
            'borrower_id.required' => 'يرجي ادخال اسم المتبرع',
            'borrower_id.exists' => 'يرجي ادخال اسم المتبرع صحيح',
            'loan_amount.required' => 'يرجي ادخال مبلغ القرض',
            'loan_amount.numeric' => 'يرجي ادخال مبلغ القرض صحيح',
            'loan_amount.max' => 'مبلغ القرض لا يتجاوز 100000',
            'loan_date.required' => 'يرجي ادخال تاريخ القرض',
        ];
    }
}

