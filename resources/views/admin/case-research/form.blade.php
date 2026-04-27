@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | {{ $file->exists ? 'تعديل ملف بحث' : 'إضافة ملف بحث' }}
@endsection

@section('page_name')
    {{ $file->exists ? 'تعديل ملف بحث' : 'إضافة ملف بحث' }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">{{ $file->exists ? 'تعديل ملف البحث' : 'إنشاء ملف بحث جديد' }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ $file->exists ? route('case-research.update', $file->id) : route('case-research.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($file->exists)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>المستفيد</label>
                            <select name="user_id" class="form-control select2" required>
                                <option value="">اختر المستفيد</option>
                                @foreach($allUsers as $user)
                                    @php
                                        $userName = $user->husband_name ?: ($user->wife_name ?: 'بدون اسم');
                                    @endphp
                                    <option value="{{ $user->id }}" {{ (string) old('user_id', $file->user_id ?: $preselectedUserId) === (string) $user->id ? 'selected' : '' }}>
                                        {{ $userName }} - {{ $user->beneficiary_code ?: 'بدون كود' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>مسؤول البحث</label>
                            <select name="social_researcher_id" class="form-control select2">
                                <option value="">غير محدد</option>
                                @foreach($researchers as $researcher)
                                    <option value="{{ $researcher->id }}" {{ old('social_researcher_id', $file->social_researcher_id) == $researcher->id ? 'selected' : '' }}>
                                        {{ $researcher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('social_researcher_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>تاريخ بدء البحث</label>
                            <input type="date" name="started_at" class="form-control" value="{{ old('started_at', optional($file->started_at)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>تاريخ انتهاء البحث</label>
                            <input type="date" name="expected_end_at" class="form-control" value="{{ old('expected_end_at', optional($file->expected_end_at)->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>حالة البحث</label>
                            <select name="status" class="form-control" required>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $file->status ?: 'new') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>نتيجة البحث النهائية</label>
                            <select name="final_result" class="form-control">
                                <option value="">بدون تحديد</option>
                                @foreach($results as $key => $label)
                                    <option value="{{ $key }}" {{ old('final_result', $file->final_result) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>سبب التأخر</label>
                            <input type="text" name="delay_reason" class="form-control" value="{{ old('delay_reason', $file->delay_reason) }}" placeholder="يظهر عند التأخر فقط">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>المستندات التي تم توفيرها (المستلمة)</label>
                            <select name="provided_documents[]" class="form-control select2" multiple>
                                @php $providedDocs = old('provided_documents', $file->provided_documents ?? []); @endphp
                                @foreach($requiredDocuments as $doc)
                                    <option value="{{ $doc->name }}" {{ in_array($doc->name, $providedDocs) ? 'selected' : '' }}>{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>المستندات الناقصة (المطلوبة)</label>
                            <select name="missing_documents[]" class="form-control select2" multiple>
                                @php $missingDocs = old('missing_documents', $file->missing_documents ?? []); @endphp
                                @foreach($requiredDocuments as $doc)
                                    <option value="{{ $doc->name }}" {{ in_array($doc->name, $missingDocs) ? 'selected' : '' }}>{{ $doc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>ملخص البحث</label>
                            <textarea name="summary" class="form-control" rows="4" placeholder="ملخص الحالة ونتائج الزيارة">{{ old('summary', $file->summary) }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>مرفقات الملف الحالية والمضافة</label>
                            @if(is_array($file->attachments) && count($file->attachments))
                                <div class="row mb-3">
                                    @foreach($file->attachments as $attachmentIndex => $attachment)
                                        @php
                                            $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                            $attachmentUrl = route('case-research.attachment', ['id' => $file->id, 'index' => $attachmentIndex]);
                                        @endphp
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            @if($isImage)
                                                <a href="{{ $attachmentUrl }}" target="_blank" class="d-block border rounded overflow-hidden">
                                                    <img src="{{ $attachmentUrl }}" alt="مرفق ملف البحث" style="width: 100%; height: 160px; object-fit: cover;">
                                                </a>
                                            @else
                                                <a href="{{ $attachmentUrl }}" target="_blank" class="d-flex flex-column justify-content-center align-items-center text-center border rounded px-2" style="height: 160px; background: #f8f9fa;">
                                                    <i class="fe fe-file-text" style="font-size: 32px;"></i>
                                                    <span class="mt-2 d-block text-break">{{ basename($attachment) }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <label class="text-primary mt-2"><i class="fe fe-plus"></i> إضافة مرفقات جديدة (يمكنك اختيار أكثر من ملف)</label>
                            <input type="file" name="attachments[]" class="dropify" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp" data-height="120">
                        </div>
                    </div>
                </div>

                @php
                    $existingVisits = old('visits', $file->exists ? $file->visits->map(function ($visit) {
                        return [
                            'visited_at' => optional($visit->visited_at)->format('Y-m-d'),
                            'notes' => $visit->notes,
                        ];
                    })->toArray() : []);
                    if (empty($existingVisits)) {
                        $existingVisits = [
                            ['visited_at' => '', 'notes' => ''],
                        ];
                    }
                @endphp

                <div class="border rounded-lg p-3 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">محطات البحث والزيارات</h4>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addVisitRow">إضافة زيارة</button>
                    </div>

                    <div id="visitsWrapper">
                        @foreach($existingVisits as $index => $visit)
                            <div class="visit-row border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>تاريخ الزيارة</label>
                                        <input type="date" class="form-control" name="visits[{{ $index }}][visited_at]" value="{{ $visit['visited_at'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-9">
                                        <label>ملاحظات</label>
                                        <textarea class="form-control" rows="1" name="visits[{{ $index }}][notes]">{{ $visit['notes'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-visit">حذف الزيارة</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 d-flex" style="gap: 10px;">
                    <button type="submit" class="btn btn-primary">حفظ الملف</button>
                    <a href="{{ route('case-research.index') }}" class="btn btn-light">رجوع</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('ajaxCalls')
    <script>
        (function () {
            if ($('.dropify').length) {
                $('.dropify').dropify();
            }

            const wrapper = document.getElementById('visitsWrapper');
            const addVisitRow = document.getElementById('addVisitRow');

            function nextIndex() {
                return wrapper.querySelectorAll('.visit-row').length;
            }

            addVisitRow.addEventListener('click', function () {
                const index = nextIndex();
                const row = document.createElement('div');
                row.className = 'visit-row border rounded p-3 mb-3';
                row.innerHTML = `
                    <div class="row">
                        <div class="col-lg-3">
                            <label>تاريخ الزيارة</label>
                            <input type="date" class="form-control" name="visits[${index}][visited_at]">
                        </div>
                        <div class="col-lg-9">
                            <label>ملاحظات</label>
                            <textarea class="form-control" rows="1" name="visits[${index}][notes]"></textarea>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-visit">حذف الزيارة</button>
                `;
                wrapper.appendChild(row);
            });

            wrapper.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-visit')) {
                    const rows = wrapper.querySelectorAll('.visit-row');
                    if (rows.length > 1) {
                        event.target.closest('.visit-row').remove();
                    }
                }
            });
        })();
    </script>
@endsection
