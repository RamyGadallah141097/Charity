<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationsRequest;
use App\Http\Requests\StoreDonate;
use App\Models\Asset;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\LockerLog;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DonationController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::whereIn("donation_type", [0, 1, 2, 3])->get();


            return Datatables::of($donations)
                ->addColumn('donor_name', function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->addColumn('donor_phone', function ($donation) {
                    return $donation->donor->phone ?? 'غير متوفر';
                })->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-y') : 'غير متوفر';
                })
                ->editColumn('donation_type', function ($donation) {
                    switch ($donation->donation_type) {
                        case 0:
                            return 'زكاة المال';
                            break;
                        case 1:
                            return 'صدقات';
                            break;
                        case 2:
                            return 'قرض حسن';
                            break;
                        default:
                            return 'تبرع عيني';
                    }
                })
                ->addColumn('action', function ($donation) {
                    $editButton = '';
                    $deleteButton = '';

                        // $editButton = '
                        //     <button type="button" data-id="' . $donation->id . '" class="btn btn-pill btn-info-light editBtn">
                        //         <i class="fa fa-edit"></i>
                        //     </button>
                        // ';

                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $donation->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';

                    return '<div class="d-flex">'  . $deleteButton . '</div>';
                })
                ->editColumn('donation_amount', function ($donation) {
                    if ($donation->donation_type == 3 ){
                        $asset = Asset::where("id", $donation->asset_id)->first();
                         $asset ? $donation->donation_amount = $asset->name . " : " .  $asset->counter : "-";
                    }else{
                        $donation->donation_amount = $donation->donation_amount;
                    }
                    return $donation->donation_amount;
                })


                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/donations/index' );
        }
    }


    public function create()
    {
        $donors = Donor::all();
        $assets = Asset::all();

        return view('admin/donations/parts/create', ["donors" => $donors ,  "assets" => $assets]);
    }


    public function store(DonationsRequest $request)
    {

        try {
            if ($request->donation_type != 3){
                $donor = Donor::find($request->donor_id);
                LockerLog::create([
                    "moneyType" => $request->donation_type == 0 ? LockerLog::moneyTypeZakat :
                        ($request->donation_type == 1 ? LockerLog::moneyTypeSadaka :
                            ($request->donation_type == 2 ? LockerLog::moneyTypeLoans : "subvention")),
                    "amount" => $request->donation_amount,
//                    "asset_id" =>0,
//                    "asset_count" =>0,
                    "type" => LockerLog::TYPE_PLUS,
                    "admin_id" => auth()->id(),
                    "comment" => "تبرع جديد من " . ($donor ? $donor->name : "مجهول") .
                        " ورقم هاتفه " . ($donor ? $donor->phone : "غير متوفر"),
                ]);
            }else{
                $donor = Donor::find($request->donor_id);
                LockerLog::create([
                    "moneyType" => $request->donation_type == 0 ? LockerLog::moneyTypeZakat :
                        ($request->donation_type == 1 ? LockerLog::moneyTypeSadaka :
                            ($request->donation_type == 2 ? LockerLog::moneyTypeLoans : "subvention")),
                    "amount" =>0,
                    "asset_id" =>$request->asset_id,
                    "asset_count" =>$request->asset_count,
                    "type" => LockerLog::TYPE_PLUS,
                    "admin_id" => auth()->id(),
                    "comment" => "تبرع جديد من " . ($donor ? $donor->name : "مجهول") .
                        " ورقم هاتفه " . ($donor ? $donor->phone : "غير متوفر"),
                ]);
            }


            $asset = Asset::find($request->asset_id);
            if (isset($asset)) {
                $asset->counter += $request->asset_count;
                $asset->save();
            }
            Donation::create($request->except('_token'));

            return response()->json(['status' => 200]);

        }catch (\Exception $e){
            return response()->json(["status" => 500 , "message"=>$e->getMessage()]);
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $donation = Donation::find($id);
        return view('admin/donations/parts/edit', ["donation" => $donation, "donors" => Donor::all()]);
    }


    public function update(DonationsRequest $request, $id)
    {
        $donation = Donation::find($id);
        if ($donation->update($request->except('_token', '_method'))) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(["status" => 405]);
        }
    }


    public function destroy($id)
    {
        //
    }


    public function delete(Request $request)
    {
        try {
            Donation::destroy($request->id);
            return redirect()->back();
            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }


    public function get_donor_phone($id)
    {
        $donor_phone = Donor::where("id", $id)->pluck("phone");
        return response()->json(['donor_phone' => $donor_phone]);
    }


    public function searchDonor(Request $request)
    {
        try {
            $query = $request->input('donor_names');
            // Make sure the table exists and the column name is correct
            $donors = Donor::where('name', 'LIKE', "%{$query}%")->get();

            return response()->json($donors);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




}
