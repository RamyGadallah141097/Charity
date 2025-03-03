<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BorrowerRequest;
use App\Models\Borrower;
use App\Http\Controllers\Controller;
use App\Models\Guarantor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $borrowers = Borrower::select(['id', 'name', 'phone', 'nationalID', 'address', 'job']);

            return DataTables::of($borrowers)
                ->addColumn('action', function ($borrower) {
                    return '
                    <button type="button" data-id="' . $borrower->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>
                     <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                data-id="' . $borrower->id . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    <button class="btn btn-primary view-guarantors" data-id="'.$borrower->id.'">  <i class="fa fa-eye"></i> </button>
                ';
                })
                ->rawColumns(['action']) // ✅ Ensure action column renders HTML
                ->make(true);
        }

        return view('Admin.borrowers.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Admin\borrowers\parts\create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(BorrowerRequest $request)
    {
        // Create Borrower
        $borrower = Borrower::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'nationalID' => $request->nationalID,
            'address' => $request->address,
            'job' => $request->job,
        ]);


        // Create Guarantors for the Borrower
        if ($request->has('guarantors')) {
            foreach ($request->guarantors as $guarantor) {
                $borrower->guarantors()->create($guarantor);
            }
        }

        return redirect()->back();
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function show(Borrower $borrower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function edit(Borrower $borrower)
    {
        return view('Admin\borrowers\parts\edit', compact('borrower'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function update(BorrowerRequest $request, Borrower $borrower)
    {


        // تحديث بيانات المقترض
        $borrower->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'nationalID' => $request->nationalID,
            'address' => $request->address,
            'job' => $request->job,
        ]);

        // تحديث أو إضافة الضامنين
        if ($request->has('guarantors')) {
            foreach ($request->guarantors as $guarantorData) {
                if (isset($guarantorData['id'])) {
                    // تحديث الضامن الحالي
                    $guarantor = $borrower->guarantors()->find($guarantorData['id']);
                    if ($guarantor) {
                        $guarantor->update([
                            'name' => $guarantorData['name'],
                            'phone' => $guarantorData['phone'],
                            'nationalID' => $guarantorData['nationalID'],
                            'address' => $guarantorData['address'],
                            'job' => $guarantorData['job'],
                        ]);
                    }
                } else {
                    // إنشاء ضامن جديد
                    $borrower->guarantors()->create($guarantorData);
                }
            }
        }

        // حذف الضامنين الذين تم إرسالهم للحذف
        if ($request->has('remove_guarantors')) {
            $borrower->guarantors()->whereIn('id', $request->remove_guarantors)->delete();
        }

        return redirect()->route('borrowers.index')->with('success', 'تم تحديث المقترض بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\Response
     */
    public function delete(Request  $request)
    {
        try {
            $borrower = Borrower::find($request->id);
            $borrower->delete();
            toastr()->success("deleted successfully");
            return redirect()->back();
        }catch (\Exception $e){
            toastr()->error("faild" . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getGuarantor(Request $request)
    {
        $guarantors = Guarantor::where('borrower_id', $request->borrower_id)->get();
        return response()->json($guarantors);

    }

}
