<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonate;
use App\Models\Center;
use App\Models\Donation;
use App\Models\DonationType;
use App\Models\Donor;
use App\Models\Governorate;
use App\Models\LockerLog;
use App\Models\Village;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $donors = Donor::latest()
                ->with([
                    'donation',
                    'preferredDonationTypes',
                    'governorate',
                    'center',
                    'village',
                ])->get();

            return DataTables::of($donors)
                ->addColumn('action', function ($donors) {
                    $editButton = '
<div class="d-flex justify-content-center align-items-center gap-2">
    <button type="button" data-id="' . $donors->id . '"
        class="btn btn-pill btn-info-light editBtn"
        title="تعديل">
        <i class="fa fa-edit"></i>
    </button>

    <a href="' . route('donorDetails', $donors->id) . '"
       class="btn btn-pill btn-success-light"
       title="عرض التفاصيل">
        <i class="fas fa-eye"></i>
    </a>
</div>
';

                    return '<div class="d-flex">' . $editButton . '</div>';
                })
                ->addColumn('phone_display', function ($donors) {
                    return collect([$donors->phone, $donors->phone_second, $donors->relative_phone])->filter()->implode(' / ') ?: 'غير متوفر';
                })
                ->addColumn('location_display', function ($donors) {
                    return $donors->full_address ?: 'غير متوفر';
                })
                ->addColumn('preferred_types', function ($donors) {
                    return $donors->preferred_donation_types_text ?: 'غير محدد';
                })
                ->addColumn('first_donation', function ($donors) {
                    return optional($donors->first_donation_date)->format('d-m-Y') ?: '--';
                })
                ->addColumn('last_donation', function ($donors) {
                    return optional($donors->last_donation_date)->format('d-m-Y') ?: '--';
                })
                ->addColumn('donations_count', function ($donors) {
                    return $donors->donations_count;
                })
                ->addColumn('total_donations_amount', function ($donors) {
                    return $donors->total_donations_amount;
                })
                ->editColumn('notes', function ($donors) {
                    return '<span class="small-text-hover">' . ($donors->notes ?? '-----') . '</span>';
                })
                ->editColumn('burn_date', function ($donors) {
                    return $donors->burn_date ? \Carbon\Carbon::parse($donors->burn_date)->format('d-m-Y') : '--';
                })
                ->editColumn('created_at', function ($donors) {
                    return $donors->created_at ? $donors->created_at->format('d-m-y') : '--';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin/donors/index');
    }

    public function returnDonationMoney(Request $request)
    {
        try {
            $donor = Donor::with('donation')->find($request->donor_id);
            $totalLoansDonations = $donor->donation
                ->where('donation_type', 2)
                ->sum(function ($donation) {
                    return (float) ($donation->amount_value ?? $donation->donation_amount ?? 0);
                });
            $returnAmount = $request->DonationReturnAmount;

            if ($returnAmount > $totalLoansDonations) {
                return response()->json([
                    'success' => false,
                    'message' => 'القيمه المسترده اكبر من القيمه المتبرع بها',
                ]);
            }

            $totalLoanAmount = LockerLog::where('moneyType', LockerLog::moneyTypeLoans)->where('type', LockerLog::TYPE_PLUS)->sum('amount')
                - LockerLog::where('moneyType', LockerLog::moneyTypeLoans)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($returnAmount > $totalLoanAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد اموال كافيه في خزنة القروض',
                ]);
            }

            $donations = Donation::where('donation_type', 2)
                ->where('donor_id', $request->donor_id)
                ->get();

            foreach ($donations as $donation) {
                if ($returnAmount <= 0) {
                    break;
                }

                if ($donation->donation_amount <= $returnAmount) {
                    $returnAmount -= $donation->donation_amount;
                    $donation->donation_amount = 0;
                } else {
                    $donation->donation_amount -= $returnAmount;
                    $returnAmount = 0;
                }

                $donation->save();
            }

            LockerLog::create([
                'moneyType' => LockerLog::moneyTypeLoans,
                'amount' => $request->DonationReturnAmount,
                'type' => LockerLog::TYPE_MINUS,
                'admin_id' => auth()->id(),
                'donation_id' => null,
                'subvention_id' => null,
                'donor_id' => $donor->id,
                'loan_id' => null,
                'comment' => 'تم استرداد أموال المتبرع ' . $donor->name . ' في يوم ' . \Carbon\Carbon::now()->format('Y-m-d'),
            ]);

            Donor::logHistory(
                $donor->id,
                'refund',
                'استرداد مبلغ للمتبرع',
                'تم استرداد مبلغ ' . $request->DonationReturnAmount . ' من رصيد تبرعات القرض الحسن.'
            );

            return response()->json(['status' => 'success', 'message' => 'Amount returned successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function create()
    {
        return view('admin/donors/parts/create', $this->formData());
    }

    public function store(StoreDonate $request)
    {
        $data = $request->validated();
        unset($data['preferred_donation_types']);
        $data['address'] = $data['address'] ?? $data['detailed_address'] ?? null;

        $donor = Donor::create($data);
        $donor->preferredDonationTypes()->sync($request->input('preferred_donation_types', []));

        Donor::logHistory($donor->id, 'created', 'إضافة متبرع جديد', 'تم تسجيل بيانات المتبرع في النظام.');

        return response()->json(['status' => 200]);
    }

    public function show($id)
    {
    }

    public function edit(Donor $donor)
    {
        return view('admin/donors/parts/edit', array_merge(
            ['donor' => $donor->load('preferredDonationTypes')],
            $this->formData()
        ));
    }

    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|digits_between:7,15|unique:donors,phone,' . $donor->id,
            'phone_second' => 'nullable|digits_between:7,15',
            'relative_phone' => 'nullable|digits_between:7,15',
            'address' => 'nullable|string',
            'detailed_address' => 'nullable|string',
            'burn_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'created_at' => 'nullable|date',
            'governorate_id' => 'nullable|exists:governorates,id',
            'center_id' => 'nullable|exists:centers,id',
            'village_id' => 'nullable|exists:villages,id',
            'preferred_donation_types' => 'nullable|array',
            'preferred_donation_types.*' => 'exists:donation_types,id',
        ]);

        unset($validated['preferred_donation_types']);
        $validated['address'] = $validated['address'] ?? $validated['detailed_address'] ?? null;

        $donor->update($validated);
        $donor->preferredDonationTypes()->sync($request->input('preferred_donation_types', []));

        Donor::logHistory($donor->id, 'updated', 'تحديث بيانات المتبرع', 'تم تعديل بيانات المتبرع.');

        if ($request->ajax()) {
            return response()->json(['status' => 200]);
        }

        toastr()->success('تم التحديث بنجاح');
        return redirect()->route('donors.index');
    }

    public function destroy($id)
    {
    }

    public function delete(Request $request)
    {
        try {
            Donor::destroy($request->id);
            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }

    public function CartIndex()
    {
        return view('charts.dashboard');
    }

    public function getChartData()
    {
        $data = Donor::selectRaw('COUNT(id) as count, DATE(created_at) as date')
            ->whereNotNull('created_at')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function donorDetails($id)
    {
        $donor = Donor::with([
            'preferredDonationTypes',
            'governorate',
            'center',
            'village',
            'donation.referenceDonationType',
            'donation.unit',
            'histories.admin',
        ])->findOrFail($id);

        $donations = Donation::with(['referenceDonationType', 'unit'])
            ->where('donor_id', $id)
            ->latest()
            ->get();

        $cashDonations = $donations->filter(function ($donation) {
            $unit = $donation->unit;
            $type = $donation->referenceDonationType;

            return $unit
                && ($unit->code === 'egp' || $unit->name === 'جنيه')
                && $type
                && $type->isCashLockerType();
        })->values();

        $nonCashDonations = $donations->filter(function ($donation) {
            $unit = $donation->unit;
            $type = $donation->referenceDonationType;

            return !$unit
                || ($unit->code !== 'egp' && $unit->name !== 'جنيه')
                || !$type
                || ! $type->isCashLockerType();
        })->values();

        $cashDonationsTotal = $cashDonations->sum(function ($donation) {
            return (float) ($donation->amount_value ?? $donation->donation_amount ?? 0);
        });

        return view('admin/donors/parts/details', compact('donor', 'cashDonations', 'nonCashDonations', 'cashDonationsTotal'));
    }

    protected function formData(): array
    {
        return [
            'governorates' => Governorate::active()->orderBy('sort_order')->get(),
            'centers' => Center::active()->orderBy('sort_order')->get(),
            'villages' => Village::active()->orderBy('sort_order')->get(),
            'donationTypes' => DonationType::active()->orderBy('sort_order')->get(),
        ];
    }
}
