@extends('Admin/layouts/master')
@section('title')
    {{ isset($setting) ? isset($setting->title) : '' }} | الصفحة الرئيسية

@endsection
@section('page_name')
    الرئـيسية
@endsection
@section('content')
    <link href="{{ asset('assets/admin') }}/assets/plugins/morris/morris.css" rel="stylesheet" />


    <div style="display: flex; gap: 20px; flex-wrap: wrap;" class="bg-white-light mt-5 mb-9 p-5 card   ">
    <div style="display: flex; gap: 20px; flex-wrap: wrap;" class="bg-white mt-5 mb-9 p-5 card   ">

        <div class="card-header" >
            <h2 class="card-title">لوحة تقدم الافكار</h2>
        </div>

        <div class="card-body d-flex g-2">
            @foreach ($progressData as $index => $task)
                <div style="text-align: center;" class="m-3">
                    <canvas id="chart-{{ $index }}" width="120" height="120"></canvas>
                    <p>
                        {{ $task['title'] }}: {{ $task['progress'] }}%
                    </p>
                </div>
            @endforeach
        </div>
    </div>








    @if ($total_donors_money >= 1000)
        <div class="row">
            <div class="col-md-12">
                <div class="card  banner">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-2 text-center"><img
                                    src="{{ asset('assets/admin') }}/assets/images/pngs/dash5.png" alt="img"
                                    class="w-95"></div>
                            <div class="col-xl-9 col-lg-10 pl-lg-0">
                                <div class="row">
                                    <div class="col-xl-7 col-lg-6">
                                        <div class="text-right text-white mt-xl-4">
                                            <h3 class="font-weight-semibold">
                                                تهانينا يا {{ loggedAdmin('name') }}</h3>
                                            <h4 class="font-weight-normal">
                                                تخطت اجمالي التبرعات 1000 جنية
                                            </h4>
                                            <p class="mb-lg-0 text-white-50">
                                                لقد بلغت اجمالي التبرعات جنية {{ $total_donors_money }}
                                                , نأمل لمواصلة التقدم وتحقيق الهدف
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-6 text-lg-center mt-xl-4">
                                        <h5 class="font-weight-semibold mb-1 text-white"> عدد المتبرعين </h5>
                                        <h2 class="display-2 mb-3 number-font text-white">{{ $donors_count }}</h2>
                                        <div class="btn-list mb-xl-0"><a href="{{ route('donors.index') }}"
                                                class="btn btn-dark mb-xl-0">عرض التفاصيل</a>
                                            <a href="#" class="btn btn-white mb-xl-0" id="skip">
                                                لاحقا</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-md-6">
            <canvas id="usersChart"></canvas>
        </div>

        <div class="col-md-6">
            <canvas id="donorsChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <canvas id="zakatChart"></canvas>
        </div>

        <div class="col-md-6">
            <canvas id="loansChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-danger img-card box-primary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $users_count }}</h2>
                            <p class="text-white mb-0">إجمالي المستفيدين </p>
                        </div>
                        <div class="mr-auto"><i class="fe fe-users text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-secondary  img-card box-info-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{$accepedUsers}}</h2>
                            <p class="text-white mb-0">المتسفيدين المقبولين</p>
                        </div>
                        <div class="mr-auto"><i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-primary  img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"> {{ $subUsers }} </h2>
                            <p class="text-white mb-0">  المتسفيدين المعلقين</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-30 ml-2 mt-2">£L</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card  bg-success  img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $rejectedUsers }}</h2>
                            <p class="text-white mb-0">المتسفيدين المرفوضين</p>
                        </div>
                        <div class="mr-auto"><i class="fe fe-shopping-bag text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- COL END -->


{{--    total subventiosn--}}

    <div class="row">

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card  bg-warning  img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $donors_count }}</h2>
                            <p class="text-white mb-0">اجمالي المتبرعين</p>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-shopping-bag text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-info img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"> {{ $totalDonations }} </h2>
                            <p class="text-white mb-0">إجمالي التبرعات</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-30 ml-2 mt-2">£L</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-dark   img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"> {{ $totalMonthlySubventions }} </h2>
                            <p class="text-white mb-0">إجمالي الاعانات الشهرية</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-30 ml-2 mt-2">£L</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>
{{--    total subventiosn--}}




{{--    total subventiosn and zakat--}}

    <div class="row">


        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-info img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"> {{ $totalZakat }} </h2>
                            <p class="text-white mb-0">إجمالي الزكاة و الصدقات</p>
                        </div>
                        <div class="mr-auto"><i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-warning img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font"> {{ $totalMonthlySubventions }} </h2>
                            <p class="text-white mb-0">إجمالي الاعانات الشهرية</p>
                        </div>
                        <div class="mr-auto">
                            <span class="text-white fs-30 ml-2 mt-2">£L</span>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>

{{--    total subventiosn--}}



{{--        total loans --}}

        <div class="row">

            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card  bg-success img-card box-success-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="text-white">
                                <h2 class="mb-0 number-font">{{ $totalLoans }}</h2>
                                <p class="text-white mb-0">اجمالي عدد القروض</p>
                            </div>
                            <div class="mr-auto"><i class="fe fe-shopping-bag text-white fs-30 ml-2 mt-2"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card bg-primary img-card box-secondary-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="text-white">
                                <h2 class="mb-0 number-font"> {{ $totalBorrowers }} </h2>
                                <p class="text-white mb-0">إجمالي المقترضين</p>
                            </div>
                            <div class="mr-auto"><i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i></div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card bg-secondary img-card box-secondary-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="text-white">
                                <h2 class="mb-0 number-font"> {{ $totalLoansDonations }} </h2>
                                <p class="text-white mb-0">إجمالي  التبرعات </p>
                            </div>
                            <div class="mr-auto">
                                <span class="text-white fs-30 ml-2 mt-2">£L</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                <div class="card bg-danger img-card box-secondary-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="text-white">
                                <h2 class="mb-0 number-font"> {{ $totalLoanOut }} </h2>
                                <p class="text-white mb-0">إجمالي القروض الخارجه </p>
                            </div>
                            <div class="mr-auto"><i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i></div>
                        </div>
                    </div>
                </div>
            </div><!-- COL END -->
        </div>
{{--        total loans --}}





</div>

@endsection
@section('js')
    {{--    <!-- INTERNAL CHARTJS CHART JS --> --}}
    <script src="{{ asset('assets/admin') }}/assets/plugins/chart/Chart.bundle.js"></script>
    <script src="{{ asset('assets/admin') }}/assets/plugins/chart/utils.js"></script>

    <!-- INTERNAL PIETY CHART JS -->
    <script src="{{ asset('assets/admin') }}/assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="{{ asset('assets/admin') }}/assets/plugins/peitychart/peitychart.init.js"></script>

    <!-- INTERNAL MORRIS CHART JS -->
    <script src="{{ asset('assets/admin') }}/assets/plugins/morris/morris.js"></script>
    <script src="{{ asset('assets/admin') }}/assets/plugins/morris/raphael-min.js"></script>
    {{--    <!-- INTERNAL APEXCHART JS --> --}}
    <script src="{{ asset('assets/admin') }}/assets/js/apexcharts.js"></script>
    <!--INTERNAL INDEX JS-->
    <script src="{{ asset('assets/admin') }}/assets/js/index4.js"></script>
@endsection

<script>
            document.addEventListener("DOMContentLoaded", function () {
                const progressData = @json($progressData);

                progressData.forEach((task, index) => {
                    const ctx = document.getElementById(`chart-${index}`).getContext('2d');

                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [task.progress, 100 - task.progress], // Access the correct progress value
                                backgroundColor: ['#4CAF50', '#E0E0E0']
                            }]
                        },
                        options: {
                            cutout: '75%',
                            responsive: false,
                            maintainAspectRatio: false,
                        }
                    });
                });
            });
        </script>

{{--charts for users--}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('usersChart').getContext('2d');
        var usersChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["إجمالي المستفيدين", "المقبولين", "المعلقين", "المرفوضين"],
                datasets: [{
                    label: "عدد المستفيدين",
                    data: [{{ $users_count }}, {{ $accepedUsers }}, {{ $subUsers }}, {{ $rejectedUsers }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

{{--charts for donors--}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('donorsChart').getContext('2d');
        var donorsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["إجمالي المتبرعين", "إجمالي التبرعات", "إجمالي الاعانات الشهرية"],
                datasets: [{
                    label: "إحصائيات التبرعات",
                    data: [{{ $donors_count }}, {{ $totalDonations }}, {{ $totalMonthlySubventions }}],
                    backgroundColor: [
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(33, 37, 41, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(33, 37, 41, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

{{--charts for zakat--}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('zakatChart').getContext('2d');
        var zakatChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["إجمالي الزكاة والصدقات", "إجمالي الإعانات الشهرية"],
                datasets: [{
                    label: "إحصائيات الزكاة والصدقات",
                    data: [{{ $totalZakat }}, {{ $totalMonthlySubventions }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

{{--charts for loans--}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('loansChart').getContext('2d');
        var loansChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["إجمالي القروض", "إجمالي المقترضين", "إجمالي التبرعات", "إجمالي القروض الخارجة"],
                datasets: [{
                    label: "إحصائيات القروض",
                    data: [{{ $totalLoans }}, {{ $totalBorrowers }}, {{ $totalLoansDonations }}, {{ $totalLoanOut }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.5)',
                        'rgba(0, 123, 255, 0.5)',
                        'rgba(108, 117, 125, 0.5)',
                        'rgba(220, 53, 69, 0.5)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(0, 123, 255, 1)',
                        'rgba(108, 117, 125, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

