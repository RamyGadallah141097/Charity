<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DonationsRequest;
use App\Models\Admin;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\DonationUnit;
use App\Models\Donor;
use App\Models\LockerLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DonationController extends Controller
{
    protected ?DonationUnit $cashDonationUnit = null;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $category = $request->get('category', 'cash');
            $donationsQuery = Donation::with(['donor', 'referenceDonationType', 'donationCategory', 'unit', 'receivedBy'])->latest();

            if ($category === 'cash') {
                $donationsQuery
                    ->whereHas('unit', function ($query) {
                        $query->where('code', 'egp')->orWhere('name', 'جنيه');
                    })
                    ->whereHas('referenceDonationType', function ($query) {
                        $query->cashLockerTypes();
                    });
            } elseif ($category === 'non_cash') {
                $donationsQuery->where(function ($query) {
                    $query
                        ->whereDoesntHave('referenceDonationType', function ($typeQuery) {
                            $typeQuery->cashLockerTypes();
                        })
                        ->orWhereDoesntHave('unit', function ($unitQuery) {
                            $unitQuery->where('code', 'egp')->orWhere('name', 'جنيه');
                        });
                });
            }

            $donations = $donationsQuery->get();

            return Datatables::of($donations)
                ->addColumn('received_at_display', function ($donation) {
                    return optional($donation->received_at)->format('d-m-Y') ?: optional($donation->created_at)->format('d-m-Y') ?: 'غير متوفر';
                })
                ->addColumn('donor_name', function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->addColumn('donor_phone', function ($donation) {
                    return $donation->donor->phone ?? 'غير متوفر';
                })
                ->addColumn('donation_type_name', function ($donation) {
                    return optional($donation->referenceDonationType)->name ?? 'غير محدد';
                })
                ->addColumn('donation_category_name', function ($donation) {
                    return optional($donation->donationCategory)->name ?? '--';
                })
                ->addColumn('value_with_unit', function ($donation) {
                    $value = $donation->amount_value ?? $donation->donation_amount ?? $donation->asset_count ?? '-';
                    $unit = optional($donation->unit)->name;
                    return trim($value . ' ' . $unit);
                })
                ->addColumn('unit_name', function ($donation) {
                    return $donation->display_unit_name;
                })
                ->addColumn('receipt_number', function ($donation) {
                    return $donation->receipt_number ?: '--';
                })
                ->addColumn('received_by_name', function ($donation) {
                    return optional($donation->receivedBy)->name ?? 'غير محدد';
                })
                ->addColumn('donation_month_name', function ($donation) {
                    return $donation->month_name;
                })
                ->addColumn('occasion_name', function ($donation) {
                    return $donation->occasion ?: '--';
                })
                ->addColumn('action', function ($donation) {
                    $buttons = [];

                    if (auth()->guard('admin')->user()->can('Donations.edit')) {
                        $buttons[] = '
                            <button type="button" data-id="' . $donation->id . '" class="btn btn-sm btn-info-light editBtn" title="تعديل">
                                <i class="fe fe-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->guard('admin')->user()->can('donations_delete')) {
                        $buttons[] = '
                            <button class="btn btn-sm btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $donation->id . '" data-title="هذا التبرع" title="حذف">
                                <i class="fe fe-trash"></i>
                            </button>
                        ';
                    }

                    return implode('', $buttons);
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin/donations/index');
    }


    public function create()
    {
        return view('admin/donations/parts/create', $this->formData());
    }


    public function store(DonationsRequest $request)
    {
        try {
            $data = $request->validated();
            $data['donation_unit_id'] = $this->resolveDonationUnitId($data['donation_type_id'] ?? null, $data['donation_unit_id'] ?? null);
            $data['donation_month'] = $data['donation_month'] ?? \Carbon\Carbon::parse($data['received_at'])->month;
            $data['created_at'] = $data['created_at'] ?? $data['received_at'];
            $data['donation_amount'] = $data['donation_amount'] ?? $data['amount_value'] ?? null;
            $data['donation_type'] = $data['donation_type_id'];
            $data['donation_kind'] = $data['donation_kind'] ?? 'financial';

            $donor = Donor::find($data['donor_id']);
            $unit = DonationUnit::find($data['donation_unit_id']);
            $donationType = DonationType::find($data['donation_type_id']);
            $isCashUnit = $unit && ($unit->code === 'egp' || $unit->name === 'جنيه');
            $lockerMoneyType = $donationType?->lockerMoneyType();
            $isCashDonation = $isCashUnit && ! is_null($lockerMoneyType);

            if ($isCashDonation && !empty($data['amount_value'])) {
                LockerLog::create([
                    'moneyType' => $lockerMoneyType,
                    'amount' => $data['amount_value'],
                    'type' => LockerLog::TYPE_PLUS,
                    'admin_id' => auth()->id(),
                    'donor_id' => $donor?->id,
                    'comment' => 'تبرع وارد جديد من ' . ($donor ? $donor->name : 'مجهول')
                        . ' ورقم هاتفه ' . ($donor ? $donor->phone : 'غير متوفر'),
                ]);
            }

            $donation = Donation::create($data);

            if ($donation?->donor_id) {
                $donor = Donor::find($donation->donor_id);

                Donor::logHistory(
                    $donation->donor_id,
                    'donation',
                    'تسجيل تبرع جديد',
                    'تم تسجيل تبرع جديد للمتبرع ' . ($donor->name ?? ''),
                    [
                        'donation_id' => $donation->id,
                        'donation_type_id' => $donation->donation_type_id,
                        'amount' => $donation->amount_value,
                        'receipt_number' => $donation->receipt_number,
                    ],
                    $donation->received_at
                );
            }

            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            return response()->json(["status" => 500, "message" => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $donation = Donation::find($id);
        return view('admin/donations/parts/edit', array_merge(['donation' => $donation], $this->formData()));
    }


    public function update(DonationsRequest $request, $id)
    {
        $donation = Donation::find($id);
        $data = $request->validated();
        $data['donation_unit_id'] = $this->resolveDonationUnitId($data['donation_type_id'] ?? null, $data['donation_unit_id'] ?? null);
        $data['donation_month'] = $data['donation_month'] ?? \Carbon\Carbon::parse($data['received_at'])->month;
        $data['donation_amount'] = $data['donation_amount'] ?? $data['amount_value'] ?? null;
        $data['donation_type'] = $data['donation_type_id'];
        $data['donation_kind'] = $data['donation_kind'] ?? ($donation->donation_kind ?: 'financial');

        if ($donation->update($data)) {
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
    public function PrintDonations()
    {
        $Donations = Donation::with(['donor', 'referenceDonationType', 'donationCategory', 'unit', 'receivedBy'])->get();
        return view('admin/print/PrintDonations', compact('Donations'));
    }

    protected function formData(): array
    {
        $donationTypes = DonationType::active()->orderBy('sort_order')->get();
        $donationUnits = DonationUnit::active()
            ->with('categories:id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return [
            'donors' => Donor::orderBy('name')->get(),
            'donationCategories' => DonationCategory::active()->with('units:id,name')->orderBy('sort_order')->orderBy('name')->get(),
            'donationTypes' => $donationTypes,
            'donationUnits' => $donationUnits,
            'cashDonationUnitId' => optional($donationUnits->firstWhere('code', 'egp'))->id,
            'admins' => Admin::orderBy('name')->get(),
            'occasions' => ['رمضان', 'عيد', 'أضحية', 'كفارة', 'صدقة جارية', 'زكاة', 'موسمية', 'أخرى'],
        ];
    }

    protected function resolveDonationUnitId($donationTypeId, $donationUnitId): ?int
    {
        $donationType = $donationTypeId ? DonationType::find($donationTypeId) : null;

        if ($donationType && ! $donationType->requiresDonationUnitSelection()) {
            return $this->cashDonationUnit()?->id;
        }

        return $donationUnitId ? (int) $donationUnitId : null;
    }

    protected function cashDonationUnit(): ?DonationUnit
    {
        if ($this->cashDonationUnit) {
            return $this->cashDonationUnit;
        }

        $this->cashDonationUnit = DonationUnit::where('code', 'egp')->first();

        return $this->cashDonationUnit;
    }
}
