<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CaseResearchFile;
use App\Models\RequiredDocument;
use App\Models\SocialResearcher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CaseResearchController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->with([
                'latestCaseResearchFile.researcher.admin',
                'latestCaseResearchFile.visits',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery->where('husband_name', 'like', '%' . $search . '%')
                    ->orWhere('wife_name', 'like', '%' . $search . '%')
                    ->orWhere('beneficiary_code', 'like', '%' . $search . '%')
                    ->orWhereHas('latestCaseResearchFile', function ($fileQuery) use ($search) {
                        $fileQuery->where('file_number', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'without_file') {
                $query->doesntHave('caseResearchFiles');
            } else {
                $query->whereHas('latestCaseResearchFile', function ($fileQuery) use ($request) {
                    $fileQuery->where('status', $request->status);
                });
            }
        }

        if ($request->filled('social_researcher_id')) {
            $query->whereHas('latestCaseResearchFile', function ($fileQuery) use ($request) {
                $fileQuery->where('social_researcher_id', $request->social_researcher_id);
            });
        }

        if ($request->filled('final_result')) {
            $query->whereHas('latestCaseResearchFile', function ($fileQuery) use ($request) {
                $fileQuery->where('final_result', $request->final_result);
            });
        }

        $beneficiaries = $query->latest('id')->paginate(12)->withQueryString();

        $stats = [
            'total_beneficiaries' => User::count(),
            'without_file' => User::doesntHave('caseResearchFiles')->count(),
            'total' => CaseResearchFile::count(),
            'new' => CaseResearchFile::where('status', 'new')->count(),
            'in_progress' => CaseResearchFile::where('status', 'in_progress')->count(),
            'delayed' => CaseResearchFile::where('status', 'delayed')->count(),
            'completed' => CaseResearchFile::where('status', 'completed')->count(),
        ];

        $researchers = SocialResearcher::where('is_active', true)->orderBy('name')->get();

        return view('admin.case-research.index', compact('beneficiaries', 'stats', 'researchers'));
    }

    public function create()
    {
        return view('admin.case-research.form', $this->formData(new CaseResearchFile(), [
            'allUsers' => User::orderBy('husband_name')->get(),
            'preselectedUserId' => request('user_id'),
        ]));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        DB::transaction(function () use ($request, &$validated) {
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $attachment) {
                    $attachments[] = $attachment->store('case-research-attachments', 'public');
                }
                $validated['attachments'] = $attachments;
            }

            if (!empty($validated['expected_end_at']) && $validated['status'] !== 'completed') {
                if (now()->startOfDay()->gt(\Carbon\Carbon::parse($validated['expected_end_at']))) {
                    $validated['status'] = 'delayed';
                }
            }

            $fileData = $validated;
            unset($fileData['visits']);

            $file = CaseResearchFile::create(array_merge($fileData, [
                'file_number' => $this->nextFileNumber(),
            ]));

            $this->syncVisits($request, $file);
        });

        return redirect()->route('case-research.index')->with('success', 'تم إنشاء ملف البحث بنجاح.');
    }

    public function edit($id)
    {
        $file = CaseResearchFile::with('visits')->findOrFail($id);

        return view('admin.case-research.form', $this->formData($file, [
            'allUsers' => User::orderBy('husband_name')->get(),
        ]));
    }

    public function attachment($id, $index)
    {
        $file = CaseResearchFile::findOrFail($id);
        $attachments = is_array($file->attachments) ? array_values($file->attachments) : [];
        $attachmentPath = $attachments[(int) $index] ?? null;

        abort_unless($attachmentPath, 404);
        abort_unless(Storage::disk('public')->exists($attachmentPath), 404);

        $absolutePath = Storage::disk('public')->path($attachmentPath);

        return response()->file($absolutePath, [
            'Content-Type' => File::mimeType($absolutePath) ?: 'application/octet-stream',
        ]);
    }

    public function update(Request $request, $id)
    {
        $file = CaseResearchFile::with('visits')->findOrFail($id);
        $validated = $this->validateRequest($request);

        DB::transaction(function () use ($request, $validated, $file) {
            if ($request->hasFile('attachments')) {
                $existingAttachments = $file->attachments ?? [];
                foreach ($request->file('attachments') as $attachment) {
                    $existingAttachments[] = $attachment->store('case-research-attachments', 'public');
                }
                $validated['attachments'] = $existingAttachments;
            }

            if (!empty($validated['expected_end_at']) && $validated['status'] !== 'completed') {
                if (now()->startOfDay()->gt(\Carbon\Carbon::parse($validated['expected_end_at']))) {
                    $validated['status'] = 'delayed';
                }
            }

            $fileData = $validated;
            unset($fileData['visits']);

            $file->update($fileData);
            $this->syncVisits($request, $file, true);
        });

        return redirect()->route('case-research.index')->with('success', 'تم تحديث ملف البحث بنجاح.');
    }

    public function destroy(Request $request)
    {
        $file = CaseResearchFile::findOrFail($request->id);
        $file->delete();

        return response()->json(['status' => true]);
    }

    public function workload()
    {
        $researchers = SocialResearcher::with(['admin', 'supervisor'])
            ->withCount('caseResearchFiles')
            ->withCount([
                'caseResearchFiles as delayed_cases_count' => function ($query) {
                    $query->where('status', 'delayed');
                },
                'caseResearchFiles as completed_cases_count' => function ($query) {
                    $query->where('status', 'completed');
                },
            ])
            ->get()
            ->map(function ($researcher) {
                $averageDays = CaseResearchFile::where('social_researcher_id', $researcher->id)
                    ->whereNotNull('completed_at')
                    ->selectRaw('AVG(DATEDIFF(completed_at, started_at)) as average_days')
                    ->value('average_days');

                $researcher->average_completion_days = $averageDays ? round($averageDays, 1) : null;
                return $researcher;
            });

        return view('admin.case-research.workload', compact('researchers'));
    }

    public function researchers()
    {
        $researchers = SocialResearcher::with(['admin', 'supervisor'])
            ->withCount('caseResearchFiles')
            ->latest()
            ->get();
        $admins = Admin::orderBy('name')->get();
        $researcherAdmins = Admin::where('job_title', 'باحث')->orderBy('name')->get();

        return view('admin.case-research.researchers', compact('researchers', 'admins', 'researcherAdmins'));
    }

    public function storeResearcher(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'admin_id' => ['nullable', 'exists:admins,id', 'unique:social_researchers,admin_id'],
            'supervisor_admin_id' => ['nullable', 'exists:admins,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ], [], [
            'name' => 'اسم الباحث',
            'admin_id' => 'المشرف المرتبط',
            'supervisor_admin_id' => 'المشرف المباشر',
        ]);

        SocialResearcher::create([
            'name' => $validated['name'],
            'admin_id' => $validated['admin_id'] ?? null,
            'supervisor_admin_id' => $validated['supervisor_admin_id'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('case-research.researchers')->with('success', 'تم إضافة الباحث بنجاح.');
    }

    private function formData(CaseResearchFile $file, array $extra = [])
    {
        return array_merge([
            'file' => $file,
            'researchers' => SocialResearcher::where('is_active', true)->orderBy('name')->get(),
            'requiredDocuments' => RequiredDocument::active()->orderBy('name')->get(),
            'statuses' => $this->statuses(),
            'results' => $this->results(),
            'preselectedUserId' => null,
        ], $extra);
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('case_research_files', 'user_id')->ignore($request->route('id')),
            ],
            'social_researcher_id' => ['nullable', 'exists:social_researchers,id'],
            'started_at' => ['nullable', 'date'],
            'expected_end_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'actual_end_at' => ['nullable', 'date'],
            'status' => ['required', 'in:new,in_progress,completed,delayed,cancelled'],
            'delay_reason' => ['nullable', 'string', 'max:255'],
            'final_result' => ['nullable', 'in:eligible,not_eligible,needs_follow_up,needs_documents'],
            'summary' => ['nullable', 'string'],
            'provided_documents' => ['nullable', 'array'],
            'missing_documents' => ['nullable', 'array'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,webp', 'max:5120'],
            'visits' => ['nullable', 'array'],
            'visits.*.visited_at' => ['nullable', 'date'],
            'visits.*.notes' => ['nullable', 'string'],
        ], [], [
            'user_id' => 'المستفيد',
            'social_researcher_id' => 'الباحث',
            'started_at' => 'تاريخ بدء البحث',
            'expected_end_at' => 'تاريخ النهاية المتوقعة',
            'completed_at' => 'تاريخ الإنهاء',
            'status' => 'حالة البحث',
            'final_result' => 'نتيجة البحث',
        ]);
    }

    private function syncVisits(Request $request, CaseResearchFile $file, $replace = false)
    {
        if ($replace) {
            foreach ($file->visits as $visit) {
                if ($visit->attachment_path) {
                    Storage::disk('public')->delete($visit->attachment_path);
                }
            }
            $file->visits()->delete();
        }

        foreach ((array) $request->input('visits', []) as $index => $visitData) {
            if (empty($visitData['visited_at']) && empty($visitData['notes']) && !$request->hasFile("visits.$index.attachment")) {
                continue;
            }

            $attachmentPath = null;
            if ($request->hasFile("visits.$index.attachment")) {
                $attachmentPath = $request->file("visits.$index.attachment")->store('case-research-visits', 'public');
            }

            $file->visits()->create([
                'visited_at' => $visitData['visited_at'] ?? now()->toDateString(),
                'notes' => $visitData['notes'] ?? null,
                'attachment_path' => $attachmentPath,
            ]);
        }
    }

    private function nextFileNumber()
    {
        do {
            $candidate = 'RF-' . now()->format('Y') . '-' . Str::upper(Str::random(6));
        } while (CaseResearchFile::where('file_number', $candidate)->exists());

        return $candidate;
    }

    private function statuses()
    {
        return [
            'new' => 'جديد',
            'in_progress' => 'جاري',
            'completed' => 'مكتمل',
            'delayed' => 'متأخر',
            'cancelled' => 'ملغي',
        ];
    }

    private function results()
    {
        return [
            'eligible' => 'مستحق',
            'not_eligible' => 'غير مستحق',
            'needs_follow_up' => 'يحتاج متابعة',
            'needs_documents' => 'يحتاج مستندات إضافية',
        ];
    }
}
