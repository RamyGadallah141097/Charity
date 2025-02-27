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
        @can('users.index')
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


                <li>
                    <a class="dropdown-item-text side-menu__item" href="{{ route('subventions.index') }}">
                        <i class="fe fe-credit-card side-menu__icon"></i>
                        <span class="side-menu__label"> الاعانات الشهرية للمستفيدين </span>
                    </a>
                </li>
            @endcan

        </ul>
        {{-- المستفيدين --}}

        {{-- ____________________________________________________ --}}


        {{-- المقترضين --}}
        {{--  التبرعات والمتبرعين  --}}

        @can('donors.index')
            @can('Donations.index')
                <p>
                    <a class="side-menu__item" data-toggle="collapse" href="#BorrowersDropdown" role="button"
                        aria-expanded="false" aria-controls="collapseExample">
                        <i class="fe fe-credit-card side-menu__icon"></i> <!-- استخدام أيقونة القروض -->
                        <span class="side-menu__label"> القروض الحسنة </span>
                    </a>
                </p>

                <ul class="collapse" id="BorrowersDropdown">
                    <ul class="collapse" id="donationsDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('safer.loans') }}">
                                <i class="fe fe-user-check side-menu__icon"></i> <!-- أيقونة تعبيرية عن المقترضين -->
                                <span class="side-menu__label"> المقترضين </span>
                            </a>
                        </li>
                    </ul>


                    <ul class="collapse" id="BorrowersDropdown">
                    @endcan
                @endcan


                @can('safer.index')
                    <p>
                        <a class="side-menu__item" data-toggle="collapse" href="#saferDropdown" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fe fe-users side-menu__icon"></i>
                            <span class="side-menu__label"> الخزانه </span>
                        </a>
                    </p>
                    <ul class="collapse" id="saferDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('SubventionsLoans.index') }}">
                                <i class="fe fe-dollar-sign side-menu__icon"></i> <!-- أيقونة تعبيرية عن القروض أو المال -->
                                <span class="side-menu__label"> القروض </span>
                            </a>
                        </li>
                    </ul>

                    <ul class="collapse" id="BorrowersDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('safer.loans') }}">
                                <i class="fe fe-lock side-menu__icon"></i>
                                <span class="side-menu__label"> الخزنة والمتبرعين </span>
                            </a>
                        </li>
                    </ul>
                    {{-- ____________________________________________________ --}}

                    {{-- الصدقات والزكاة --}}
                    <p>
                        <a class="side-menu__item" data-toggle="collapse" href="#CharityAndZakatDropdown" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fe fe-credit-card side-menu__icon"></i> <!-- استخدام أيقونة القروض -->
                            <span class="side-menu__label"> التبرعات والمتبرعين </span>
                        </a>
                    </p>

                    <ul class="collapse" id="CharityAndZakatDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('donors.index') }}">
                                <span class="side-menu__label"> المتبرعين </span>
                            </a>
                        </li>
                    </ul>

                    <ul class="collapse" id="CharityAndZakatDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('Donations.index') }}">
                                <span class="side-menu__label"> التبرعات </span>
                            </a>
                        </li>
                    </ul>

                    {{-- ____________________________________________________ --}}

                    <p>
                        <a class="side-menu__item" data-toggle="collapse" href="#saferDropdown" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                            <i class="fe fe-users side-menu__icon"></i>
                            <span class="side-menu__label"> الخزنة </span>
                        </a>
                    </p>

                    <ul class="collapse" id="saferDropdown">
                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('safer.charity') }}">
                                <span class="side-menu__label"> الصدقات </span>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('safer.Zakat') }}">
                                <span class="side-menu__label"> الزكات </span>
                            </a>
                        </li>


                        <li>
                            <a class="dropdown-item-text side-menu__item" href="{{ route('safer.InKindDonations') }}">
                                <span class="side-menu__label"> تبرعات عينية </span>
                            </a>
                        </li>

                    </ul>
                @endcan

                @can('tasks.index')
                    {{-- بنك الافكار  --}}
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('tasks.index') }}">
                            <i class="fe fe-file-text side-menu__icon"></i>
                            <span class="side-menu__label"> بنك الافكار </span>
                        </a>
                    </li>
                @endcan


                @can('research.index')
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('research.index') }}">
                            <i class="fe fe-file-text side-menu__icon"></i>
                            <span class="side-menu__label"> طباعة بحث اجتماعي </span>
                        </a>
                    </li>
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('research.receive') }}">
                            <i class="fe fe-printer side-menu__icon"></i>
                            <span class="side-menu__label"> طباعة اقرار استلام </span>
                        </a>
                    </li>
                @endcan

                @can('setting.index')
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('setting.index') }}">
                            <i class="fe fe-settings side-menu__icon"></i>
                            <span class="side-menu__label"> الاعدادت </span>
                        </a>
                    </li>
                @endcan


                @can('subventions.index')
                    <li class="slide">
                        <a class="side-menu__item" href="{{ route('subventions.index') }}">
                            <i class="fe fe-dollar-sign side-menu__icon"></i>
                            <span class="side-menu__label"> الإعانات </span>
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
