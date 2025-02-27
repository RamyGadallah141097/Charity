@extends('Admin.layouts.master')

@section('title')
    {{ $setting->title ?? '' }} | المستفيدين
@endsection

@section('page_name')
    المستفيدين
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        قائمة بالمستفدين من {{ $setting->title ?? '' }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">اسم المستفيد</th>
                                 <th class="min-w-50px">اجمالي الصدقات</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.layouts.myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        var columns = [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            // Uncomment if required
            { data: 'zakahTotal', name: 'zakahTotal' },

        ];

        // Fixing the Syntax Error
        showData('{{ route('DonationDetails', $id) }}', columns);

        // Uncomment if delete function is needed
        // deleteScript('{{ route('delete_users') }}');

        // Show Details Modal
        $(document).on('click', '.detailsBtn', function() {
            var id = $(this).data('id');
            var url = "{{ route('userDetails', ':id') }}".replace(':id', id);

            $('#modal-body').html(loader);
            $('#editOrCreate').modal('show');

            setTimeout(function() {
                $('#modal-body').load(url);
            }, 500);
        });
    </script>
@endsection
