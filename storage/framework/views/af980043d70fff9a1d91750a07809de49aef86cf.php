<script>
    var loader = `
			<div class="dimmer active">
			<div class="lds-ring"><div></div><div></div><div></div><div></div></div>
			</div>
        `;
    // Show Data Using YAJRA
        async function showData(routeOfShow, columns) {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                // Save current state before destroying
                var state = table.state();
                localStorage.setItem('DataTable_state', JSON.stringify(state));
                table.destroy();
            }

            // Initialize DataTable
            table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true, // Enable DataTables state saving
                ajax: routeOfShow,
                columns: columns,
                order: [[0, "ASC"]],
                "language": {
                    "sProcessing": "جاري التحميل ..",
                    "sLengthMenu": "اظهار _MENU_ سجل",
                    "sZeroRecords": "لا يوجد نتائج",
                    "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                    "sInfoEmpty": "لا نتائج",
                    "sInfoFiltered": "للبحث",
                    "sSearch": "بحث :    ",
                    "oPaginate": {
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                    },
                    buttons: {
                        copyTitle: 'تم النسخ للحافظة <i class="fa fa-check-circle text-success"></i>',
                        copySuccess: {
                            1: "تم نسخ صف واحد",
                            _: "تم نسخ %d صفوف بنجاح"
                        },
                    }
                },
                buttons: [
                    {
                        extend: 'copy',
                        text: 'نسخ',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'excel',
                        text: 'اكسيل',
                        className: 'btn-primary'
                    },
                    {
                        extend: 'colvis',
                        text: 'عرض',
                        className: 'btn-primary'
                    },
                ],
                initComplete: function() {
                    // Load saved state if exists
                    var savedState = localStorage.getItem('DataTable_state');
                    if (savedState) {
                        var state = JSON.parse(savedState);
                        table.columns().search(state.search.search);
                        table.page(state.start / state.length).draw(false);
                    }
                }
            });

            // Save state on various events
            table.on('stateSaveParams', function(e, settings, data) {
                localStorage.setItem('DataTable_state', JSON.stringify(data));
            });
        }
    //     pdf solved successfully
    //     pdf solved successfully
    //     pdf solved successfully
    //     pdf solved successfully
    //     pdf solved successfully
    //     pdf solved successfully

    // Delete Using Ajax
    function deleteScript(routeOfDelete) {
        $(document).ready(function () {
            //Show data in the delete form
            $('#delete_modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var title = button.data('title')
                var modal = $(this)
                modal.find('.modal-body #delete_id').val(id);
                modal.find('.modal-body #title').text(title);
            });
        });
        $(document).on('click', '#delete_btn', function (event) {
            var id = $("#delete_id").val();
            $.ajax({
                type: 'POST ',
                url: routeOfDelete,
                data: {
                    '_token': "<?php echo e(csrf_token()); ?>",
                    'id': id,
                },
                success: function (data) {
                    if (data.status === 200) {
                        console.log(data);
                        $("#dismiss_delete_modal")[0].click();
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success(data.message)
                    } else {
                        $("#dismiss_delete_modal")[0].click();
                        toastr.error(data.message)
                    }
                },
                errer:function (data){
                    toastr.error(data.message)
                }
            });
        });
    }

    // show Add Modal
    function showAddModal(routeOfShow){
        $(document).on('click', '.addBtn', function () {
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')
            setTimeout(function () {
                $('#modal-body').load(routeOfShow)
            }, 250)
        });
    }

    function addScript(){
        $(document).on('submit', 'Form#addForm', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#addForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    $('#addButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">انتظر ..</span>').attr('disabled', true);
                },
                success: function (data) {
                    if (data.status == 200)
                    {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success('تم الاضافة بنجاح');
                        window.location.reload();
                        $('#editOrCreate').modal('hide')


                    } else
                        toastr.error('There is an error');
                    $('#addButton').html(`اضافة`).attr('disabled', false);
                    $('#editOrCreate').modal('hide')
                },
                error: function (data) {
                    if (data.status === 500) {
                        toastr.error('هناك خطأ ما ..');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value);
                                });
                            }
                        });
                    } else
                        toastr.error('هناك خطأ ما ..');
                    $('#addButton').html(`اضافة`).attr('disabled', false);
                },//end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    }

    function showEditModal(routeOfEdit){
        $(document).on('click', '.editBtn', function () {
            var id = $(this).data('id')
            var url = routeOfEdit;
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')

            setTimeout(function () {
                $('#modal-body').load(url)
            }, 500)
        })
    }

    function editScript(){
        $(document).on('submit', 'Form#updateForm', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var url = $('#updateForm').attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    $('#updateButton').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">انتظر ..</span>').attr('disabled', true);
                },
                success: function (data) {
                    $('#updateButton').html(`تعديل`).attr('disabled', false);
                    if (data.status == 200) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success('تم التعديل بنجاح');
                    } else
                        toastr.error('هناك خطأ ما ..');

                    $('#editOrCreate').modal('hide')
                },
                error: function (data) {
                    if (data.status === 500) {
                        toastr.error('هناك خطأ ما ..');
                    } else if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);
                        $.each(errors, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value);
                                });
                            }
                        });
                    } else
                        toastr.error('هناك خطأ ما ..');
                    $('#updateButton').html(`تعديل`).attr('disabled', false);
                },//end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });
    }


    document.addEventListener("DOMContentLoaded", function () {
    const tableWrapper = document.querySelector(".table-responsive");
    const table = document.querySelector("#dataTable");

    if (!tableWrapper || !table) return;

    const topScroll = document.createElement("div");
    topScroll.style.overflowX = "auto";
    topScroll.style.overflowY = "hidden";
    topScroll.style.height = "20px";
    topScroll.style.marginBottom = "5px";
    topScroll.style.width = "100%";
    topScroll.style.display = "none";

    const topInner = document.createElement("div");
    topInner.style.height = "1px";

    topScroll.appendChild(topInner);
    tableWrapper.parentNode.insertBefore(topScroll, tableWrapper);

    topScroll.addEventListener("scroll", function () {
        tableWrapper.scrollLeft = topScroll.scrollLeft;
    });

    tableWrapper.addEventListener("scroll", function () {
        topScroll.scrollLeft = tableWrapper.scrollLeft;
    });

    function adjustTopScroll() {
        const scrollWidth = table.scrollWidth;
        const clientWidth = tableWrapper.clientWidth;

        if (scrollWidth > clientWidth) {
            topInner.style.width = (scrollWidth + 500) + "px"; // increased scroll area
            topScroll.style.display = "block";
        } else {
            topScroll.style.display = "none";
        }
    }

    setTimeout(adjustTopScroll, 200);
    window.addEventListener("resize", adjustTopScroll);
});


</script>
<?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/layouts/myAjaxHelper.blade.php ENDPATH**/ ?>