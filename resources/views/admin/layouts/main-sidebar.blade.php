<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1">
            <img src="{{ $setting && $setting->logo ? asset($setting->logo) : asset('images/default-logo.png') }}"
                alt="logo" style="max-height: 50px; mix-blend-mode: multiply;">
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

        {{--        @can('admin.home') --}}

        <li class="slide">
            <a class="side-menu__item" href="{{ route('adminHome') }}">
                <i class="icon icon-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>
        {{--        @endcan --}}

        @if (auth()->check() && auth()->user()->can('admins.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admins.index') }}">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label">المشرفين</span>
                </a>
            </li>
        @endif

        {{-- المستفيدين --}}
        @if (auth()->check() && auth()->user()->can('users.index'))
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('users.index') || request()->routeIs('users.index.status') ? 'active' : '' }}"
                    href="{{ route('users.index') }}">
                    <i class="fas fa-hand-holding-usd" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> المستفيدين </span>
                </a>
            </li>
        @endif

        {{-- التبرعات والمتبرعين --}}
        @if (auth()->check())
            @if (auth()->user()->can('donors.index') || auth()->user()->can('Donations.index'))
                <p>
                    <a class="side-menu__item
                        {{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'active' : '' }}"
                        data-toggle="collapse" href="#donationsDropdown" role="button"
                        aria-expanded="{{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'true' : 'false' }}"
                        aria-controls="donationsDropdown">
                        <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> المتبرعين والتبرعات </span>
                    </a>
                </p>

                <ul class="collapse {{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'show' : '' }}"
                    id="donationsDropdown">
                    @if (auth()->check() && auth()->user()->can('donors.index'))
                        <li>
                            <a class="dropdown-item-text side-menu__item {{ request()->routeIs('donors.index') ? 'active' : '' }}"
                                href="{{ route('donors.index') }}">
                                <i class="fas fa-user-friends" style="margin-left: 10px;"></i>
                                <span class="side-menu__label"> المتبرعين </span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->check() && auth()->user()->can('Donations.index'))
                        <li>
                            <a class="dropdown-item-text side-menu__item {{ request()->routeIs('Donations.index') ? 'active' : '' }}"
                                href="{{ route('Donations.index') }}">
                                <i class="fas fa-donate" style="margin-left: 5px;"></i>
                                <span class="side-menu__label"> التبرعات </span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif
        @endif

        @php
            $associationLockerId = \App\Models\DonationType::query()->where('code', \App\Models\DonationType::ASSOCIATION_CODE)->value('id');
            $associationMenuOpen = request()->routeIs('association-expenses.*')
                || request()->routeIs('association-revenues.*')
                || (request()->routeIs('lock') && request('locker_type') == $associationLockerId)
                || (request()->segment(2) === 'lock' && request()->segment(3) == $associationLockerId);
        @endphp
        {{--    القروض الحسنة   --}}

        @if (auth()->check())
            @if (auth()->user()->can('goodLoans.index') || auth()->user()->can('borrower.index'))
                <p>
                    <a class="side-menu__item
            {{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'active' : '' }}"
                        data-toggle="collapse" href="#GoodLoansDropdown" role="button"
                        aria-expanded="{{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'true' : 'false' }}"
                        aria-controls="GoodLoansDropdown">
                        <i class="fas fa-hand-holding-usd side-menu__icon"></i>
                        <span class="side-menu__label"> القروض الحسنة </span>
                    </a>
                </p>

                <ul class="collapse {{ request()->routeIs('indexLoansDonations') || request()->routeIs('borrowers.index') || request()->routeIs('index.Loans') ? 'show' : '' }}"
                    id="GoodLoansDropdown">

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
            @endif
        @endif

        {{--    القروض الحسنة   --}}

        {{--     الزكاة والصدقات   --}}
        @if (auth()->check() && auth()->user()->can('zakat.index'))
            {{-- <p>
                <a class="side-menu__item
       {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}"
                    data-toggle="collapse" href="#CharityZakatDropdown" role="button"
                    aria-expanded="{{ request()->routeIs('safer.CharityZakat') ? 'true' : 'false' }}"
                    aria-controls="CharityZakatDropdown">
                    <i class="fas fa-hand-holding-heart side-menu__icon"></i>
                    <span class="side-menu__label"> الزكاة والصدقات </span>
                </a>
            </p> --}}

            <ul class="collapse {{ request()->routeIs('safer.CharityZakat') ? 'show' : '' }}"
                id="CharityZakatDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}"
                        href="{{ route('safer.CharityZakat') }}">
                        <i class="fas fa-donate" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> التبرعات </span>
                    </a>
                </li>
            </ul>
        @endif
        {{--     الزكاة والصدقات   --}}

        {{--     التبرعات العينية    --}}
        {{--        <ul> --}}
        {{--            <li> --}}
        {{--                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('safer.CharityZakat') ? 'active' : '' }}" --}}
        {{--                   href="{{ route('safer.CharityZakat') }}"> --}}
        {{--                    <i class="fas fa-box-open side-menu__icon" style="margin-left: 10px;"></i> --}}
        {{--                    <span class="side-menu__label"> التبرعات العينية </span> --}}
        {{--                </a> --}}
        {{--            </li> --}}
        {{--        </ul> --}}

        {{--     التبرعات العينية    --}}






        {{-- الإعانات --}}
        @if (auth()->check() && auth()->user()->can('subventions.index'))
            <p>
                <a class="side-menu__item {{ request()->routeIs('subventions.*') ? 'active' : '' }} {{ request()->routeIs('SubventionsLoans.*') ? 'active' : '' }} {{ request()->routeIs('in-kind-disbursements.*') ? 'active' : '' }}"
                    data-toggle="collapse" href="#subventionsDropdown" role="button"
                    aria-expanded="{{ request()->routeIs('subventions.*') || request()->routeIs('SubventionsLoans.*') || request()->routeIs('in-kind-disbursements.*') ? 'true' : 'false' }}"
                    aria-controls="subventionsDropdown">
                    <i class="fe fe-credit-card side-menu__icon"></i>
                    <span class="side-menu__label"> الإعانات </span>
                </a>
            </p>


            <ul class="collapse {{ request()->routeIs('subventions.*') || request()->routeIs('SubventionsLoans.*') || request()->routeIs('in-kind-disbursements.*') ? 'show' : '' }}"
                id="subventionsDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('subventions.index') ? 'active' : '' }}"
                        href="{{ route('subventions.index') }}">
                        <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> الإعانات الشهرية </span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('SubventionsLoans.index') ? 'active' : '' }}"
                        href="{{ route('SubventionsLoans.index') }}">
                        <i class="fas fa-user-friends" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> الإعانات الفردية </span>
                    </a>
                </li>

                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('in-kind-disbursements.*') ? 'active' : '' }}"
                        href="{{ route('in-kind-disbursements.index') }}">
                        <i class="fas fa-box-open" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> صرف التبرعات العينية </span>
                    </a>
                </li>
            </ul>
        @endif

        @if (auth()->check() && auth()->user()->can('lock.index'))
            <p>
                <a class="side-menu__item {{ $associationMenuOpen ? 'active' : '' }}"
                    data-toggle="collapse" href="#associationDropdown" role="button"
                    aria-expanded="{{ $associationMenuOpen ? 'true' : 'false' }}"
                    aria-controls="associationDropdown">
                    <i class="fas fa-building" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> الجمعية </span>
                </a>
            </p>

            <ul class="collapse {{ $associationMenuOpen ? 'show' : '' }}" id="associationDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item {{ (request()->routeIs('lock') && request('locker_type') == $associationLockerId) || (request()->segment(2) === 'lock' && request()->segment(3) == $associationLockerId) ? 'active' : '' }}"
                        href="{{ $associationLockerId ? route('lock', ['lock' => $associationLockerId]) : route('lock') }}">
                        <i class="fas fa-wallet" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> خزنة الجمعية </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('association-expenses.*') ? 'active' : '' }}"
                        href="{{ route('association-expenses.index') }}">
                        <i class="fas fa-file-invoice-dollar" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> المصروفات </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('association-revenues.*') ? 'active' : '' }}"
                        href="{{ route('association-revenues.index') }}">
                        <i class="fas fa-coins" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> الإيرادات </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('references.index') && request()->route('type') === 'expense-types' ? 'active' : '' }}"
                        href="{{ route('references.index', 'expense-types') }}">
                        <i class="fas fa-tags" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> أنواع المصروفات </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('references.index') && request()->route('type') === 'revenue-types' ? 'active' : '' }}"
                        href="{{ route('references.index', 'revenue-types') }}">
                        <i class="fas fa-layer-group" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> أنواع الإيرادات </span>
                    </a>
                </li>
            </ul>
        @endif


        {{-- الإعدادات --}}
        @if (auth()->check() && auth()->user()->can('setting.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('setting.index') }}">
                    <i class="fe fe-settings side-menu__icon"></i>
                    <span class="side-menu__label"> الاعدادت </span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('references.dashboard') }}">
                    <i class="fe fe-database side-menu__icon"></i>
                    <span class="side-menu__label"> التعريفات العامة </span>
                </a>
            </li>
        @endif

        {{-- الصلاحيات --}}
        {{--        @can('roles.index') --}}
        {{--            <li class="slide"> --}}
        {{--                <a class="side-menu__item" href="{{ route('roles.index') }}"> --}}
        {{--                    <i class="fe fe-lock side-menu__icon"></i> --}}
        {{--                    <span class="side-menu__label"> الصلاحيات </span> --}}
        {{--                </a> --}}
        {{--            </li> --}}
        {{--        @endcan --}}



        @if (auth()->check() && auth()->user()->can('roles.index'))
            <li class="slide">
                <a class="side-menu__item" href="{{ route('roles.index') }}">
                    <i class="fe fe-lock side-menu__icon"></i>
                    <span class="side-menu__label"> الصلاحيات </span>
                </a>
            </li>
        @endif



        {{-- تسجيل الخروج --}}
        {{--        @if (auth()->check() && auth()->user()->can('admin.logout')) --}}
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.logout') }}">
                <i class="icon icon-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>
        {{--        @endif --}}
    </ul>

</aside>
