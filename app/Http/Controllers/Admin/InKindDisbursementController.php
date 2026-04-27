<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\BeneficiaryCategory;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\DonationUnit;
use App\Models\Subvention;
use App\Models\User;
use Carbon\Carbon;
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
        [$fromDate, $toDate] = $this->resolveDateRange($request);

        if ($request->ajax()) {
            $disbursements = Subvention::with(['user', 'donationCategory', 'donationUnit'])
                ->where('price', 0)
                ->whereNotNull('donation_category_id')
                ->whereBetween('created_at', [$fromDate, $toDate])
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
                ->addColumn('action', function ($subvention) {
                    if (!auth()->guard('admin')->user()->can('in-kind-disbursements.delete')) {
                        return '';
                    }

                    return '
                        <button class="btn btn-sm btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $subvention->id . '" data-title="هذا الصرف العيني" title="حذف">
                            <i class="fe fe-trash"></i>
                        </button>
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }

        $totalItems = Subvention::where('price', 0)
            ->whereNotNull('donation_category_id')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('asset_count');

        $periodLabel = $request->filled('from') && $request->filled('to')
            ? 'إجمالي المنصرف العيني خلال الفترة من ' . $fromDate->format('Y-m-d') . ' إلى ' . $toDate->format('Y-m-d')
            : 'إجمالي المنصرف العيني خلال الشهر الحالي';

        return view('admin/in-kind-disbursements/index', compact('fromDate', 'toDate', 'totalItems', 'periodLabel'));
    }

    public function create()
    {
        return view('admin/in-kind-disbursements/create', [
            'users' => User::where('status', 'accepted')
                ->with('beneficiaryCategory:id,name')
                ->select('id', 'beneficiary_code', 'husband_name', 'wife_name', 'beneficiary_category_id')
                ->orderBy('wife_name')
                ->orderBy('husband_name')
                ->get(),
            'beneficiaryCategories' => $this->beneficiaryCategoriesForFilters(),
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
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'exists:users,id'],
            'donation_category_id' => ['required', 'exists:donation_categories,id'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ], [
            'user_ids.required' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.array' => 'قائمة المستفيدين غير صالحة.',
            'user_ids.min' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.*.exists' => 'أحد المستفيدين المختارين غير موجود.',
            'donation_category_id.required' => 'يرجى اختيار صنف التبرع العيني.',
            'quantity.required' => 'يرجى إدخال كمية الصرف.',
            'quantity.min' => 'كمية الصرف يجب أن تكون أكبر من صفر.',
        ]);

        $category = DonationCategory::with('units:id')->findOrFail($validated['donation_category_id']);
        $donationUnitId = optional($category->units->first())->id;

        $availableQuantity = $this->availableCategoryQuantity((int) $validated['donation_category_id']);
        $userIds = collect($validated['user_ids'])->filter()->unique()->values();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        if ($users->isEmpty()) {
            return redirect()->back()->withInput()->withErrors([
                'user_ids' => 'المستفيدون المختارون غير موجودين.',
            ]);
        }

        $quantityPerUser = (float) $validated['quantity'];
        $requestedQuantity = $quantityPerUser * $users->count();

        if ($requestedQuantity > $availableQuantity) {
            return redirect()->back()->withInput()->withErrors([
                'quantity' => 'الكمية المطلوبة أكبر من المتاح في خزنة هذا الصنف. المتاح: ' . number_format($availableQuantity, 0),
            ]);
        }

        DB::transaction(function () use ($validated, $userIds, $users, $quantityPerUser, $donationUnitId) {
            foreach ($userIds as $userId) {
                if (!$users->has($userId)) {
                    continue;
                }

                Subvention::create([
                    'user_id' => $userId,
                    'price' => 0,
                    'type' => 'once',
                    'asset_count' => $quantityPerUser,
                    'donation_category_id' => $validated['donation_category_id'],
                    'donation_unit_id' => $donationUnitId,
                    'comment' => $validated['comment'] ?? 'صرف تبرع عيني',
                ]);
            }
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

    private function beneficiaryCategoriesForFilters()
    {
        $categories = BeneficiaryCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($categories->isNotEmpty()) {
            return $categories;
        }

        $usedCategoryIds = User::where('status', 'accepted')
            ->whereNotNull('beneficiary_category_id')
            ->distinct()
            ->pluck('beneficiary_category_id');

        return BeneficiaryCategory::query()
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
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

    private function resolveDateRange(Request $request): array
    {
        $rawFrom = (string) $request->query('from', '');
        $rawTo = (string) $request->query('to', '');

        if (!$rawTo && str_contains($rawFrom, 'to=')) {
            [$fromPart, $toPart] = explode('to=', $rawFrom, 2);
            $rawFrom = rtrim($fromPart, '&?');
            $rawTo = trim($toPart);
        }

        $fromDate = $rawFrom !== ''
            ? Carbon::parse($rawFrom)->startOfDay()
            : now()->startOfMonth();
        $toDate = $rawTo !== ''
            ? Carbon::parse($rawTo)->endOfDay()
            : now()->endOfMonth();

        return [$fromDate, $toDate];
    }

    public function delete(Request $request)
    {
        try {
            $subvention = Subvention::findOrFail($request->id);
            $subvention->delete();
            return redirect()->back();
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }
}
