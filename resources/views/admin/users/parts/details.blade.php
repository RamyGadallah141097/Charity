@extends('admin/layouts/master')
@section('title') {{ $setting->title ?? '' }} | المستفيدين @endsection
@section('page_name') المستفيدين @endsection

@section('content')
    <div class="card bg-white p-3 shadow-sm">
        <style>
            .gallery-image {
                cursor: pointer;
                transition: 0.3s;
                margin: 5px;
                max-width: 220px;
                height: 150px;
                object-fit: cover;
                border-radius: 8px;
            }

            .gallery-image:hover {
                opacity: 0.75;
            }

            .image-modal {
                display: none;
                position: fixed;
                z-index: 1000;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.9);
                padding-top: 60px;
            }

            .image-modal__content {
                margin: auto;
                display: block;
                max-width: 90%;
                max-height: 85vh;
            }

            .image-modal__close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #f1f1f1;
                font-size: 40px;
                font-weight: bold;
                cursor: pointer;
            }
        </style>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">بيانات المستفيد</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th>اسم الزوج</th>
                            <th>اسم الزوجة</th>
                            <th>الرقم القومى للزوج</th>
                            <th>الرقم القومى للزوجة</th>
                            <th>عمر الزوج</th>
                            <th>عمر الزوجة</th>
                            <th>الحالة الاجتماعية للمستفيد</th>
                            <th>الهاتف</th>
                            <th>نوع العمل</th>
                            <th>العنوان</th>
                            <th>المحافظة</th>
                            <th>المركز</th>
                            <th>القرية</th>
                             <th>التصنيف</th>
                         </tr>
                     </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->husband_name ?: '-' }}</td>
                            <td>{{ $user->wife_name ?: '-' }}</td>
                            <td>{{ $user->husband_national_id ?: '-' }}</td>
                            <td>{{ $user->wife_national_id ?: '-' }}</td>
                            <td>{{ $user->age_husband ?: '-' }}</td>
                            <td>{{ $user->age_wife ?: '-' }}</td>
                            <td>{{ $user->social_status == 0 ? 'اعزب' : ($user->social_status == 1 ? 'متزوج' : ($user->social_status == 2 ? 'مطلق' : 'ارمل')) }}</td>
                            <td>{{ $user->nearest_phone ?: '-' }}</td>
                            <td>{{ $user->work_type ?: '-' }}</td>
                            <td>{{ $user->address ?: '-' }}</td>
                            <td>{{ optional($user->governorate)->name ?? '-' }}</td>
                            <td>{{ optional($user->center)->name ?? '-' }}</td>
                            <td>{{ optional($user->village)->name ?? '-' }}</td>
                             <td>{{ optional($user->beneficiaryCategory)->name ?? '-' }}</td>
                         </tr>
                     </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">تفاصيل الدخل</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th>الراتب</th>
                            <th>معاش</th>
                            <th>تامين</th>
                            <th>كرامة</th>
                            <th>تجارة</th>
                            <th>الوسائد</th>
                            <th>اخرى</th>
                            <th>اجمالى الدخل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->salary ?? 0 }}</td>
                            <td>{{ $user->pension ?? 0 }}</td>
                            <td>{{ $user->insurance ?? 0 }}</td>
                            <td>{{ $user->dignity ?? 0 }}</td>
                            <td>{{ $user->trade ?? 0 }}</td>
                            <td>{{ $user->pillows ?? 0 }}</td>
                            <td>{{ $user->other ?? 0 }}</td>
                            <td>{{ $user->gross_income ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">تفاصيل النفقات</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th>ايجار</th>
                            <th>غاز</th>
                            <th>دين</th>
                            <th>مياه</th>
                            <th>كهرباء</th>
                            <th>جمعية</th>
                            <th>طعام</th>
                            <th>دراسة</th>
                            <th>المصاريف الطبية</th>
                            <th>اجمالى النفقات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->rent ?? 0 }}</td>
                            <td>{{ $user->gas ?? 0 }}</td>
                            <td>{{ $user->debt ?? 0 }}</td>
                            <td>{{ $user->water ?? 0 }}</td>
                            <td>{{ $user->electricity ?? 0 }}</td>
                            <td>{{ $user->association ?? 0 }}</td>
                            <td>{{ $user->food ?? 0 }}</td>
                            <td>{{ $user->study ?? 0 }}</td>
                            <td>{{ $user->medical_expenses ?? 0 }}</td>
                            <td>{{ $user->gross_expenses ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

         <div class="card mb-3">
             <div class="card-header">
                 <h3 class="card-title mb-0">بيانات التقييم والمواعيد</h3>
             </div>
             <div class="card-body">
                 <div class="row">
                     <div class="col-md-4">
                         <label class="form-label">مستوى المعيشة</label>
                         <input type="text" class="form-control" value="{{ $user->standard_living ?? 0 }}" readonly>
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">تاريخ الإضافة</label>
                         <input type="text" class="form-control"
                             value="{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}" readonly>
                     </div>
                     <div class="col-md-4">
                         <label class="form-label">آخر تعديل</label>
                         <input type="text" class="form-control"
                             value="{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}" readonly>
                     </div>
                 </div>
             </div>
         </div>

        @if ($user->childrens->isNotEmpty())
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">الابناء</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>#</th>
                                <th>اسم الابن</th>
                                <th>الرقم القومى</th>
                                <th>العمر</th>
                                <th>النوع</th>
                                <th>المدرسة</th>
                                <th>التكلفة الشهرية</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->childrens as $index => $child)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $child->child_name ?: '-' }}</td>
                                    <td>{{ $child->children_national_id ?: '-' }}</td>
                                    <td>{{ $child->age ?: '-' }}</td>
                                    <td>{{ $child->gender === 1 ? 'ذكر' : ($child->gender === 0 ? 'أنثى' : '-') }}</td>
                                    <td>{{ $child->school ?: '-' }}</td>
                                    <td>{{ $child->monthly_cost ?? 0 }}</td>
                                    <td>{{ $child->notes ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($patients->isNotEmpty())
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">الحالة الصحية</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>#</th>
                                <th>اسم المريض</th>
                                <th>الطبيب المعالج</th>
                                <th>نوع المريض</th>
                                <th>وسيلة صرف الدواء</th>
                                <th>هل له تأمين</th>
                                <th>الدواء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $index => $patient)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $patient->patient_name ?: '-' }}</td>
                                    <td>{{ $patient->doctor_name ?: '-' }}</td>
                                    <td>{{ $patient->type == 0 ? 'انثى' : 'ذكر' }}</td>
                                    <td>{{ $patient->treatment_pay_by ?: '-' }}</td>
                                    <td>{{ $patient->is_insurance == 0 ? 'لا' : 'نعم' }}</td>
                                    <td>{{ $patient->treatment ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">ممتلكات المتقدم وقرار اللجنة</h3>
            </div>
            <div class="card-body">
                <textarea rows="5" class="form-control" readonly>{{ $user->Case_evaluation }}</textarea>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title mb-0">المرفقات</h3>
            </div>
            <div class="card-body">
                @php
                    $attachments = is_string($user->attachments) ? json_decode($user->attachments, true) : ($user->attachments ?? []);
                @endphp

                @if (!empty($attachments))
                    <div class="d-flex flex-wrap">
                        @foreach ($attachments as $attachment)
                            <img src="{{ route('attachments.view', ['path' => $attachment]) }}" alt="Attachment"
                                class="gallery-image"
                                onclick="openModal('{{ route('attachments.view', ['path' => $attachment]) }}')">
                        @endforeach
                    </div>
                @else
                    <p class="mb-0 text-muted">لا توجد مرفقات.</p>
                @endif
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        </div>

        <div id="imageModal" class="image-modal">
            <span class="image-modal__close" onclick="closeModal()">&times;</span>
            <img class="image-modal__content" id="modalImage" alt="Attachment Preview">
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
@endsection
