<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="<?php echo e(route('tasks.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="title" class="form-control-label" > العنوان</label>
            <input type="text"  class="form-control" name="title"   id="title">
        </div>
        <div class="form-group">
            <label for="description" class="form-control-label" > الوصف</label>
            <textarea type="text"  class="form-control" name="description"   id="description"></textarea>
        </div>
        <div class="form-group">
            <label for="from_date" class="form-control-label" > وقت البدأ</label>
            <input type="date"  value="<?php echo e(\Carbon\Carbon::now()->format("Y-m-d")); ?>"  class="form-control" name="from_date"   id="from_date">
        </div>
        <div class="form-group">
            <label for="to_date" class="form-control-label" > وقت الانتهاء</label>
            <input type="date"   class="form-control" name="to_date"   id="to_date">
        </div>
















        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
<?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/task/parts/create.blade.php ENDPATH**/ ?>