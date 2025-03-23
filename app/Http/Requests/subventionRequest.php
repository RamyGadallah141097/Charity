<?php

namespace App\Http\Requests;

use App\Models\Asset;
use App\Models\Loan;
use App\Models\Setting;
use App\Models\Subvention;
use Illuminate\Foundation\Http\FormRequest;

class subventionRequest extends FormRequest
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
    public function rules(): array
    {
        {
            if (request()->sub_type == 1) {
                return [
                    "user_id" => "required|exists:users,id",
                    "asset_id" => "required|exists:assets,id",
                    "asset_count" => [
                        "numeric" , "min:1",
                        function ($attribute, $value, $fail) {
                            $asset = Asset::find(request()->input('asset_id'));

                            if (!$asset) {
                                $fail("The selected asset does not exist.");
                                return;
                            }

                            // Validate asset count
                            if ($value > $asset->counter) {
                                toastr()->error("عدد العينيه غير كافيه");
                                $fail("عدد العينيه غير كافيه");
                            }
                        },
                    ],
                    "type" => [],
                ];
            }else{
                return [

//                    "user_id" => "required|exists:users,id",
//                    "price" => [ "min:1" ,
//                        function ($attribute, $value, $fail) {
//                            $maxLoan = Setting::latest()->first()? Setting::latest()->first()->maxSubvention ?? 0 : 0;
//                            $currentYear = now()->year;
//                            $totalSubvention = $value + Subvention::where("user_id", request()->input('user_id'))->whereYear('created_at', $currentYear)->sum("price");
//                            if ($totalSubvention > $maxLoan) {
//                                $fail("مبلغ القرض يجب ألا يتجاوز $maxLoan.");
//                            }
//                        }
//                    ],
//                    "type" => [],
                ];
            }

            return []; // Ensure the function always returns an array
        }


    }
}
