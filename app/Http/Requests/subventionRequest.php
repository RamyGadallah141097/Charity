<?php

namespace App\Http\Requests;

use App\Models\Asset;
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
        return [
            "user_id" => "required|exists:users,id",
            "asset_id" => "required|exists:assets,id",
            "price" => "numeric",
            "asset_count" => [
                "required",
                "numeric",
                function ($attribute, $value, $fail) {
                    $asset = Asset::find($this->asset_id);

                    if (!$asset) {
                        $fail("The selected asset does not exist.");
                        return;
                    }

                    if ($value > $asset->counter) {
                        $fail("The asset count must be less than or equal to the available asset counter.");
                    }
                },
            ],
            "type" => [],

        ];
    }
}
