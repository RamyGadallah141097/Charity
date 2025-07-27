<div class="modal-body">

    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data"
          action="<?php echo e(route('subventions.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label class="form-label">اختيار المستفيد</label>
            <select name="user_id" class="form-control select2" required
                    data-placeholder="اختيارالمستفيد">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->wife_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>


        <div class="mt-4 ">
            <label>نوع الاعانه</label>
                <select id="sub_type" name="sub_type" class="form-control">
                    <option value=0>ماديه</option>
                    <option value=1>عينيه</option>
                </select>
        </div>


        <div class="form-group mt-4 mb-4" id="money">
                <label for="price" class="form-control-label">العينيه و قيمتها </label>
                <div class="input-group">
                    <select  class="form-select bx-outline" name="moneyType" id="asset">
                            <option value=0>زكاة مال</option>
                            <option value=1>صدقه</option>
                    </select>
                    <input type="number" value=0 class="form-control"  name="price" id="price">
                </div>
        </div>





            <div class="row mt-4 mb-4" id="subvention">
                <label for="price" class="form-control-label">العينيه و قيمتها </label>
                <div class="input-group">
                    <select  class="form-select bx-outline" name="asset_id" id="asset">

                        <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="number" value=0 class="form-control" name="asset_count" id="asset_count" placeholder="العدد">
                </div>

            </div>


        <div>
            <div class="form-group form-elements">
                <div class="form-label">نوعية الصرف</div>
                <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="once" checked>
                        <span class="custom-control-label">مرة واحدة</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="monthly">
                        <span class="custom-control-label">شهري</span>
                    </label>
                </div>
            </div>

            <div>
                <label > سبب الاعانه</label>
                <input class="form-control" name="comment">
            </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    $("document").ready(function(){
        $("#subvention").hide();
        $("select[id='sub_type']").on("change" , function(){
            let type = $(this).val();
            if(type == 1){
                $("#money").hide();
                $("#subvention").show();
            }else{
                $("#money").show();
                $("#subvention").hide();
            }
        })
    })

</script>

<?php /**PATH C:\xampp\htdocs\new-zakat\resources\views/admin/subventions/parts/create.blade.php ENDPATH**/ ?>