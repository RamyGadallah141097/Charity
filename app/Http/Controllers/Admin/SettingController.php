<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSetting;
use App\Models\Setting;
use App\Traits\PhotoTrait;
use App\Traits\WebpTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use WebpTrait;
    public function index(){
            return view('Admin.setting.index');
    }

    public function update(UpdateSetting $request){
        $input = $request->except('_token');

        if($request->has('logo'))
            $input['logo'] = $this->saveImage($request->logo,'assets/uploads','file');

        Setting::first()->update($input);
        toastr()->success('تم تحديث البيانات بنجاح');
        return back();
    }
}
