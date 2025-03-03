<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BorrowerRequest;
use App\Models\Borrower;
use App\Http\Controllers\Controller;
use App\Models\Guarantor;
use App\Models\Media;
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
                    <button class="btn btn-pill view-guarantors btn-success-light" data-id="'.$borrower->id.'">  <i class="fa fa-eye"></i> </button>
                ';
                })
                ->rawColumns(['action'])
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
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(BorrowerRequest $request)
    {
      try{
          // Create Borrower
          $borrower = Borrower::create([
              'name' => $request->name,
              'phone' => $request->phone,
              'nationalID' => $request->nationalID,
              'address' => $request->address,
              'job' => $request->job,
          ]);

//          dd($borrower);

          if ($borrower){

              $borrower_id = $borrower->id;

              // Create Guarantors for the Borrower
              if ($request->has('guarantors')) {
                  foreach ($request->guarantors as $guarantor) {
                      $borrower->guarantors()->create($guarantor);
                  }
              }


              if ($request->hasFile('borrowerMedia')) {
                  foreach ($request->file('borrowerMedia') as $borrowerMedia) {
                      if ($borrowerMedia->isValid()) {
                          $file_name = time() . "_"  . $borrowerMedia->getClientOriginalName();
                          $storagePath = 'BorrowerUploads/Borrower';
                          $borrowerMedia->move(public_path($storagePath), $file_name);
                          Media::create([
                              'name' => $file_name,
                              'path' => $storagePath . '/' . $file_name,
                              'type' => 0, // 0 = Borrower Media
                              'borrower_id' => $borrower_id,
                              'guarantor_id' => null
                          ]);
                      }
                  }


              }



              if ($request->hasFile('guarantorMedia')) {
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


          }

          return response()->json([
              "status"=>200,
              "success"=>"added successfully",
          ]);
      }catch (\Exception $e){
          return response()->json([
              "status"=>405,
              "errors"=>"faild ". $e->getMessage(),
          ]);
      }



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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BorrowerRequest $request, Borrower $borrower)
    {

        try {
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

            $borrower_id = $borrower->id;

            // تحديث ملفات المقترض
            if ($request->hasFile('borrowerMedia')) {
                // حذف الملفات القديمة فقط إذا تم رفع ملفات جديدة
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
