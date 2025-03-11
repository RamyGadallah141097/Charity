<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\subventionRequest;
use App\Models\Asset;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\LockerLog;
use App\Models\Subvention;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubventionController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $data = Subvention::latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    $editButton = '';
                    $deleteButton = '';

                    if (auth()->user()->can('subventions.edit')) {
                        $editButton = '
                            <button type="button" data-id="' . $data->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('subventions.destroy')) {
                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $data->id . '" data-title="' . ($data->user->name ?? "غير معروف") . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                })

                ->editColumn('user_id', function ($data) {
                    return ($data->user->husband_name) ?? 'تم حذفه';
                })
                ->editColumn('price', function ($data) {
                    if ($data->price == 0){
                        return ' عدد : ' . $data->asset_count . ' من ' . ($data->asset ? ($data->asset->name ?? '-') : '-');
                    }else{
                        return " مبلغ قدره : " . $data->price . " جنيه";
                    }
                })
                ->editColumn('type', function ($data) {
                    if($data->type == 'once')
                        return 'مرة واحدة';
                    else
                        return 'إعانة شهرية';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/subventions/index');
        }
    }



    public function create()
    {
        $users = User::where('status','accepted')->whereDoesntHave('subvention')->select('id','husband_name')->latest()->get();
        $assets = Asset::all();
        return view('Admin/subventions/parts/create',compact('users' , "assets"));

    }


    public function store(subventionRequest $request)
    {
        try{
            $user = User::find($request->user_id);
            if ($request->sub_type == 0){
                if ($request->moneyType == 0){
                    if ($request->price <= $totalDonation = Donation::where("donation_type", 0)->sum("donation_amount")){
                        Subvention::create($request->except('_token' , "sub_type" , "moneyType"));
                        LockerLog::create([
                            "moneyType" => LockerLog::moneyTypeZakat,
                            "amount" => $request->price,
                            "type" => LockerLog::TYPE_MINUS,
                            "admin_id" => auth()->id(),
                            "comment" => "  زكاة جديده الي" . ($user ? $user->husband_name : "مجهول") .
                                " ورقم هاتفه " . ($user ? $user->nearest_phone : "غير متوفر"),
                        ]);
                    }else{
                        return response()->json(["status"=>500 , "message" => "لا توجد سيوله لهذه الاعانه"]);
                    }
                }else{
                    if ($request->price <= $totalDonation = Donation::where("donation_type", 1)->sum("donation_amount")){
                        Subvention::create($request->except('_token' , "sub_type" , "moneyType"));
                        LockerLog::create([
                            "moneyType" => LockerLog::moneyTypeSadaka,
                            "amount" => $request->price,
                            "type" => LockerLog::TYPE_MINUS,
                            "admin_id" => auth()->id(),
                            "comment" => "  صدقه جديده الي" . ($user ? $user->husband_name : "مجهول") .
                                " ورقم هاتفه " . ($user ? $user->nearest_phone : "غير متوفر"),
                        ]);
                    }else{
                        return response()->json(["status"=>500 , "message" => "لا توجد سيوله لهذه الاعانه"]);
                    }
                }
            }else{
                Subvention::create($request->except('_token' , "sub_type" , "moneyType"));
                $totalAssets = Asset::where("id" , $request->asset_id)->first();
                if($totalAssets->counter >= $request->asset_count) {
                    LockerLog::create([
                        "moneyType" => LockerLog::moneyTypeSubvention,
                        "asset_id" => $request->asset_id,
                        "asset_count" => $request->asset_count,
                        "type" => LockerLog::TYPE_MINUS,
                        "admin_id" => auth()->id(),
                        "comment" => "  اعانه جديده الي" . ($user ? $user->husband_name : "مجهول") .
                            " ورقم هاتفه " . ($user ? $user->nearest_phone : "غير متوفر"),
                    ]);
                    $asset = Asset::find($request->asset_id);
                    $asset->counter -= $request->asset_count;
                    $asset->save();
                }else{
                    return response()->json(["status"=>500 ,  "message" => "لا توجد سيوله لهذه الاعانه"]);
                }
            }


            return response()->json(['status' => 200]);
        }catch (\Exception $e){
            return response()->json(['status' => 500]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit(Subvention $subvention)
    {
        $users = User::where('status','accepted')
            ->whereDoesntHave('subvention')
            ->orWhere('id',$subvention->user_id)
            ->select('id','husband_name')
            ->latest()->get();
        $assets = Asset::all();
        return view('Admin/subventions/parts/edit',compact('users','subvention' , "assets"));
    }


    public function update(Request $request, $id)
    {

        try{
            $user = User::find($request->user_id);
            if ($request->sub_type == 0){
                if ($request->moneyType == 0){
                    if ($request->price <= $totalDonation = Donation::where("donation_type", 0)->sum("donation_amount")){
                        $subvention = Subvention::find($id);
                        Subvention::find($id)->update($request->except('_token' , "sub_type" , "moneyType"));
                        $suv = Subvention::find($id);

                        $lockerLogs = LockerLog::where("amount" , $subvention->price)->where("created_at" , $subvention->created_at)->where("type" , LockerLog::TYPE_MINUS);
                        $lockerLogs->update([
                            "amount" => $suv->price,
                            "asset_id" =>$suv->asset_id,
                            "asset_count" =>$suv->asset_count,
                            "admin_id" => auth()->id(),
                        ]);
                    }else{
                        return response()->json(["status"=>500 , "message" => "لا توجد سيوله لهذه الاعانه"]);
                    }
                }else{
                    if ($request->price <= $totalDonation = Donation::where("donation_type", 1)->sum("donation_amount")){
                            $subvention = Subvention::find($id);
                            Subvention::find($id)->update($request->except('_token' , "sub_type" , "moneyType"));
                            $suv = Subvention::find($id);

                            $lockerLogs = LockerLog::where("amount" , $subvention->price)->where("created_at" , $subvention->created_at)->where("type" , LockerLog::TYPE_MINUS);
                            $lockerLogs->update([
                                "amount" => $suv->price,
                                "asset_id" =>$suv->asset_id,
                                "asset_count" =>$suv->asset_count,
                                "admin_id" => auth()->id(),
                            ]);
                    }else{
                        return response()->json(["status"=>500 , "message" => "لا توجد سيوله لهذه الاعانه"]);
                    }
                }
            }else{
                Subvention::create($request->except('_token' , "sub_type" , "moneyType"));
                $totalAssets = Asset::where("id" , $request->asset_id)->first();
                if($totalAssets->counter >= $request->asset_count) {

                    $subvention = Subvention::find($id);
                    Subvention::find($id)->update($request->except('_token' , "sub_type" , "moneyType"));
                    $suv = Subvention::find($id);

                    $lockerLogs = LockerLog::where("amount" , $subvention->price)->where("created_at" , $subvention->created_at)->where("type" , LockerLog::TYPE_MINUS);
                    $lockerLogs->update([
                        "amount" => $suv->price,
                        "asset_id" =>$suv->asset_id,
                        "asset_count" =>$suv->asset_count,
                        "admin_id" => auth()->id(),
                    ]);

                    $asset = Asset::find($request->asset_id);
                    $subvention = Subvention::find($id);
                    if (!$asset || !$subvention) {
                        abort(404, "البيانات غير موجودة");
                    }
                    $newCounter = $asset->counter + $subvention->asset_count;
                    if ($request->asset_count < $newCounter) {
                        $asset->counter = $newCounter - $request->asset_count;
                        $asset->save();
                    } else {
                        toastr()->error("غير كافي");
                        abort(405, "غير كافي");
                    }
                }else{
                    return response()->json(["status"=>500 ,  "message" => "لا توجد سيوله لهذه الاعانه"]);
                }
            }


            return response()->json(['status' => 200]);
        }catch (\Exception $e){
            return response()->json(['status' => 500]);
        }
//
//        \
//
//
//        $subvention = Subvention::find($id);
//        if ($subvention->price == 0 || $subvention->price == null){
//            $asset = Asset::find($request->asset_id);
//            $subvention = Subvention::find($id);
//            if (!$asset || !$subvention) {
//                abort(404, "البيانات غير موجودة");
//            }
//            $newCounter = $asset->counter + $subvention->asset_count;
//            if ($request->asset_count < $newCounter) {
//                $asset->counter = $newCounter - $request->asset_count;
//                $asset->save();
//            } else {
//                toastr()->error("غير كافي");
//                abort(405, "غير كافي");
//            }
//        }
//        if(
//        ){
//
//            Subvention::find($id)->update($request->except('_token' , "sub_type" , "moneyType"))
//            $suv = Subvention::find($id);
//
//            $lockerLogs = LockerLog::where("amount" , $subvention->price)->where("created_at" , $subvention->created_at)->where("type" , LockerLog::TYPE_MINUS);
//            $lockerLogs->update([
//                "amount" => $suv->price,
//                "asset_id" =>$suv->asset_id,
//                "asset_count" =>$suv->asset_count,
//                "admin_id" => auth()->id(),
//            ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        try {

            $subvention = Subvention::where('id',$request->id)->first();
            $lockerLogs = LockerLog::where("amount" , $subvention->price)->where("created_at" , $subvention->created_at)->where("type" , LockerLog::TYPE_MINUS);
            $lockerLogs->delete();
            $asset = Asset::find($subvention->asset_id);
            $asset->counter += Subvention::where('id',$request->id)->first()->asset_count;
            $asset->save();
            Subvention::destroy($request->id);
            return redirect()->back();
//            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }
    }

    public function showSubventions(){
        $subventions = Subvention::where('type','monthly')->latest()->get();
        return view('Admin.print.subvention-print',compact('subventions'));
    }
    public function showOneSubvention(){
        $subventions = Subvention::where('type','once')->latest()->get();
        return view('Admin.print.subvention-print',compact('subventions'));
    }
}
