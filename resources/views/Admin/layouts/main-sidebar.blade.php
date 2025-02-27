<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('adminHome') }}">
            <img src="{{ get_file($setting->logo) }}" class="header-brand-img light-logo1" alt="logo">
        </a>
    </div>

    <ul class="side-menu">
        <li>
            <h3>العناصر</h3>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{ route('adminHome') }}">
                <i class="fe fe-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>

        @can('admins.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('admins.index') }}">
                    <i class="fe fe-user-check side-menu__icon"></i>
                    <span class="side-menu__label">المشرفين</span>
                </a>
            </li>
        @endcan

        {{-- المستفيدين --}}
        @can('users.index')
            <p>
                <a class="side-menu__item" data-toggle="collapse" href="#usersDropdown" role="button" aria-expanded="false">
                    <i class="fe fe-users side-menu__icon"></i>
                    <span class="side-menu__label"> المستفيدين </span>
                </a>
            </p>

            <ul class="collapse" id="usersDropdown">
                <li>
                    <a class="side-menu__item" href="{{ route('users.index', 'new') }}">
                        <i class="fe fe-user-plus side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين الجدد </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('users.index', 'accepted') }}">
                        <i class="fe fe-user-check side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين المقبولين </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('users.index', 'preparing') }}">
                        <i class="fe fe-loader side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين قيد التنفيذ </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('users.index', 'refused') }}">
                        <i class="fe fe-user-x side-menu__icon"></i>
                        <span class="side-menu__label"> المستفيدين المرفوضين </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('subventions.index') }}">
                        <i class="fe fe-credit-card side-menu__icon"></i>
                        <span class="side-menu__label"> الإعانات الشهرية للمستفيدين </span>
                    </a>
                </li>
            </ul>
        @endcan

        {{-- القروض الحسنة --}}
        @can('donors.index')
            <p>
                <a class="side-menu__item" data-toggle="collapse" href="#BorrowersDropdown" role="button" aria-expanded="false">
                    <i class="fe fe-dollar-sign side-menu__icon"></i>
                    <span class="side-menu__label"> القروض الحسنة </span>
                </a>
            </p>

            <ul class="collapse" id="BorrowersDropdown">
                <li>
                    <a class="side-menu__item" href="{{ route('safer.loans') }}">
                        <i class="fe fe-credit-card side-menu__icon"></i>
                        <span class="side-menu__label"> المقترضين </span>
                    </a>
                </li>
            </ul>
        @endcan

        {{-- الخزينة --}}
        @can('safer.index')
            <p>
                <a class="side-menu__item" data-toggle="collapse" href="#saferDropdown" role="button" aria-expanded="false">
                    <i class="fe fe-archive side-menu__icon"></i>
                    <span class="side-menu__label"> الخزانه </span>
                </a>
            </p>

            <ul class="collapse" id="saferDropdown">
                <li>
                    <a class="side-menu__item" href="{{ route('SubventionsLoans.index') }}">
                        <i class="fe fe-dollar-sign side-menu__icon"></i>
                        <span class="side-menu__label"> القروض </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('safer.loans') }}">
                        <i class="fe fe-lock side-menu__icon"></i>
                        <span class="side-menu__label"> الخزنة والمتبرعين </span>
                    </a>
                </li>
            </ul>
        @endcan

        {{-- التبرعات --}}
        @can('donors.index')
            <p>
                <a class="side-menu__item" data-toggle="collapse" href="#donationsDropdown" role="button" aria-expanded="false">
                    <i class="fe fe-gift side-menu__icon"></i>
                    <span class="side-menu__label"> التبرعات والمتبرعين </span>
                </a>
            </p>

            <ul class="collapse" id="donationsDropdown">
                <li>
                    <a class="side-menu__item" href="{{ route('donors.index') }}">
                        <i class="fe fe-user side-menu__icon"></i>
                        <span class="side-menu__label"> المتبرعين </span>
                    </a>
                </li>

                <li>
                    <a class="side-menu__item" href="{{ route('Donations.index') }}">
                        <i class="fe fe-dollar-sign side-menu__icon"></i>
                        <span class="side-menu__label"> التبرعات </span>
                    </a>
                </li>
            </ul>
        @endcan

        {{-- بنك الأفكار --}}
        @can('tasks.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('tasks.index') }}">
                    <i class="fe fe-lightbulb side-menu__icon"></i>
                    <span class="side-menu__label"> بنك الأفكار </span>
                </a>
            </li>
        @endcan

        {{-- الإعدادات --}}
        @can('setting.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('setting.index') }}">
                    <i class="fe fe-settings side-menu__icon"></i>
                    <span class="side-menu__label"> الاعدادت </span>
                </a>
            </li>
        @endcan

        {{-- الصلاحيات --}}
        @can('roles.index')
            <li class="slide">
                <a class="side-menu__item" href="{{ route('roles.index') }}">
                    <i class="fe fe-shield side-menu__icon"></i>
                    <span class="side-menu__label"> الصلاحيات </span>
                </a>
            </li>
        @endcan

        {{-- تسجيل الخروج --}}
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.logout') }}">
                <i class="fe fe-log-out side-menu__icon"></i>
                <span class="side-menu__label"> تسجيل الخروج </span>
            </a>
        </li>
    </ul>
</aside>
