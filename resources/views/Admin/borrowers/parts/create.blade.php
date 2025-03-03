<div class="modal-body">
    <form id="addBorrowerForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('borrowers.store') }}">
        @csrf

        <!-- Borrower Fields -->
        <h4 class="text-primary">معلومات المقترض</h4>
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="phone" class="form-control-label">الهاتف</label>
            <input type="number" class="form-control" name="phone" id="phone" required>
        </div>

        <div class="form-group">
            <label for="nationalID" class="form-control-label">الرقم القومي</label>
            <input type="number" class="form-control" name="nationalID" id="nationalID" required>
        </div>

        <div class="form-group">
            <label for="address" class="form-control-label">العنوان</label>
            <input type="text" class="form-control" name="address" id="address" required>
        </div>

        <div class="form-group">
            <label for="job" class="form-control-label">المهنة</label>
            <input type="text" class="form-control" name="job" id="job" required>
        </div>

        <h4 class="text-primary mt-4">الضامنين</h4>
        <div id="guarantorsContainer">

        </div>


        <button type="button" class="btn btn-success mb-3" id="addGuarantor">إضافة ضامن</button>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addBorrowerButton">إضافة المقترض</button>
        </div>
    </form>
</div>

<!-- JavaScript to Handle Dynamic Form -->
<script>
    $(document).ready(function () {
        let guarantorIndex = 0; // Track guarantor count

        // Function to add a new guarantor form
        $('#addGuarantor').click(function () {
            guarantorIndex++;

            $('#guarantorsContainer').append(`
            <div class="guarantor-item border p-3 mb-2" id="guarantor_${guarantorIndex}">
                <h5 class="text-secondary">كفيل ${guarantorIndex}</h5>

                <div class="form-group">
                    <label for="guarantors[${guarantorIndex}][name]" class="form-control-label">اسم الضامن</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][name]" required>
                </div>

                <div class="form-group">
                    <label for="guarantors[${guarantorIndex}][phone]" class="form-control-label">هاتف الضامن</label>
                    <input type="number" class="form-control" name="guarantors[${guarantorIndex}][phone]" required>
                </div>

                <div class="form-group">
                    <label for="guarantors[${guarantorIndex}][nationalID]" class="form-control-label">الرقم القومي</label>
                    <input type="number" class="form-control" name="guarantors[${guarantorIndex}][nationalID]" required>
                </div>

                <div class="form-group">
                    <label for="guarantors[${guarantorIndex}][address]" class="form-control-label">العنوان</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][address]" required>
                </div>

                <div class="form-group">
                    <label for="guarantors[${guarantorIndex}][job]" class="form-control-label">المهنة</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][job]" required>
                </div>

                <button type="button" class="btn btn-danger removeGuarantor" data-id="${guarantorIndex}">حذف الضامن</button>
            </div>
        `);
        });

        // Function to remove a guarantor form
        $(document).on('click', '.removeGuarantor', function () {
            let id = $(this).data('id');
            $('#guarantor_' + id).remove();
        });
    });
</script>
