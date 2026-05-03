<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AssociationExpense;
use App\Models\BeneficiaryCategory;
use App\Models\CaseResearchFile;
use App\Models\Center;
use App\Models\Donation;
use App\Models\DonationType;
use App\Models\Donor;
use App\Models\ExpenseType;
use App\Models\Governorate;
use App\Models\Setting;
use App\Models\SocialResearcher;
use App\Models\Subvention;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mpdf\Mpdf;

class ReportsController extends Controller
{
    public function incomingDonations(Request $request)
    {
        return $this->renderReport($request, $this->buildIncomingDonationsReport($request), 'incoming-donations');
    }

    public function outgoingDonations(Request $request)
    {
        return $this->renderReport($request, $this->buildOutgoingDonationsReport($request), 'outgoing-donations');
    }

    public function expenses(Request $request)
    {
        return $this->renderReport($request, $this->buildExpensesReport($request), 'expenses');
    }

    public function comparison(Request $request)
    {
        return $this->renderReport($request, $this->buildComparisonReport($request), 'comparison');
    }

    public function beneficiaries(Request $request)
    {
        return $this->renderReport($request, $this->buildBeneficiariesReport($request), 'beneficiaries');
    }

    public function caseResearch(Request $request)
    {
        return $this->renderReport($request, $this->buildCaseResearchReport($request), 'case-research');
    }

    private function renderReport(Request $request, array $report, string $slug)
    {
        $report = $this->applySelectedRows($request, $report);

        if ($request->get('export') === 'excel') {
            return $this->exportCsv($report['title'], $report['columns'], $report['rows'], $slug);
        }

        if ($request->get('export') === 'pdf') {
            return $this->exportPdf($report);
        }

        if ($request->get('export') === 'print') {
            return view('admin.reports.print', $report);
        }

        return view('admin.reports.show', $report + ['slug' => $slug]);
    }

    private function buildIncomingDonationsReport(Request $request): array
    {
        $query = Donation::with(['donor', 'referenceDonationType', 'donationCategory', 'unit']);

        $this->applyDateFilter($query, $request, 'received_at');

        if ($request->filled('donation_kind')) {
            $query->where('donation_kind', (string) $request->get('donation_kind'));
        }

        if ($request->filled('donation_type_id')) {
            $query->where('donation_type_id', (int) $request->get('donation_type_id'));
        }

        if ($request->filled('donor_id')) {
            $query->where('donor_id', (int) $request->get('donor_id'));
        }

        $groupBy = $request->get('group_by', 'details');
        $donations = $query->latest('received_at')->get();

        if ($groupBy === 'details') {
            $rows = $donations->map(function (Donation $donation) {
                return [
                    'التاريخ' => optional($donation->received_at)->format('Y-m-d') ?: '--',
                    'المتبرع' => optional($donation->donor)->name ?? 'غير معروف',
                    'نوع التبرع' => $donation->donation_kind === 'in_kind' ? 'عيني' : 'مالي',
                    'التصنيف التفصيلي' => optional($donation->referenceDonationType)->name ?? '--',
                    'الصنف العيني' => optional($donation->donationCategory)->name ?? '--',
                    'القيمة/الكمية' => $donation->display_value,
                    'رقم الوصل' => $donation->receipt_number ?? '--',
                ];
            });
        } else {
            $rows = $donations
                ->groupBy(fn (Donation $donation) => $this->incomingGroupLabel($donation, $groupBy))
                ->map(function (Collection $items, string $label) {
                    return [
                        'التجميع' => $label,
                        'عدد العمليات' => $items->count(),
                        'إجمالي القيمة' => number_format($items->sum(fn (Donation $donation) => (float) ($donation->amount_value ?? 0)), 2),
                        'إجمالي العينيات' => number_format($items->sum(fn (Donation $donation) => $donation->donation_kind === 'in_kind' ? (float) ($donation->amount_value ?? 0) : 0), 2),
                    ];
                })
                ->values();
        }

        $summaryCards = [
            ['label' => 'إجمالي العمليات', 'value' => number_format($donations->count())],
            ['label' => 'إجمالي التبرعات المالية', 'value' => number_format($donations->where('donation_kind', '!=', 'in_kind')->sum('amount_value'), 2)],
            ['label' => 'إجمالي التبرعات العينية', 'value' => number_format($donations->where('donation_kind', 'in_kind')->sum('amount_value'), 2)],
            ['label' => 'عدد المتبرعين', 'value' => number_format($donations->pluck('donor_id')->filter()->unique()->count())],
        ];

        return [
            'title' => 'تقرير التبرعات الواردة',
            'description' => 'تقرير تفصيلي للتبرعات الواردة مع التصفية حسب التاريخ والنوع والمتبرع والتجميع الزمني.',
            'filters' => [
                ['name' => 'from', 'label' => 'من تاريخ', 'type' => 'date', 'value' => $request->get('from')],
                ['name' => 'to', 'label' => 'إلى تاريخ', 'type' => 'date', 'value' => $request->get('to')],
                ['name' => 'donation_kind', 'label' => 'نوع التبرع', 'type' => 'select', 'value' => $request->get('donation_kind'), 'options' => [
                    '' => 'الكل',
                    'financial' => 'مالية',
                    'in_kind' => 'عينية',
                ]],
                ['name' => 'donation_type_id', 'label' => 'التصنيف التفصيلي', 'type' => 'select', 'value' => $request->get('donation_type_id'), 'options' => ['' => 'الكل'] + DonationType::active()->orderBy('sort_order')->pluck('name', 'id')->toArray()],
                ['name' => 'donor_id', 'label' => 'المتبرع', 'type' => 'select', 'value' => $request->get('donor_id'), 'options' => ['' => 'الكل'] + Donor::orderBy('name')->pluck('name', 'id')->toArray()],
                ['name' => 'group_by', 'label' => 'تجميع حسب', 'type' => 'select', 'value' => $groupBy, 'options' => [
                    'details' => 'تفصيلي',
                    'day' => 'اليوم',
                    'month' => 'الشهر',
                    'year' => 'السنة',
                    'kind' => 'نوع التبرع',
                    'type' => 'التصنيف التفصيلي',
                    'donor' => 'المتبرع',
                ]],
            ],
            'summaryCards' => $summaryCards,
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->values()->all(),
            'setting' => Setting::first(),
        ];
    }

    private function buildOutgoingDonationsReport(Request $request): array
    {
        $query = Subvention::with(['user.governorate', 'user.center', 'user.village', 'donationCategory', 'donationUnit']);
        $this->applyDateFilter($query, $request);

        if ($request->filled('governorate_id')) {
            $query->whereHas('user', fn ($userQuery) => $userQuery->where('governorate_id', (int) $request->get('governorate_id')));
        }

        if ($request->filled('beneficiary_id')) {
            $query->where('user_id', (int) $request->get('beneficiary_id'));
        }

        if ($request->filled('assistance_type')) {
            $type = (string) $request->get('assistance_type');
            if ($type === 'in_kind') {
                $query->where('price', 0)->whereNotNull('donation_category_id');
            } else {
                $query->where('type', $type);
            }
        }

        $rowsSource = $query->latest()->get();
        $rows = $rowsSource->map(function (Subvention $subvention) {
            $beneficiary = optional($subvention->user)->wife_name ?: optional($subvention->user)->husband_name ?: 'غير معروف';
            $location = collect([
                optional(optional($subvention->user)->governorate)->name,
                optional(optional($subvention->user)->center)->name,
                optional(optional($subvention->user)->village)->name,
            ])->filter()->implode(' - ');

            $assistanceType = $subvention->price > 0
                ? ($subvention->type === 'monthly' ? 'إعانة شهرية' : 'إعانة فردية')
                : 'صرف عيني';

            $value = $subvention->price > 0
                ? number_format((float) $subvention->price, 2) . ' جنيه'
                : trim(($subvention->asset_count ?? 0) . ' ' . (optional($subvention->donationUnit)->name ?? ''));

            return [
                'التاريخ' => optional($subvention->created_at)->format('Y-m-d') ?: '--',
                'المستفيد' => $beneficiary,
                'المنطقة' => $location ?: '--',
                'نوع المساعدة' => $assistanceType,
                'الصنف/الوحدة' => optional($subvention->donationCategory)->name ?? '--',
                'القيمة/الكمية' => $value,
            ];
        });

        return [
            'title' => 'تقرير التبرعات المنصرفة',
            'description' => 'يعرض جميع المساعدات المصروفة مع التصفية حسب المستفيد والمنطقة ونوع المساعدة والفترة الزمنية.',
            'filters' => [
                ['name' => 'from', 'label' => 'من تاريخ', 'type' => 'date', 'value' => $request->get('from')],
                ['name' => 'to', 'label' => 'إلى تاريخ', 'type' => 'date', 'value' => $request->get('to')],
                ['name' => 'beneficiary_id', 'label' => 'المستفيد', 'type' => 'select', 'value' => $request->get('beneficiary_id'), 'options' => ['' => 'الكل'] + User::orderBy('wife_name')->get()->mapWithKeys(fn (User $user) => [$user->id => ($user->wife_name ?: $user->husband_name ?: 'مستفيد #' . $user->id)])->toArray()],
                ['name' => 'governorate_id', 'label' => 'المحافظة', 'type' => 'select', 'value' => $request->get('governorate_id'), 'options' => ['' => 'الكل'] + Governorate::orderBy('name')->pluck('name', 'id')->toArray()],
                ['name' => 'assistance_type', 'label' => 'نوع المساعدة', 'type' => 'select', 'value' => $request->get('assistance_type'), 'options' => [
                    '' => 'الكل',
                    'monthly' => 'إعانة شهرية',
                    'once' => 'إعانة فردية',
                    'in_kind' => 'صرف عيني',
                ]],
            ],
            'summaryCards' => [
                ['label' => 'عدد العمليات', 'value' => number_format($rowsSource->count())],
                ['label' => 'الإعانات المالية', 'value' => number_format($rowsSource->where('price', '>', 0)->sum('price'), 2)],
                ['label' => 'الصرف العيني', 'value' => number_format($rowsSource->where('price', 0)->sum('asset_count'))],
                ['label' => 'عدد المستفيدين', 'value' => number_format($rowsSource->pluck('user_id')->filter()->unique()->count())],
            ],
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->all(),
            'setting' => Setting::first(),
        ];
    }

    private function buildExpensesReport(Request $request): array
    {
        $query = AssociationExpense::with(['expenseType', 'admin']);
        $this->applyDateFilter($query, $request, 'transaction_date');

        if ($request->filled('expense_type_id')) {
            $query->where('expense_type_id', (int) $request->get('expense_type_id'));
        }

        if ($request->filled('admin_id')) {
            $query->where('admin_id', (int) $request->get('admin_id'));
        }

        $rowsSource = $query->latest('transaction_date')->get();
        $rows = $rowsSource->map(function (AssociationExpense $expense) {
            return [
                'التاريخ' => optional($expense->transaction_date)->format('Y-m-d') ?: '--',
                'نوع المصروف' => optional($expense->expenseType)->name ?? '--',
                'المسؤول' => optional($expense->admin)->name ?? '--',
                'رقم المرجع' => $expense->reference_number ?: '--',
                'القيمة' => number_format((float) $expense->amount, 2),
                'ملاحظات' => $expense->notes ?: '--',
            ];
        });

        return [
            'title' => 'تقرير المصروفات',
            'description' => 'يعرض المصروفات حسب نوع المصروف والمسؤول والفترة الزمنية.',
            'filters' => [
                ['name' => 'from', 'label' => 'من تاريخ', 'type' => 'date', 'value' => $request->get('from')],
                ['name' => 'to', 'label' => 'إلى تاريخ', 'type' => 'date', 'value' => $request->get('to')],
                ['name' => 'expense_type_id', 'label' => 'نوع المصروف', 'type' => 'select', 'value' => $request->get('expense_type_id'), 'options' => ['' => 'الكل'] + ExpenseType::orderBy('name')->pluck('name', 'id')->toArray()],
                ['name' => 'admin_id', 'label' => 'المسؤول', 'type' => 'select', 'value' => $request->get('admin_id'), 'options' => ['' => 'الكل'] + Admin::orderBy('name')->pluck('name', 'id')->toArray()],
            ],
            'summaryCards' => [
                ['label' => 'عدد القيود', 'value' => number_format($rowsSource->count())],
                ['label' => 'إجمالي المصروفات', 'value' => number_format($rowsSource->sum('amount'), 2)],
                ['label' => 'أنواع المصروفات', 'value' => number_format($rowsSource->pluck('expense_type_id')->filter()->unique()->count())],
                ['label' => 'عدد المسؤولين', 'value' => number_format($rowsSource->pluck('admin_id')->filter()->unique()->count())],
            ],
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->all(),
            'setting' => Setting::first(),
        ];
    }

    private function buildComparisonReport(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->get('from'))->startOfMonth() : now()->subMonths(11)->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->get('to'))->endOfMonth() : now()->endOfMonth();

        $rows = collect();
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();

            $incoming = Donation::whereBetween('received_at', [$monthStart, $monthEnd])
                ->where('donation_kind', '!=', 'in_kind')
                ->sum('amount_value');

            $outgoing = Subvention::whereBetween('created_at', [$monthStart, $monthEnd])->sum('price')
                + AssociationExpense::whereBetween('transaction_date', [$monthStart, $monthEnd])->sum('amount');

            $surplus = $incoming - $outgoing;

            $rows->push([
                'الفترة' => $cursor->translatedFormat('F Y'),
                'التبرعات' => number_format($incoming, 2),
                'المصروفات' => number_format($outgoing, 2),
                'الفائض/العجز' => number_format($surplus, 2),
                'الحالة' => $surplus >= 0 ? 'فائض' : 'عجز',
            ]);

            $cursor->addMonth();
        }

        return [
            'title' => 'تقارير المقارنة والتحليل',
            'description' => 'مقارنة التبرعات بالمصروفات وتحليل الفائض أو العجز عبر الفترات الزمنية.',
            'filters' => [
                ['name' => 'from', 'label' => 'من شهر', 'type' => 'date', 'value' => $request->get('from')],
                ['name' => 'to', 'label' => 'إلى شهر', 'type' => 'date', 'value' => $request->get('to')],
            ],
            'summaryCards' => [
                ['label' => 'إجمالي التبرعات', 'value' => number_format($rows->sum(fn ($row) => (float) str_replace(',', '', $row['التبرعات'])), 2)],
                ['label' => 'إجمالي المصروفات', 'value' => number_format($rows->sum(fn ($row) => (float) str_replace(',', '', $row['المصروفات'])), 2)],
                ['label' => 'صافي الفارق', 'value' => number_format($rows->sum(fn ($row) => (float) str_replace(',', '', $row['الفائض/العجز'])), 2)],
                ['label' => 'عدد الفترات', 'value' => number_format($rows->count())],
            ],
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->all(),
            'setting' => Setting::first(),
        ];
    }

    private function buildBeneficiariesReport(Request $request): array
    {
        $query = User::with(['governorate', 'center', 'village', 'beneficiaryCategory']);

        if ($request->filled('status')) {
            $query->where('status', (string) $request->get('status'));
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', (int) $request->get('governorate_id'));
        }

        if ($request->filled('beneficiary_category_id')) {
            $query->where('beneficiary_category_id', (int) $request->get('beneficiary_category_id'));
        }

        if ($request->filled('social_status')) {
            $query->where('social_status', (string) $request->get('social_status'));
        }

        $rowsSource = $query->latest()->get();
        $rows = $rowsSource->map(function (User $user) {
            return [
                'المستفيد' => $user->wife_name ?: $user->husband_name ?: 'غير معروف',
                'المحافظة' => optional($user->governorate)->name ?? '--',
                'المركز' => optional($user->center)->name ?? '--',
                'القرية' => optional($user->village)->name ?? '--',
                'التصنيف' => optional($user->beneficiaryCategory)->name ?? '--',
                'الحالة' => $this->beneficiaryStatusLabel($user->status),
                'الحالة الاجتماعية' => $this->socialStatusLabel($user->social_status),
                'العمر' => $user->age_wife ?: $user->age_husband ?: '--',
            ];
        });

        return [
            'title' => 'تقرير المستفيدين',
            'description' => 'توزيع المستفيدين حسب المحافظة والتصنيف والحالة الاجتماعية وحالة الملف.',
            'filters' => [
                ['name' => 'status', 'label' => 'حالة الملف', 'type' => 'select', 'value' => $request->get('status'), 'options' => [
                    '' => 'الكل',
                    'new' => 'جديد',
                    'preparing' => 'قيد التجهيز',
                    'accepted' => 'مقبول',
                    'refused' => 'مرفوض',
                ]],
                ['name' => 'governorate_id', 'label' => 'المحافظة', 'type' => 'select', 'value' => $request->get('governorate_id'), 'options' => ['' => 'الكل'] + Governorate::orderBy('name')->pluck('name', 'id')->toArray()],
                ['name' => 'beneficiary_category_id', 'label' => 'التصنيف', 'type' => 'select', 'value' => $request->get('beneficiary_category_id'), 'options' => ['' => 'الكل'] + BeneficiaryCategory::orderBy('name')->pluck('name', 'id')->toArray()],
                ['name' => 'social_status', 'label' => 'الحالة الاجتماعية', 'type' => 'select', 'value' => $request->get('social_status'), 'options' => [
                    '' => 'الكل',
                    '0' => 'أعزب/عزباء',
                    '1' => 'متزوج/متزوجة',
                    '2' => 'مطلق/مطلقة',
                    '3' => 'أرمل/أرملة',
                ]],
            ],
            'summaryCards' => [
                ['label' => 'إجمالي المستفيدين', 'value' => number_format($rowsSource->count())],
                ['label' => 'المقبولون', 'value' => number_format($rowsSource->where('status', 'accepted')->count())],
                ['label' => 'التصنيفات المستخدمة', 'value' => number_format($rowsSource->pluck('beneficiary_category_id')->filter()->unique()->count())],
                ['label' => 'المحافظات', 'value' => number_format($rowsSource->pluck('governorate_id')->filter()->unique()->count())],
            ],
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->all(),
            'setting' => Setting::first(),
        ];
    }

    private function buildCaseResearchReport(Request $request): array
    {
        $query = CaseResearchFile::with(['user', 'researcher']);
        $this->applyDateFilter($query, $request, 'created_at');

        if ($request->filled('status')) {
            $query->where('status', (string) $request->get('status'));
        }

        if ($request->filled('social_researcher_id')) {
            $query->where('social_researcher_id', (int) $request->get('social_researcher_id'));
        }

        $rowsSource = $query->latest()->get();
        $rows = $rowsSource->map(function (CaseResearchFile $file) {
            $startedAt = $file->started_at ?: $file->created_at;
            $endedAt = $file->completed_at ?: $file->actual_end_at;
            $duration = ($startedAt && $endedAt) ? Carbon::parse($startedAt)->diffInDays(Carbon::parse($endedAt)) : null;

            return [
                'رقم الملف' => $file->file_number,
                'الحالة' => $this->caseStatusLabel($file->status),
                'المستفيد' => optional($file->user)->wife_name ?: optional($file->user)->husband_name ?: 'غير معروف',
                'الباحث' => optional($file->researcher)->name ?? '--',
                'تاريخ البدء' => optional($file->started_at ?: $file->created_at)->format('Y-m-d') ?: '--',
                'الموعد المتوقع' => optional($file->expected_end_at)->format('Y-m-d') ?: '--',
                'الإغلاق الفعلي' => optional($file->completed_at ?: $file->actual_end_at)->format('Y-m-d') ?: '--',
                'مدة الإغلاق بالأيام' => $duration ?? '--',
            ];
        });

        $completedWithDuration = $rowsSource->filter(fn (CaseResearchFile $file) => ($file->started_at || $file->created_at) && ($file->completed_at || $file->actual_end_at));
        $avgDays = $completedWithDuration->count()
            ? round($completedWithDuration->avg(fn (CaseResearchFile $file) => Carbon::parse($file->started_at ?: $file->created_at)->diffInDays(Carbon::parse($file->completed_at ?: $file->actual_end_at))), 1)
            : 0;

        return [
            'title' => 'تقرير بحث الحالات',
            'description' => 'يعرض عدد الحالات الجارية والمتأخرة والمكتملة ومتوسط زمن الإغلاق وأداء الباحثين.',
            'filters' => [
                ['name' => 'from', 'label' => 'من تاريخ', 'type' => 'date', 'value' => $request->get('from')],
                ['name' => 'to', 'label' => 'إلى تاريخ', 'type' => 'date', 'value' => $request->get('to')],
                ['name' => 'status', 'label' => 'حالة البحث', 'type' => 'select', 'value' => $request->get('status'), 'options' => [
                    '' => 'الكل',
                    'new' => 'جديد',
                    'in_progress' => 'جاري',
                    'completed' => 'مكتمل',
                    'delayed' => 'متأخر',
                    'cancelled' => 'ملغي',
                ]],
                ['name' => 'social_researcher_id', 'label' => 'الباحث', 'type' => 'select', 'value' => $request->get('social_researcher_id'), 'options' => ['' => 'الكل'] + SocialResearcher::orderBy('name')->pluck('name', 'id')->toArray()],
            ],
            'summaryCards' => [
                ['label' => 'الحالات الجارية', 'value' => number_format($rowsSource->where('status', 'in_progress')->count())],
                ['label' => 'الحالات المتأخرة', 'value' => number_format($rowsSource->filter(fn (CaseResearchFile $file) => $file->status === 'delayed')->count())],
                ['label' => 'الحالات المكتملة', 'value' => number_format($rowsSource->where('status', 'completed')->count())],
                ['label' => 'متوسط الإغلاق', 'value' => $avgDays . ' يوم'],
            ],
            'columns' => array_keys($rows->first() ?? ['لا توجد بيانات' => '']),
            'rows' => $rows->all(),
            'setting' => Setting::first(),
        ];
    }

    private function exportCsv(string $title, array $columns, array $rows, string $slug)
    {
        $fileName = $slug . '-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($columns, $rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                fputcsv($handle, array_values($row));
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function exportPdf(array $report)
    {
        $html = view('admin.reports.print', $report)->render();
        $tempDir = storage_path('app/mpdf-temp');

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => $tempDir,
            'margin_top' => 14,
            'margin_bottom' => 14,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDirectionality('rtl');
        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output('report-' . now()->format('Y-m-d-His') . '.pdf', 'S'),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="report-' . now()->format('Y-m-d-His') . '.pdf"',
            ]
        );
    }

    private function applyDateFilter($query, Request $request, string $column = 'created_at'): void
    {
        if ($request->filled('from')) {
            $query->whereDate($column, '>=', $request->get('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate($column, '<=', $request->get('to'));
        }
    }

    private function applySelectedRows(Request $request, array $report): array
    {
        $scope = $request->get('export_scope', 'all');
        $selectedRows = collect(explode(',', (string) $request->get('selected_rows')))
            ->filter(fn ($value) => $value !== '')
            ->map(fn ($value) => (int) $value)
            ->values();

        if ($scope !== 'selected' || $selectedRows->isEmpty()) {
            return $report;
        }

        $report['rows'] = collect($report['rows'])
            ->values()
            ->filter(fn ($row, $index) => $selectedRows->contains($index))
            ->values()
            ->all();

        return $report;
    }

    private function incomingGroupLabel(Donation $donation, string $groupBy): string
    {
        return match ($groupBy) {
            'day' => optional($donation->received_at)->format('Y-m-d') ?: '--',
            'month' => optional($donation->received_at)->format('Y-m') ?: '--',
            'year' => optional($donation->received_at)->format('Y') ?: '--',
            'kind' => $donation->donation_kind === 'in_kind' ? 'عيني' : 'مالي',
            'type' => optional($donation->referenceDonationType)->name ?? '--',
            'donor' => optional($donation->donor)->name ?? 'غير معروف',
            default => 'تفصيلي',
        };
    }

    private function beneficiaryStatusLabel(?string $status): string
    {
        return match ($status) {
            'new' => 'جديد',
            'preparing' => 'قيد التجهيز',
            'accepted' => 'مقبول',
            'refused' => 'مرفوض',
            default => '--',
        };
    }

    private function socialStatusLabel($status): string
    {
        return match ((string) $status) {
            '0' => 'أعزب/عزباء',
            '1' => 'متزوج/متزوجة',
            '2' => 'مطلق/مطلقة',
            '3' => 'أرمل/أرملة',
            default => '--',
        };
    }

    private function caseStatusLabel(?string $status): string
    {
        return match ($status) {
            'new' => 'جديد',
            'in_progress' => 'جاري',
            'completed' => 'مكتمل',
            'delayed' => 'متأخر',
            'cancelled' => 'ملغي',
            default => '--',
        };
    }
}
