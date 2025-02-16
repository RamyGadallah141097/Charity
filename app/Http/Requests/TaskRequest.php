<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        if (request()->method('put') ) {
            return [
                "title" => "required|max:30",
                "description" => "required",

                "from_date" => [
                    "required",
                    "date",
                ],

                "to_date" => [
                    "required",
                    "date",
                    "after_or_equal:today",
                    "after:from_date",
                ],

            ];
        }else{
            return [
                "title" => "required|max:30",
                "description" => "required",

                "from_date" => [
                    "required",
                    "date",
                    "after_or_equal:today",
                    "before:to_date",
                ],
                "to_date" => [
                    "required",
                    "date",
                    "after_or_equal:today",
                    "after:from_date",
                ],

            ];
        }
    }


    public function messages()
    {
        return [
            "title.required" => "يرجي ادخال العنوان",
            "title.max" => "العنوان يجب ان يكون اقل من 30 حرف",
            "description.required" => "يرجي ادخال الوصف",
            "from_date.required" => "يرجي ادخال تاريخ البداية",
            "from_date.date" => "يرجي ادخال تاريخ البداية بشكل صحيح",
            "from_date.before" => "يجب أن يكون تاريخ البداية قبل تاريخ النهاية.",
            "from_date.after_or_equal" => "يجب أن يكون تاريخ البداية اليوم أو بعده.",

            "to_date.required" => "يرجي ادخال تاريخ الانتهاء",
            "to_date.date" => "يرجي ادخال تاريخ الانتهاء بشكل صحيح",
            "to_date.after" => "يجب أن يكون تاريخ النهاية بعد تاريخ البداية.",
            "to_date.after_or_equal" => "يجب أن يكون تاريخ الانتهاء اليوم أو بعده.",


        ];
    }
}
