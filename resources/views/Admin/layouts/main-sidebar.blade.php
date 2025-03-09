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

        @if(auth()->user()->can('admin.home'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('adminHome') }}">
                    <i class="icon icon-home side-menu__icon"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->can('admins.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admins.index') }}">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label">المشرفين</span>
                </a>
            </li>
        @endif

        {{-- المستفيدين --}}
        @if(auth()->user()->can('users.index'))
            <p>
                <a class="side-menu__item"
                   data-toggle="collapse"
                   href="#sersDropdown"
                   aria-expanded="{{ request()->segment(1) === 'users' ? 'true' : 'false' }}">
                    <i class="fas fa-hand-holding-usd" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> المستفيدين </span>
                </a>
            </p>

            <ul class="collapse {{ request()->segment(1) === 'users' ? 'show' : '' }}" id="sersDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item"
                       href="{{ route('users.index', 'new') }}">
                        <i class="fas fa-user-plus" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> المستفيدين الجدد </span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item-text side-menu__item"
                       href="{{ route('users.index', 'accepted') }}">
                        <i class="fe fe-user-check side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين المقبولين </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->is('users/index/preparing') ? 'active' : '' }}"
                       href="{{ route('users.index', 'preparing') }}">
                        <i class="fe fe-user side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين قيد التنفيذ </span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->is('users/index/refused') ? 'active' : '' }}"
                       href="{{ route('users.index', 'refused') }}">
                        <i class="fe fe-user-x side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين المرفوضين </span>
                    </a>
                </li>

            </ul>
        @endif

        {{-- التبرعات والمتبرعين --}}
        @if(auth()->user()->can('donors.index') || auth()->user()->can('Donations.index'))
            <p>
                <a class="side-menu__item"
                   data-toggle="collapse"
                   href="#donationsDropdown"
                   aria-expanded="{{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'true' : 'false' }}">
                    <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> المتبرعين والتبرعات </span>
                </a>
            </p>

            <ul class="collapse {{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'show' : '' }}" id="donationsDropdown">
                @if(auth()->user()->can('donors.index'))
                    <li>
                        <a class="dropdown-item-text side-menu__item"
                           href="{{ route('donors.index') }}">
                            <i class="fas fa-user-friends" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> المتبرعين </span>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->can('Donations.index'))
                    <li>
                        <a class="dropdown-item-text side-menu__item"
                           href="{{ route('Donations.index') }}">
                            <i class="fas fa-donate" style="margin-left: 5px;"></i>
                            <span class="side-menu__label"> التبرعات </span>
                        </a>
                    </li>
                @endif
            </ul>
        @endif

        {{--    القروض الحسنة   --}}

        <p>
            <a class="side-menu__item
       {{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'active' : '' }}"
               data-toggle="collapse"
               href="#GoodLoansDropdown" role="button"
               aria-expanded="{{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'true' : 'false' }}"
               aria-controls="GoodLoansDropdown">
                <i class="fas fa-hand-holding-usd side-menu__icon"></i>
                <span class="side-menu__label"> القروض الحسنة </span>
            </a>
        </p>

        <ul class="collapse {{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'show' : '' }}" id="GoodLoansDropdown">
            <li>
                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('indexLoansDonations') ? 'active' : '' }}"
                   href="{{ route('indexLoansDonations') }}">
                    <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> التبرعات </span>
                </a>
            </li>

            <li>
                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('borrowers.index') ? 'active' : '' }}"
                   href="{{ route('borrowers.index') }}">
                    <i class="fas fa-user" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> المقترضين </span>
                </a>
            </li>

            <li>
                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('index.Loans') ? 'active' : '' }}"
                   href="{{ route('index.Loans') }}">
                    <i class="fas fa-money-check-alt" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> القروض </span>
                </a>
            </li>
        </ul>


        {{--    القروض الحسنة   --}}



        {{--     الزكاة والصدقات   --}}
        <p>
            <a class="side-menu__item
       {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}"
               data-toggle="collapse"
               href="#CharityZakatDropdown" role="button"
               aria-expanded="{{ request()->routeIs('safer.CharityZakat') ? 'true' : 'false' }}"
               aria-controls="CharityZakatDropdown">
                <i class="fas fa-hand-holding-heart side-menu__icon"></i>
                <span class="side-menu__label"> الزكاة والصدقات </span>
            </a>
        </p>

        <ul class="collapse {{ request()->routeIs('safer.CharityZakat') ? 'show' : '' }}" id="CharityZakatDropdown">
            <li>
                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}"
                   href="{{ route('safer.CharityZakat') }}">
                    <i class="fas fa-donate" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> التبرعات </span>
                </a>
            </li>
        </ul>

        {{--     الزكاة والصدقات   --}}



        {{--     التبرعات العينية    --}}
        {{--        <ul>--}}
        {{--            <li>--}}
        {{--                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}"--}}
        {{--                   href="{{ route('safer.CharityZakat') }}">--}}
        {{--                    <i class="fas fa-box-open side-menu__icon" style="margin-left: 10px;"></i>--}}
        {{--                    <span class="side-menu__label"> التبرعات العينية </span>--}}
        {{--                </a>--}}
        {{--            </li>--}}
        {{--        </ul>--}}

        {{--     التبرعات العينية    --}}



        {{-- بنك الافكار --}}
        @if(auth()->user()->can('tasks.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('tasks.index') }}">
                    <i class="fe fe-file-text side-menu__icon"></i>
                    <span class="side-menu__label"> بنك الافكار </span>
                </a>
            </li>
        @endif




        {{-- الإعانات الشهرية --}}
        @if(auth()->user()->can('subventions.index'))
            <p>
                <a class="side-menu__item"
                   data-toggle="collapse"
                   href="#subventionsDropdown"
                   aria-expanded="{{ request()->routeIs('subventions.*') ? 'true' : 'false' }}">
                    <i class="fe fe-credit-card side-menu__icon"></i>
                    <span class="side-menu__label"> الإعانات الشهرية </span>
                </a>
            </p>

            <ul class="collapse {{ request()->routeIs('subventions.*') ? 'show' : '' }}" id="subventionsDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item"
                       href="{{ route('subventions.index') }}">
                        <i class="fe fe-users" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> الإعانات الشهرية للمستفيدين </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('assets.index') ? 'active' : '' }}"
                       href="{{ route('assets.index') }}">
                        <i class="fe fe-users" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> خزنة التبرعات العينيه  </span>
                    </a>
                </li>

            </ul>
        @endif

        {{-- الإعدادات --}}
        @if(auth()->user()->can('setting.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('setting.index') }}">
                    <i class="fe fe-settings side-menu__icon"></i>
                    <span class="side-menu__label"> الاعدادت </span>
                </a>
            </li>
        @endif

        {{-- الصلاحيات --}}
        @if(auth()->user()->can('roles.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('roles.index') }}">
                    <i class="fe fe-lock side-menu__icon"></i>
                    <span class="side-menu__label"> الصلاحيات </span>
                </a>
            </li>
        @endif

        {{-- تسجيل الخروج --}}
        @if(auth()->user()->can('admin.logout'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admin.logout') }}">
                    <i class="icon icon-lock side-menu__icon"></i>
                    <span class="side-menu__label">تسجيل الخروج</span>
                </a>
            </li>
        @endif
    </ul>

</aside>
