<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('adminHome') }}">
            <img src="{{ $setting && $setting->logo ? asset($setting->logo) : asset('images/default-logo.png') }}"
                alt="logo" style="max-height: 50px; mix-blend-mode: multiply;">
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

        @if (auth()->check())
            @php
                $hasLegacyResearchPermission = auth()->user()->can('research.index');
                $canCaseResearchIndex = $hasLegacyResearchPermission || auth()->user()->can('case-research.index');
                $canManageResearchers = auth()->user()->can('case-research.manage-researchers');
                $canCaseResearchers = auth()->user()->can('case-research.researchers.index');
                $canCaseResearchWorkload = auth()->user()->can('case-research.workload.index');
                $canShowCaseResearchMenu = $canCaseResearchIndex || ($canManageResearchers && ($canCaseResearchers || $canCaseResearchWorkload));
                $caseResearchOpen = request()->routeIs('case-research.index')
                    || request()->routeIs('case-research.create')
                    || request()->routeIs('case-research.edit')
                    || request()->routeIs('case-research.researchers*')
                    || request()->routeIs('case-research.workload');
            @endphp
            @if ($canShowCaseResearchMenu)
                <li class="slide">
                    <a class="side-menu__item {{ $caseResearchOpen ? 'active' : '' }}"
                        data-toggle="collapse" href="#caseResearchDropdown" role="button"
                        aria-expanded="{{ $caseResearchOpen ? 'true' : 'false' }}"
                        aria-controls="caseResearchDropdown">
                        <i class="fas fa-sitemap" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> إدارة الباحثين والأبحاث </span>
                    </a>
                    <ul class="collapse {{ $caseResearchOpen ? 'show' : '' }}" id="caseResearchDropdown">
                        @if ($canCaseResearchIndex)
                            <li>
                                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('case-research.index') || request()->routeIs('case-research.create') || request()->routeIs('case-research.edit') ? 'active' : '' }}"
                                    href="{{ route('case-research.index') }}">
                                    <i class="fas fa-clipboard-check" style="margin-left: 10px;"></i>
                                    <span class="side-menu__label"> الحالات قيد البحث </span>
                                </a>
                            </li>
                        @endif
                        @if ($canCaseResearchers)
                            <li>
                                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('case-research.researchers*') ? 'active' : '' }}"
                                    href="{{ route('case-research.researchers') }}">
                                    <i class="fas fa-user-tie" style="margin-left: 10px;"></i>
                                    <span class="side-menu__label"> الباحثون </span>
                                </a>
                            </li>
                        @endif
                        @if ($canCaseResearchWorkload)
                            <li>
                                <a class="dropdown-item-text side-menu__item {{ request()->routeIs('case-research.workload') ? 'active' : '' }}"
                                    href="{{ route('case-research.workload') }}">
                                    <i class="fas fa-chart-line" style="margin-left: 10px;"></i>
                                    <span class="side-menu__label"> عبء العمل </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        @endif

        {{-- التبرعات والمتبرعين --}}
        @if (auth()->check())
            @if (auth()->user()->can('donors.index') || auth()->user()->can('Donations.index'))
                <li class="slide">
                    <a class="side-menu__item
                        {{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'active' : '' }}"
                        data-toggle="collapse" href="#donationsDropdown" role="button"
                        aria-expanded="{{ request()->routeIs('donors.index') || request()->routeIs('Donations.index') ? 'true' : 'false' }}"
                        aria-controls="donationsDropdown">
                        <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> المتبرعين والتبرعات </span>
                    </a>
                </li>

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
            $associationMenuOpen = request()->routeIs('association-expenses.*')
                || request()->routeIs('association-revenues.*')
                || (request()->routeIs('references.index') && in_array(request()->route('type'), ['expense-types', 'revenue-types'], true));
        @endphp
        {{--    القروض الحسنة   --}}

        @if (auth()->check())
            @php
                $canBorrowers = auth()->user()->can('borrower.index');
                $canLoans = auth()->user()->can('goodLoans.index');
                $goodLoansOpen = request()->routeIs('borrowers.*')
                    || request()->routeIs('index.Loans')
                    || request()->routeIs('person.loans')
                    || request()->routeIs('indexLoansDonations');
            @endphp
            @if ($canBorrowers || $canLoans)
                <li class="slide">
                    <a class="side-menu__item
            {{ $goodLoansOpen ? 'active' : '' }}"
                        data-toggle="collapse" href="#GoodLoansDropdown" role="button"
                        aria-expanded="{{ $goodLoansOpen ? 'true' : 'false' }}"
                        aria-controls="GoodLoansDropdown">
                        <i class="fas fa-hand-holding-usd side-menu__icon"></i>
                        <span class="side-menu__label"> القروض الحسنة </span>
                    </a>
                </li>

                <ul class="collapse {{ $goodLoansOpen ? 'show' : '' }}"
                    id="GoodLoansDropdown">
                    @if ($canBorrowers)
                        <li>
                            <a class="dropdown-item-text side-menu__item {{ request()->routeIs('borrowers.*') ? 'active' : '' }}"
                                href="{{ route('borrowers.index') }}">
                                <i class="fas fa-user" style="margin-left: 10px;"></i>
                                <span class="side-menu__label"> المقترضين </span>
                            </a>
                        </li>
                    @endif

                    @if ($canLoans)
                        <li>
                            <a class="dropdown-item-text side-menu__item {{ request()->routeIs('index.Loans') || request()->routeIs('person.loans') || request()->routeIs('indexLoansDonations') ? 'active' : '' }}"
                                href="{{ route('index.Loans') }}">
                                <i class="fas fa-money-check-alt" style="margin-left: 10px;"></i>
                                <span class="side-menu__label"> القروض </span>
                            </a>
                        </li>
                    @endif
                </ul>
            @endif
        @endif

        {{--    القروض الحسنة   --}}

        {{-- الإعانات --}}
        @if (auth()->check())
            @php
                $canMonthlySubventions = auth()->user()->can('subventions.index');
                $canSingleSubventions = auth()->user()->can('SubventionsLoans.index');
                $canInKindDisbursements = auth()->user()->can('in-kind-disbursements.index');
                $subventionsOpen = request()->routeIs('subventions.*')
                    || request()->routeIs('SubventionsLoans.*')
                    || request()->routeIs('in-kind-disbursements.*');
            @endphp
        @if ($canMonthlySubventions || $canSingleSubventions || $canInKindDisbursements)
            <li class="slide">
                <a class="side-menu__item {{ $subventionsOpen ? 'active' : '' }}"
                    data-toggle="collapse" href="#subventionsDropdown" role="button"
                    aria-expanded="{{ $subventionsOpen ? 'true' : 'false' }}"
                    aria-controls="subventionsDropdown">
                    <i class="fe fe-credit-card side-menu__icon"></i>
                    <span class="side-menu__label"> الإعانات </span>
                </a>
            </li>


            <ul class="collapse {{ $subventionsOpen ? 'show' : '' }}"
                id="subventionsDropdown">
                @if ($canMonthlySubventions)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('subventions.*') ? 'active' : '' }}"
                            href="{{ route('subventions.index') }}">
                            <i class="fas fa-hand-holding-heart" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> الإعانات الشهرية </span>
                        </a>
                    </li>
                @endif

                @if ($canSingleSubventions)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('SubventionsLoans.*') ? 'active' : '' }}"
                            href="{{ route('SubventionsLoans.index') }}">
                            <i class="fas fa-user-friends" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> الإعانات الفردية </span>
                        </a>
                    </li>
                @endif

                @if ($canInKindDisbursements)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('in-kind-disbursements.*') ? 'active' : '' }}"
                            href="{{ route('in-kind-disbursements.index') }}">
                            <i class="fas fa-box-open" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> صرف التبرعات العينية </span>
                        </a>
                    </li>
                @endif
            </ul>
        @endif
        @endif

        @if (auth()->check() && auth()->user()->can('lock.index'))
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('lock') ? 'active' : '' }}" href="{{ route('lock') }}">
                    <i class="fas fa-wallet" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> الخزنة </span>
                </a>
            </li>
        @endif

        @if (auth()->check())
            @php
                $reportsOpen = request()->routeIs('reports.*');
            @endphp
            <li class="slide">
                <a class="side-menu__item {{ $reportsOpen ? 'active' : '' }}"
                    data-toggle="collapse" href="#reportsDropdown" role="button"
                    aria-expanded="{{ $reportsOpen ? 'true' : 'false' }}"
                    aria-controls="reportsDropdown">
                    <i class="fas fa-chart-bar" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> التقارير والاستعلامات </span>
                </a>
            </li>

            <ul class="collapse {{ $reportsOpen ? 'show' : '' }}" id="reportsDropdown">
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.incoming-donations') ? 'active' : '' }}"
                        href="{{ route('reports.incoming-donations') }}">
                        <i class="fas fa-arrow-down" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> التبرعات الواردة </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.outgoing-donations') ? 'active' : '' }}"
                        href="{{ route('reports.outgoing-donations') }}">
                        <i class="fas fa-arrow-up" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> التبرعات المنصرفة </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.expenses') ? 'active' : '' }}"
                        href="{{ route('reports.expenses') }}">
                        <i class="fas fa-file-invoice-dollar" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> تقارير المصروفات </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.comparison') ? 'active' : '' }}"
                        href="{{ route('reports.comparison') }}">
                        <i class="fas fa-balance-scale" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> تقارير المقارنة </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.beneficiaries') ? 'active' : '' }}"
                        href="{{ route('reports.beneficiaries') }}">
                        <i class="fas fa-users" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> تقارير المستفيدين </span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item-text side-menu__item {{ request()->routeIs('reports.case-research') ? 'active' : '' }}"
                        href="{{ route('reports.case-research') }}">
                        <i class="fas fa-search" style="margin-left: 10px;"></i>
                        <span class="side-menu__label"> تقارير بحث الحالات </span>
                    </a>
                </li>
            </ul>
        @endif

        @if (auth()->check())
            @php
                $canAssociationExpenses = auth()->user()->can('association.expenses.index');
                $canAssociationRevenues = auth()->user()->can('association.revenues.index');
                $canExpenseTypes = auth()->user()->can('references.index');
                $canRevenueTypes = auth()->user()->can('references.index');
            @endphp
        @if ($canAssociationExpenses || $canAssociationRevenues || $canExpenseTypes || $canRevenueTypes)
            <li class="slide">
                <a class="side-menu__item {{ $associationMenuOpen ? 'active' : '' }}"
                    data-toggle="collapse" href="#associationDropdown" role="button"
                    aria-expanded="{{ $associationMenuOpen ? 'true' : 'false' }}"
                    aria-controls="associationDropdown">
                    <i class="fas fa-building" style="margin-left: 10px;"></i>
                    <span class="side-menu__label"> الجمعية </span>
                </a>
            </li>

            <ul class="collapse {{ $associationMenuOpen ? 'show' : '' }}" id="associationDropdown">
                @if ($canAssociationExpenses)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('association-expenses.*') ? 'active' : '' }}"
                            href="{{ route('association-expenses.index') }}">
                            <i class="fas fa-file-invoice-dollar" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> المصروفات </span>
                        </a>
                    </li>
                @endif
                @if ($canAssociationRevenues)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('association-revenues.*') ? 'active' : '' }}"
                            href="{{ route('association-revenues.index') }}">
                            <i class="fas fa-coins" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> الإيرادات </span>
                        </a>
                    </li>
                @endif
                @if ($canExpenseTypes)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('references.index') && request()->route('type') === 'expense-types' ? 'active' : '' }}"
                            href="{{ route('references.index', 'expense-types') }}">
                            <i class="fas fa-tags" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> أنواع المصروفات </span>
                        </a>
                    </li>
                @endif
                @if ($canRevenueTypes)
                    <li>
                        <a class="dropdown-item-text side-menu__item {{ request()->routeIs('references.index') && request()->route('type') === 'revenue-types' ? 'active' : '' }}"
                            href="{{ route('references.index', 'revenue-types') }}">
                            <i class="fas fa-layer-group" style="margin-left: 10px;"></i>
                            <span class="side-menu__label"> أنواع الإيرادات </span>
                        </a>
                    </li>
                @endif
            </ul>
        @endif
        @endif


        {{-- الإعدادات --}}
        @if (auth()->check() && auth()->user()->can('setting.index'))
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('setting.index') ? 'active' : '' }}" href="{{ route('setting.index') }}">
                    <i class="fe fe-settings side-menu__icon"></i>
                    <span class="side-menu__label"> الاعدادت </span>
                </a>
            </li>
        @endif
        @if (auth()->check() && auth()->user()->can('references.dashboard'))
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('references.dashboard') ? 'active' : '' }}" href="{{ route('references.dashboard') }}">
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
                <a class="side-menu__item {{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
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
