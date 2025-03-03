<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">


        <a class="header-brand1">
            <img src="{{ get_file($setting->logo) }}" class="header-brand-img light-logo1" alt="logo">
            <a class="header-brand1" href="{{ route('adminHome') }}">

                <a class="header-brand1">
                    <a class="header-brand1" href="{{ route('adminHome') }}">

                    </a>
                </a>
            </a>
        </a>
        <!-- LOGO -->
    </div>

    <ul class="side-menu">
        <li>
            <h3>العناصر</h3>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{ route('adminHome') }}">
                <i class="icon icon-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>
        @can('admins.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admins.index') }}">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label">المشرفين</span>
                </a>
            </li>
        @endcan


        {{-- المستفيدين --}}
        <p>
            <a class="side-menu__item" data-toggle="collapse" href="#sersDropdown" role="button" aria-expanded="false"
                aria-controls="collapseExample">
                <i class="fe fe-users side-menu__icon"></i>
                <span class="side-menu__label"> المستفيدين </span>
            </a>
        </p>
        <ul class="collapse" id="sersDropdown">
            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('users.index', 'new') }}">
                    <i class="fe fe-user-plus side-menu__icon"></i>
                    <span class="side-menu__label"> المستفيدين الجدد </span>
                </a>
            </li>

            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('users.index', 'accepted') }}">
                    <i class="fe fe-user-check side-menu__icon"></i>
                    <span class="side-menu__label"> المستفيدين المقبولين </span>
                </a>
            </li>

            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('users.index', 'preparing') }}">
                    <i class="fe fe-user side-menu__icon"></i>
                    <span class="side-menu__label"> المستفيدين قيد التنفيذ </span>
                </a>
            </li>
            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('users.index', 'refused') }}">
                    <i class="fe fe-user-x side-menu__icon"></i>
                    <span class="side-menu__label"> المستفيدين المرفوضين </span>
                </a>
            </li>
        </ul>
        {{-- المستفيدين --}}


        {{--   التبرعات والمتبرعين   --}}
        @can('donors.index')
            @can('Donations.index')
                <p>
                    <a class="side-menu__item" data-toggle="collapse" href="#donationsDropdown" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <i class="fe fe-users side-menu__icon"></i>
                        <span class="side-menu__label"> المتبرعين والتبرعات </span>
                    </a>
                </p>

                <ul class="collapse" id="donationsDropdown">
                    <li>
                        <a class="dropdown-item-text side-menu__item" href="{{ route('donors.index') }}">
                            <span class="side-menu__label"> المتبرعين </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-text side-menu__item" href="{{ route('Donations.index') }}">
                            <span class="side-menu__label"> التبرعات </span>
                        </a>
                    </li>
                </ul>
            @endcan
        @endcan
        {{--   التبرعات والمتبرعين   --}}



        {{--    القروض الحسنة   --}}
        @can('donors.index')
            @can('Donations.index')
                <p>
                    <a class="side-menu__item" data-toggle="collapse" href="#GoodLoansDropdown" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <i class="fe fe-users side-menu__icon"></i>
                        <span class="side-menu__label"> القروض الحسنة </span>
                    </a>
                </p>

                <ul class="collapse" id="GoodLoansDropdown">
                    <li>
                        <a class="dropdown-item-text side-menu__item" href="{{ route('indexLoansDonations') }}">
                            <span class="side-menu__label"> التبرعات </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-text side-menu__item" href="{{route("borrowers.index")}}">
                            <span class="side-menu__label"> المقترضين </span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item-text side-menu__item" href="#">
                            <span class="side-menu__label"> القروض </span>
                        </a>
                    </li>

                </ul>
            @endcan
        @endcan
        {{--    القروض الحسنة   --}}


        {{--     الزكاة والصدقات   --}}
        <p>
            <a class="side-menu__item" data-toggle="collapse" href="#CharityZakatDropdown" role="button"
                aria-expanded="false" aria-controls="collapseExample">
                <i class="fe fe-users side-menu__icon"></i>
                <span class="side-menu__label"> الزكاة والصدقات </span>
            </a>
        </p>

        <ul class="collapse" id="CharityZakatDropdown">
            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('safer.CharityZakat') }}">
                    <span class="side-menu__label"> التبرعات </span>
                </a>
            </li>

        </ul>
        {{--     الزكاة والصدقات   --}}

        {{--     التبرعات العينية    --}}
        <ul>
            <li>
                <a class="dropdown-item-text side-menu__item" href="{{ route('safer.CharityZakat') }}">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label"> التبرعات العينية </span>
                </a>
            </li>
        </ul>
        {{--     التبرعات العينية    --}}








        {{-- بنك الافكار  --}}
        <li class="slide">
            <a class="side-menu__item" href="{{ route('tasks.index') }}">
                <i class="fe fe-file-text side-menu__icon"></i>
                <span class="side-menu__label"> بنك الافكار </span>
            </a>
        </li>
        <li>
            <a class="dropdown-item-text side-menu__item" href="{{ route('subventions.index') }}">
                <i class="fe fe-credit-card side-menu__icon"></i>
                <span class="side-menu__label"> الاعانات الشهرية للمستفيدين </span>
            </a>
        </li>
        {{-- @endcan --}}
    </ul>
    <ul>


        @can('setting.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('setting.index') }}">
                    <i class="fe fe-settings side-menu__icon"></i>
                    <span class="side-menu__label"> الاعدادت </span>
                </a>
            </li>
        @endcan

        @can('roles.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('roles.index') }}">
                    <i class="fe fe-dollar-sign side-menu__icon"></i>
                    <span class="side-menu__label"> الصلاحيات </span>
                </a>
            </li>
        @endcan

        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.logout') }}">
                <i class="icon icon-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>
    </ul>
</aside>
