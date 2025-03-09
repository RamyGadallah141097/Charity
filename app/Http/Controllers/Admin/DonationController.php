<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationsRequest;
use App\Http\Requests\StoreDonate;
use App\Models\Asset;
use App\Models\Donation;
use App\Models\Donor;
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

                    if (auth()->user()->can('Donations.edit')) {
                        $editButton = '
                            <button type="button" data-id="' . $donation->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('Donations.destroy')) {
                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $donation->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
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
            return view('Admin/donations/index' );
        }
    }


    public function create()
    {
        $donors = Donor::all();
        $assets = Asset::all();

        return view('Admin/donations/parts/create', ["donors" => $donors ,  "assets" => $assets]);
    }


    public function store(DonationsRequest $request)
    {
        try {

            $asset = Asset::find($request->asset_id);
            $asset->counter += $request->asset_count;
            $asset->save();
            Donation::create($request->except('_token'));

            return response()->json(['status' => 200]);

        }catch (\Exception $e){
            return response()->json(["status" => 500]);
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $donation = Donation::find($id);
        return view('Admin/donations/parts/edit', ["donation" => $donation, "donors" => Donor::all()]);
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

    public function lock(Request $request, $model = null)
    {
        if ($request->ajax()) {
            $donations = Donation::where("donation_type", $model)->get();
            return Datatables::of($donations)
                ->addColumn('donor_name', function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->editColumn('price', function ($donation) {
                    if ($donation->donation_type == 3 ){
                        $asset = Asset::where("id", $donation->asset_id)->first();
                        $asset ? $donation->donation_amount = $asset->name . " : " .  $asset->counter : "-";
                    }else{
                        $donation->donation_amount = $donation->donation_amount;
                    }
                    return $donation->donation_amount;

                })
                ->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-y') : 'غير متوفر';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $title = $model ==  0  ?  "زكاة" :  ($model ==  1 ? "صدقات"  : ($model == 2 ? "قرض حسن " : "عينيات") );
            return view('Admin/lock/stdPage', compact('model' , "title"));
        }
    }


}
