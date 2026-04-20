<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- FAVICON -->
<link rel="shortcut icon" type="image/x-icon"
    href="{{ !empty($setting?->logo) ? asset($setting->logo) : asset('assets/admin/images/favicon.ico') }}" />

<!-- TITLE -->
<title>@yield('page_name')</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- BOOTSTRAP CSS -->
<link href="{{ asset('assets/admin') }}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<!-- STYLE CSS -->
<link href="{{ asset('assets/admin') }}/assets/css-rtl/style.css" rel="stylesheet" />
<link href="{{ asset('assets/admin') }}/assets/css-rtl/skin-modes.css" rel="stylesheet" />
<link href="{{ asset('assets/admin') }}/assets/css-rtl/dark-style.css" rel="stylesheet" />

<!-- SIDE-MENU CSS -->
<link href="{{ asset('assets/admin') }}/assets/css-rtl/sidemenu.css" rel="stylesheet">

<!--PERFECT SCROLL CSS-->
<link href="{{ asset('assets/admin') }}/assets/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet" />

<!-- CUSTOM SCROLL BAR CSS-->
<link href="{{ asset('assets/admin') }}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet" />

<!--- FONT-ICONS CSS -->
<link href="{{ asset('assets/admin') }}/assets/css/icons.css" rel="stylesheet" />

<!-- SIDEBAR CSS -->
<link href="{{ asset('assets/admin') }}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

<!-- SELECT2 CSS -->
<link href="{{ asset('assets/admin') }}/assets/plugins/select2/select2.min.css" rel="stylesheet" />
<!-- Dropify CSS -->
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css">--}}

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all"
    href="{{ asset('assets/admin') }}/assets/colors/color1.css" />


<!-- TOASTR CSS -->
@toastr_css

<!-- Switcher CSS -->
<link href="{{ asset('assets/admin') }}/assets/switcher/css/switcher-rtl.css" rel="stylesheet">
<link href="{{ asset('assets/admin') }}/assets/switcher/demo.css" rel="stylesheet">

<script defer src="{{ asset('assets/admin') }}/assets/iconfonts/font-awesome/js/brands.js"></script>
<script defer src="{{ asset('assets/admin') }}/assets/iconfonts/font-awesome/js/solid.js"></script>
<script defer src="{{ asset('assets/admin') }}/assets/iconfonts/font-awesome/js/fontawesome.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" />



<style>
    .page-header-with-back {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-header-title-group {
        min-width: 0;
    }

    .page-back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        margin-right: auto;
    }

    @media (max-width: 767.98px) {
        .page-header-with-back {
            align-items: stretch;
        }

        .page-back-button {
            width: fit-content;
        }
    }

    .small-text-hover {
        font-size: 12px;
        transition: font-size 0.3s ease-in-out;
        display: inline-block;
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .small-text-hover:hover {
        font-size: 16px;
        white-space: normal;
        overflow: visible;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        min-height: calc(2.25rem + 2px);
        border: 1px solid #e8e8f7;
        border-radius: 6px;
        background-color: #fff;
    }

    .select2-container[dir="rtl"] .select2-selection--single .select2-selection__rendered {
        line-height: calc(2.25rem + 2px);
        padding-right: 12px;
        padding-left: 34px;
        text-align: right;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        left: 8px;
        right: auto;
        width: 20px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field,
    .select2-container--default .select2-search--inline .select2-search__field {
        border: 1px solid #e8e8f7;
        border-radius: 6px;
        padding: 6px 10px;
        text-align: right;
        direction: rtl;
    }

    .select2-dropdown {
        border: 1px solid #e8e8f7;
        border-radius: 8px;
        overflow: hidden;
    }

    .select2-container--open .select2-dropdown--below {
        margin-top: 4px;
    }

    .select2-results__option {
        text-align: right;
        direction: rtl;
        padding: 10px 12px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5a5ce0;
    }

    .modal .select2-container {
        z-index: 1056;
    }

    .modal .select2-dropdown {
        z-index: 1057;
    }



</style>
@yield('css')
