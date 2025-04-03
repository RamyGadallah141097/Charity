<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetsRequest;
use App\Models\Asset;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assets = Asset::query(); // Keeping DataTables intact

            return DataTables::of($assets)
                ->addColumn('action', function ($asset) {
                    $editButton = '';
                    $deleteButton = '';

                        $editButton = '
                            <button type="button" data-id="' . $asset->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';

                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $asset->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin/asset/index');
    }

    /**
     * Get action buttons for DataTables.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin/asset/parts/create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AssetsRequest $request)
    {
        try {
            Asset::create($request->validated());
            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['status' => 405, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('admin/asset/parts/edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AssetsRequest $request, Asset $asset)
    {
        try {
            $asset->update($request->validated());
            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['status' => 405, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $asset = Asset::findOrFail($request->id);

        try {
            $asset->delete();
            toastr()->success("تم الحذف بنجاح.");
        } catch (\Exception $e) {
            toastr()->error("فشل في الحذف.");
        }

        return redirect()->route("assets.index");
    }
}
