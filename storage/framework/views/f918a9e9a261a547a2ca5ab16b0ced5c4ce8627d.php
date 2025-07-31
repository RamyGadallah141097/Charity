<?php $__env->startSection('title'); ?> <?php echo e($setting->title ?? ''); ?> | المستفيدين <?php $__env->stopSection(); ?>

<?php $__env->startSection('page_name'); ?> المستفيدين <?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card bg-white p-3 shadow-sm">


        <style>
            .gallery-image {
                cursor: pointer;
                transition: 0.3s;
                margin: 5px;
            }

            .gallery-image:hover {
                opacity: 0.7;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }


            .modal-content {
                margin: auto;
                display: block;
                max-width: 100%;
                max-height: 100%;
                top: 20%;
                /*    // make image in semi center */
            }


            .close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #f1f1f1;
                font-size: 40px;
                font-weight: bold;
                transition: 0.3s;
            }

            .close:hover,
            .close:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        <h3>المستفيد</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">بيانات المستفيد</h3>
                        </div>
                        <div class="card-body">
                            <h3>المستفيد</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="min-w-25px">اسم الزوج</th>
                                            <th class="min-w-25px">اسم الزوجة</th>
                                            <th class="min-w-25px">الرقم القومى للزوج </th>
                                            <th class="min-w-25px"> الرقم القومى للزوجة</th>
                                            
                                            
                                            <th class="min-w-25px">عمر الزوج</th>
                                            <th class="min-w-25px">عمر الزوجة</th>
                                            <th class="min-w-25px">الحالة الاجتماعية</th>
                                            <th class="min-w-25px">الهاتف</th>
                                            <th class="min-w-25px">نوع العمل</th>
                                            <th class="min-w-25px"> العنوان</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($user->husband_name); ?></td>
                        <td><?php echo e($user->wife_name); ?></td>
                        <td><?php echo e($user->husband_national_id); ?></td>
                        <td><?php echo e($user->wife_national_id); ?></td>
                        
                        
                        <td><?php echo e($user->age_husband); ?></td>
                        <td><?php echo e($user->age_wife); ?></td>
                        <td><?php echo e($user->social_status == 0 ? 'اعزب' : ($user->social_status == 1 ? 'متزوج' : ($user->social_status == 2 ? 'مطلق' : 'ارمل'))); ?>

                        </td>
                        <td><?php echo e($user->nearest_phone); ?></td>
                        <td><?php echo e($user->work_type); ?></td>
                        <td><?php echo e($user->address); ?></td>

                    </tr>
                </tbody>
            </table>
        </div>

        <h3> تفاصيل الدخل</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">الراتب </th>
                        <th class="min-w-25px">معاش </th>
                        <th class="min-w-25px">تامين </th>
                        <th class="min-w-25px">كرامة </th>
                        <th class="min-w-25px">تجارة </th>
                        <th class="min-w-25px">الوسائد </th>
                        <th class="min-w-25px">اخرى </th>
                        <th class="min-w-25px">اجمالى الدخل </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($user->salary); ?></td>
                        <td><?php echo e($user->pension); ?></td>
                        <td><?php echo e($user->insurance); ?></td>
                        <td><?php echo e($user->dignity); ?></td>
                        <td><?php echo e($user->trade); ?></td>
                        <td><?php echo e($user->pillows); ?></td>
                        <td><?php echo e($user->other); ?></td>
                        <td><?php echo e($user->gross_income); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3> تفاصيل النفقات</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">ايجار </th>
                        <th class="min-w-25px">غاز </th>
                        <th class="min-w-25px">دين </th>
                        <th class="min-w-25px">مياه </th>
                        <th class="min-w-25px">علاج </th>
                        <th class="min-w-25px">كهرباء </th>
                        <th class="min-w-25px">منظمة </th>
                        <th class="min-w-25px">طعام </th>
                        <th class="min-w-25px">دراسة</th>
                        <th class="min-w-25px">اجمالى النفقات </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($user->rent); ?></td>
                        <td><?php echo e($user->gas); ?></td>
                        <td><?php echo e($user->debt); ?></td>
                        <td><?php echo e($user->water); ?></td>
                        <td><?php echo e($user->treatment); ?></td>
                        <td><?php echo e($user->electricity); ?></td>
                        <td><?php echo e($user->association); ?></td>
                        <td><?php echo e($user->food); ?></td>
                        <td><?php echo e($user->study); ?></td>
                        <td><?php echo e($user->gross_expenses); ?></td>

                    </tr>
                </tbody>
            </table>
        </div>
        <h3> مستوى المعيشة</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">مستوى المعيشة </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo e($user->standard_living); ?></td>
                    </tr>
                </tbody>
            </table>


            <?php if($user->childrens): ?>
                <h3>الابناء </h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px"> #</th>
                                <th class="min-w-25px"> اسم الابن</th>
                                <th class="min-w-25px">الرقم القومى للابن</th>
                                <th class="min-w-25px">العمر </th>
                                <th class="min-w-25px">المدرسة</th>
                                <th class="min-w-25px"> التكلفة الشهرية</th>
                                <th class="min-w-25px">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $user->childrens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($boy->id); ?></td>
                                    <td><?php echo e($boy->child_name); ?></td>
                                    <td><?php echo e($boy->children_national_id); ?></td>
                                    <td><?php echo e($boy->age); ?></td>
                                    <td><?php echo e($boy->school); ?></td>
                                    <td><?php echo e($boy->monthly_cost); ?></td>
                                    <td><?php echo e($boy->notes); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if($patients): ?>
                <h3>الحالة الصحية</h3>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px"> #</th>
                                <th class="min-w-25px">اسم المريض</th>
                                <th class="min-w-20px">الطبيب المعالج</th>
                                <th class="min-w-20px">نوع المريض</th>
                                <th class="min-w-20px">وسيلة صرف الدواء</th>
                                <th class="min-w-20px">هل تأمين ؟</th>
                                <th class="min-w-20px">الدواء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($patient->id); ?></td>
                                    <td><?php echo e($patient->patient_name); ?></td>
                                    <td><?php echo e($patient->doctor_name); ?> </td>
                                    <td><?php echo e($patient->type == 0 ? 'انثى' : 'ذكر'); ?></td>
                                    <td><?php echo e($patient->treatment_pay_by); ?></td>
                                    <td><?php echo e($patient->is_insurance == 0 ? 'لا' : 'نعم'); ?></td>
                                    <td><?php echo e($patient->treatment); ?></td>
                                    <td><?php echo e(Str::limit($patient->treatment, 40)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            </div>



            <div class="card-header">
                <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                    ممتلكات المتقدم</h2>
            </div>

            <textarea rows="5" class="form-control" name="Case_evaluation" id="Case_evaluation" readonly disabled><?php echo e(old('Case_evaluation', $user->Case_evaluation)); ?></textarea>

            <div class="card-header">
                <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                    المرفقات </h2>
            </div>

            <?php if($user->attachments): ?>
                <?php
                    $attachments = is_string($user->attachments)
                        ? json_decode($user->attachments, true)
                        : $user->attachments;

                ?>

                <div class="image-gallery">
                    <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $attachment)); ?>" alt="Attachment" style="max-width: 500px"
                            height="150" class="gallery-image"
                            onclick="openModal('<?php echo e(asset('storage/' . $attachment)); ?>')">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div id="imageModal" class="modal">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <img class="modal-content" style="width: 1500px" id="modalImage">
                </div>
            <?php endif; ?>


            


            <?php if($user->patient != null): ?>
                <h3>الحالة الصحية</h3>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered w-100">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">اسم المريض</th>
                                <th class="min-w-20px">نوع المريض</th>
                                <th class="min-w-20px">الدواء</th>
                                <th class="min-w-20px">وسيلة صرف الدواء</th>
                                <th class="min-w-20px">هل تأمين ؟</th>
                                <th class="min-w-20px">الطبيب المعالج</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($user->patient->name); ?></td>
                                <td><?php echo e($user->patient->type == 1 ? 'ذكر' : 'أنثي'); ?></td>
                                <td><?php echo e($user->patient->treatment); ?></td>
                                <td><?php echo e($user->patient->treatment_pay_by); ?></td>
                                <td><?php echo e($user->patient->is_insurance == 0 ? 'لا' : 'نعم'); ?></td>
                                <td><?php echo e($user->patient->doctor_name); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            </tbody>
            </table>
        <?php $__env->stopSection(); ?>
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
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</div>

<?php echo $__env->make('admin/layouts/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/users/parts/details.blade.php ENDPATH**/ ?>