<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BorrowerRequest;
use App\Models\Borrower;
use App\Http\Controllers\Controller;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\LockerLog;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
            $borrowers = Borrower::select(['id', 'name', 'phone', 'nationalID', 'address', 'job', "review", "borrower_age", "rate", "review"]);


            return DataTables::of(source: $borrowers)
                ->addColumn('action', function ($borrower) {
                    $editButton = '';
                    $deleteButton = '';
                    $viewGuarantorsButton = '';
                    $viewMediaButton = '';

                    $editButton = '
                            <button type="button" data-id="' . $borrower->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                              <a href="' . route('borrowerDetails', $borrower->id) . '"
                            class="btn btn-pill btn-success-light"
                            title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                        ';

                    // التحقق من إذن الحذف
                    // $deleteButton = '
                    //     <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                    //             data-id="' . $borrower->id . '">
                    //         <i class="fas fa-trash"></i>
                    //     </button>
                    // ';

                    // $viewGuarantorsButton = '
                    //     <button class="btn btn-pill view-guarantors btn-success-light" data-id="' . $borrower->id . '">
                    //         <i class="fa fa-eye"></i>
                    //     </button>
                    // ';


                    // $viewMediaButton = '
                    //     <button class="btn btn-pill btn-primary-light viewMedia" data-id="' . $borrower->id . '">
                    //         <i class="fa fa-photo-video"></i>
                    //     </button>
                    // ';

                    $borrowerReview = '
                            <button class="btn btn-pill btn-primary-light borrowerReview" data-id="' . $borrower->id . '"  data-review="' . $borrower->review . '">
                                <i class="fa fa-star"></i>

                            </button>
                        ';

                    return '<div class="d-flex">' . $editButton . $deleteButton . $viewGuarantorsButton . $viewMediaButton . $borrowerReview . '</div>';
                })

                ->editColumn('borrower_age', function ($borrower) {
                    return $borrower->borrower_age ? $borrower->borrower_age : "--";
                })
                ->editColumn('rate', function ($borrower) {
                    return $borrower->rate
                        ? $borrower->rate . ' <i class="fa fa-star" style="color: gold;"></i>'
                        : '-';
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin/borrowers/index');
    }



    public function create()
    {
        return view('admin/borrowers/parts/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */



    public function store(Request $request)
    {
        //        dd($request->all());
        try {
            // Create Borrower
            $borrower = Borrower::create([
                'name' => $request->name,
                'borrower_age' => $request->borrower_age,
                'phone' => $request->phone,
                'nationalID' => $request->nationalID,
                'address' => $request->address,
                'job' => $request->job,
            ]);

            if ($borrower) {
                $borrower_id = $borrower->id;

                // Create Guarantors with Age
                if ($request->has('guarantors')) {
                    foreach ($request->guarantors as $index => $guarantor) {
                        $guarantor['age'] = $request->guarantorAge[$index]['guarantorAge'] ?? null;

                        $borrower->guarantors()->create([
                            'name' => $guarantor['name'],
                            'phone' => $guarantor['phone'],
                            'nationalID' => $guarantor['nationalID'],
                            'address' => $guarantor['address'],
                            'job' => $guarantor['job'],
                            'guarantorAge' => $guarantor['guarantorAge'],
                        ]);
                    }
                }

                // Handle Borrower Media
                if ($request->hasFile('borrowerMedia')) {
                    foreach ($request->file('borrowerMedia') as $borrowerMedia) {
                        if ($borrowerMedia->isValid()) {
                            $file_name = time() . "_" . $borrowerMedia->getClientOriginalName();
                            $storagePath = 'BorrowerUploads/Borrower';
                            $borrowerMedia->move(public_path($storagePath), $file_name);

                            Media::create([
                                'name' => $file_name,
                                'path' => $storagePath . '/' . $file_name,
                                'type' => 0, // Borrower Media
                                'borrower_id' => $borrower_id,
                                'guarantor_id' => null
                            ]);
                        }
                    }
                }

                // Handle Guarantor Media
                if ($request->hasFile('guarantorMedia')) {
                    foreach ($request->file('guarantorMedia') as $guarantorMedia) {
                        if ($guarantorMedia->isValid()) {
                            $file_name = time() . "_" . $guarantorMedia->getClientOriginalName();
                            $file_path = 'GuarantorUploads/Guarantor';
                            $guarantorMedia->move(public_path($file_path), $file_name);

                            Media::create([
                                'name' => $file_name,
                                'path' => $file_path . '/' . $file_name,
                                'type' => 1, // Guarantor Media
                                'borrower_id' => $borrower_id,
                                'guarantor_id' => null
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'status' => 200,
                'success' => 'Added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 405,
                'errors' => 'Failed: ' . $e->getMessage(),
            ]);
        }
    }


    public function show(Borrower $borrower)
    {
        //
    }


    public function edit(Borrower $borrower)
    {
        $media1 = Media::where("borrower_id", $borrower->id)->where("type", null);
        $media2 = Media::where("borrower_id", $borrower->id)->where("type", 1);
        return view('admin/borrowers/parts/edit', compact('borrower', "media1", "media2"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Borrower  $borrower
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BorrowerRequest $request, Borrower $borrower)
    {

        try {
            // تحديث بيانات المقترض
            $borrower->update([
                'name' => $request->name,
                "borrower_age" => $request->borrower_age,
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
                                'guarantorAge' => $guarantorData['guarantorAge'],
                                'job' => $guarantorData['job'],
                            ]);
                        }
                    } else {
                        // إنشاء ضامن جديد
                        $borrower->guarantors()->create($guarantorData);
                    }
                }
            }

            $borrower_id = $borrower->id;

            // تحديث ملفات المقترض
            if ($request->hasFile('borrowerMedia')) {
                // حذف الملفات القديمة فقط إذا تم رفع ملفات جديدة
                if ($borrower->media()->where('type',  0)->count() > 0) {
                    foreach ($borrower->media()->where('type', 0)->get() as $media) {
                        if (File::exists(public_path($media->path))) {
                            File::delete(public_path($media->path));
                        }
                    }
                }
                $borrower->media()->where('type', 0)->delete();

                foreach ($request->file('borrowerMedia') as $borrowerMedia) {
                    if ($borrowerMedia->isValid()) {
                        $file_name = time() . "_" . $borrowerMedia->getClientOriginalName();
                        $storagePath = 'BorrowerUploads/Borrower';
                        $borrowerMedia->move(public_path($storagePath), $file_name);

                        Media::create([
                            'name' => $file_name,
                            'path' => $storagePath . '/' . $file_name,
                            'type' => 0,
                            'borrower_id' => $borrower_id,
                            'guarantor_id' => null
                        ]);
                    }
                }
            }

            // تحديث ملفات الضامن
            if ($request->hasFile('guarantorMedia')) {
                // حذف الملفات القديمة فقط إذا تم رفع ملفات جديدة
                if ($borrower->media()->where("type", 1)->count() > 0) {
                    foreach ($borrower->media()->where('type', 1)->get() as $media) {
                        if (FILE::exists(public_path($media->paht))) {
                            File::delete(public_path($media->path));
                        }
                    }
                }
                $borrower->media()->where('type', 1)->delete();

                foreach ($request->file('guarantorMedia') as $guarantorMedia) {
                    if ($guarantorMedia->isValid()) {
                        $file_name = time() . "_" . $guarantorMedia->getClientOriginalName();
                        $file_path = "GuarantorUploads/Guarantor";
                        $guarantorMedia->move(public_path($file_path), $file_name);

                        Media::create([
                            "name" => $file_name,
                            "path" => $file_path . "/" . $file_name,
                            "type" => 1,
                            "borrower_id" => $borrower_id,
                            "guarantor_id" => null
                        ]);
                    }
                }
            }

            // حذف الضامنين المحددين للحذف
            if ($request->has('remove_guarantors') && count($request->remove_guarantors) > 0) {
                $borrower->guarantors()->whereIn('id', $request->remove_guarantors)->delete();
            }

            return response()->json([
                "status" => 200,
                "success" => "تم التحديث بنجاح",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 405,
                "errors" => "فشل التحديث: " . $e->getMessage(),
            ]);
        }
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

            //            if ($borrower->media()->count() > 0){
            //                foreach ($borrower->media()->get() as $media){
            //                    if (File::exists(public_path($media->path))){
            //                        File::delete(public_path($media->path));
            //                    }
            //                }

            //            }
            //            $borrower = Borrower::with('media')->findOrFail($request->id);
            //            $loan = Loan::where('borrower_id', $request->id)->first();
            //
            //            if ($loan) {
            //                LockerLog::where("amount", $loan->loan_amount)
            //                    ->whereDate("created_at", $loan->created_at) // Ensures date-only comparison
            //                    ->where("type", LockerLog::TYPE_MINUS)
            //                    ->where("moneyType", LockerLog::moneyTypeLoans)
            //                    ->delete();
            //            }
            $borrower = Borrower::with('media')->findOrFail($request->id);
            $loan = Loan::where('borrower_id', $request->id)->first();

            if ($loan) {
                //                        LockerLog::where("amount", $loan->loan_amount)
                //                        ->where("created_at", $loan->created_at) // Matches exact timestamp
                //                        ->where("type", LockerLog::TYPE_MINUS)
                //                        ->where("moneyType", LockerLog::moneyTypeLoans)
                //                        ->delete();
                LockerLog::where("loan_id", $loan->id)->delete();
            }


            $borrower->delete();
            toastr()->success("deleted successfully");
            return redirect()->back();
        } catch (\Exception $e) {
            toastr()->error("faild" . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getGuarantor(Request $request)
    {
        $guarantors = Guarantor::where('borrower_id', $request->borrower_id)->get();

        return response()->json(['guarantors' => $guarantors,]);
    }

    public function borrowerDetails($id)
    {

        $borrower = Borrower::findOrFail($id);
        $loans = Loan::where('borrower_id', $id)->get();
        $guarantors = Guarantor::where('borrower_id', $id)->get();
        return view('admin/borrowers/parts/details', compact('borrower', 'loans', 'guarantors'));
    }

    public function getMedia($id)
    {
        $borrower = Borrower::with('media')->findOrFail($id);
        return response()->json(['media' => $borrower->media]);
    }

    public function storeReview(Request $request)
    {
        try {
            $borrower = Borrower::find($request->borrower_id);
            $borrower->review = $request->review;
            $borrower->rate = $request->rating;
            $borrower->save();
            return redirect()->back()->with("تم رفع التقييم");
        } catch (\Exception $e) {
            return redirect()->back()->with("مشكله في التقييم ");
        }
    }
}
