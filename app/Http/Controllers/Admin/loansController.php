<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class loansController extends Controller
{
    public function indexLoans()
    {

        return view('admin.loans.index');
    }



    public function createLoans()
    {

        return view('admin.loans.parts.create');
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
