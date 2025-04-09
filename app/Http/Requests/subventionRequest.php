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
        if (request()->sub_type == 1) {
            return [
                "user_id" => "required|exists:users,id",
                "asset_id" => "required|exists:assets,id",
                "asset_count" => [
                    "required",
                    "numeric",
                    "min:1",
                    function ($attribute, $value, $fail) {
                        $asset = Asset::find(request()->input('asset_id'));

                        if (!$asset) {
                            $fail("The selected asset does not exist.");
                            return;
                        }

                        if ($value > $asset->counter) {
                            toastr()->error("عدد العينيه غير كافيه");
                            $fail("عدد العينيه غير كافيه");
                        }
                    },
                ],
                "type" => [],
            ];
        }

        return [
            "user_id" => "required|exists:users,id",
            "price" => [
                "required",
                "numeric",
                "min:1",
                function ($attribute, $value, $fail) {
                    $maxLoan = Setting::latest()->first()?->maxSubvention ?? 0;
                    $currentYear = now()->year;
                    $totalSubvention = $value + Subvention::where("user_id", request()->input('user_id'))
                            ->whereYear('created_at', $currentYear)
                            ->sum("price");

                    if ($totalSubvention > $maxLoan) {
                        toastr()->error("مبلغ الاعانه يجب ألا يتجاوز في السنه $maxLoan.");
                        $fail("مبلغ الاعانه يجب ألا يتجاوز في السنه $maxLoan.");
                    }
                }
            ],
            "type" => [],
        ];
    }

    public function messages()
    {
        return [
            'price.min' => 'مبلغ الاعانة يجب أن يكون على الأقل 1.',
            'price.required' => 'مبلغ الاعانة مطلوب.',
            'price.numeric' => 'مبلغ الاعانة يجب أن يكون رقماً.',
            'asset_count.min' => 'عدد العينية يجب أن يكون على الأقل 1.',
        ];
    }
}
