<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\DonationUnit;
use App\Models\Subvention;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class InKindDisbursementController extends Controller
{
    private const IN_KIND_CATEGORY_CODES = [
        'new_clothes',
        'used_clothes',
        'blankets',
        'meat',
        'new_furniture',
        'used_furniture',
        'electrical_appliances',
    ];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $disbursements = Subvention::with(['user', 'donationCategory', 'donationUnit'])
                ->where('price', 0)
                ->whereNotNull('donation_category_id')
                ->latest()
                ->get();

            return DataTables::of($disbursements)
                ->addColumn('beneficiary_name', function (Subvention $subvention) {
                    return optional($subvention->user)->wife_name ?: optional($subvention->user)->husband_name ?: 'تم حذفه';
                })
                ->addColumn('beneficiary_code', function (Subvention $subvention) {
                    return optional($subvention->user)->beneficiary_code ?: '-';
                })
                ->addColumn('category_name', function (Subvention $subvention) {
                    return optional($subvention->donationCategory)->name ?: '--';
                })
                ->addColumn('quantity', function (Subvention $subvention) {
                    return number_format((float) $subvention->asset_count, 0) . ' ' . (optional($subvention->donationUnit)->name ?: '');
                })
                ->editColumn('created_at', function (Subvention $subvention) {
                    return $subvention->created_at ? $subvention->created_at->format('d/m/Y') : '-';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin/in-kind-disbursements/index');
    }

    public function create()
    {
        return view('admin/in-kind-disbursements/create', [
            'users' => User::where('status', 'accepted')
                ->select('id', 'beneficiary_code', 'husband_name', 'wife_name')
                ->orderBy('wife_name')
                ->orderBy('husband_name')
                ->get(),
            'categories' => DonationCategory::active()
                ->with('units:id,name')
                ->whereIn('code', self::IN_KIND_CATEGORY_CODES)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'balances' => $this->categoryBalances(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'donation_category_id' => ['required', 'exists:donation_categories,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ], [
            'user_id.required' => 'يرجى اختيار المستفيد.',
            'donation_category_id.required' => 'يرجى اختيار صنف التبرع العيني.',
            'quantity.required' => 'يرجى إدخال كمية الصرف.',
            'quantity.min' => 'كمية الصرف يجب أن تكون أكبر من صفر.',
        ]);

        $category = DonationCategory::with('units:id')->findOrFail($validated['donation_category_id']);
        $donationUnitId = optional($category->units->first())->id;

        $availableQuantity = $this->availableCategoryQuantity((int) $validated['donation_category_id']);
        $requestedQuantity = (float) $validated['quantity'];

        if ($requestedQuantity > $availableQuantity) {
            return redirect()->back()->withInput()->withErrors([
                'quantity' => 'الكمية المطلوبة أكبر من المتاح في خزنة هذا الصنف. المتاح: ' . number_format($availableQuantity, 0),
            ]);
        }

        DB::transaction(function () use ($validated, $requestedQuantity, $donationUnitId) {
            Subvention::create([
                'user_id' => $validated['user_id'],
                'price' => 0,
                'type' => 'once',
                'asset_count' => $requestedQuantity,
                'donation_category_id' => $validated['donation_category_id'],
                'donation_unit_id' => $donationUnitId,
                'comment' => $validated['comment'] ?? 'صرف تبرع عيني',
            ]);
        });

        toastr()->success('تم صرف التبرع العيني بنجاح');

        return redirect()->route('in-kind-disbursements.index');
    }

    private function categoryBalances()
    {
        return DonationCategory::active()
            ->with('units:id,name')
            ->whereIn('code', self::IN_KIND_CATEGORY_CODES)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (DonationCategory $category) {
                return [
                    $category->id => [
                        'available' => $this->availableCategoryQuantity($category->id),
                        'unit' => optional($category->units->first())->name,
                    ],
                ];
            });
    }

    private function availableCategoryQuantity(int $categoryId): float
    {
        $inKindTypeId = DonationType::where('code', 'in_kind')->value('id');

        $incoming = Donation::where('donation_type_id', $inKindTypeId)
            ->where('donation_category_id', $categoryId)
            ->get()
            ->sum(function (Donation $donation) {
                return (float) ($donation->amount_value ?? $donation->asset_count ?? $donation->donation_amount ?? 0);
            });

        $outgoing = Subvention::where('price', 0)
            ->where('donation_category_id', $categoryId)
            ->sum('asset_count');

        return max((float) $incoming - (float) $outgoing, 0);
    }
}
