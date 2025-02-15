<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{route('adminHome')}}">
            <img src="{{get_file($setting->logo)}}" class="header-brand-img light-logo1" alt="logo">
        </a>
        <!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li><h3>العناصر</h3></li>
        <li class="slide">
            <a class="side-menu__item" href="{{route('adminHome')}}">
                <i class="icon icon-home side-menu__icon"></i>
                <span class="side-menu__label">الرئيسية</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('admins.index')}}">
                <i class="fe fe-users side-menu__icon"></i>
                <span class="side-menu__label">المشرفين</span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('users.index','new')}}">
                <i class="fe fe-user-plus side-menu__icon"></i>
                <span class="side-menu__label">المستفيدين الجدد </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('users.index','preparing')}}">
                <i class="fe fe-user side-menu__icon"></i>
                <span class="side-menu__label">المستفيدين قيد التنفيذ </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('users.index','accepted')}}">
                <i class="fe fe-user-check side-menu__icon"></i>
                <span class="side-menu__label">المستفيدين المقبولين </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('users.index','refused')}}">
                <i class="fe fe-user-x side-menu__icon"></i>
                <span class="side-menu__label">المستفيدين المرفوضين </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('donors.index')}}">
                <i class="fe fe-award side-menu__icon"></i>
                <span class="side-menu__label"> قائمة المتبرعين </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('research.index')}}">
                <i class="fe fe-file-text side-menu__icon"></i>
                <span class="side-menu__label"> طباعة بحث اجتماعي </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('research.receive')}}">
                <i class="fe fe-printer side-menu__icon"></i>
                <span class="side-menu__label"> طباعة اقرار استلام </span>
            </a>
        </li>

        <li class="slide">
            <a class="side-menu__item" href="{{route('setting.index')}}">
                <i class="fe fe-settings side-menu__icon"></i>
                <span class="side-menu__label"> الاعدادت </span>
            </a>
        </li>

{{--        <li class="slide">--}}
{{--            <a class="side-menu__item" href="{{route('research.receive')}}">--}}
{{--                <i class="fe fe-printer side-menu__icon"></i>--}}
{{--                <span class="side-menu__label"> طباعة الاعلانات </span>--}}
{{--            </a>--}}
{{--        </li>--}}

        <li class="slide">
            <a class="side-menu__item" href="{{route('subventions.index')}}">
                <i class="fe fe-dollar-sign side-menu__icon"></i>
                <span class="side-menu__label"> الإعانات </span>
            </a>
        </li>


        <li class="slide">
            <a class="side-menu__item" href="{{route('admin.logout')}}">
                <i class="icon icon-lock side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>

    </ul>
</aside>
