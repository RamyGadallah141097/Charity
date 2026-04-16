<script src="{{asset('assets/admin')}}/assets/js/jquery-3.4.1.min.js"></script>
@toastr_js
@toastr_render
<script>
    $('[data-password-toggle]').on('click', function () {
        var passwordField = $('#password');
        var icon = $(this).find('i');
        var isPassword = passwordField.attr('type') === 'password';

        passwordField.attr('type', isPassword ? 'text' : 'password');
        icon.toggleClass('fa-eye fa-eye-slash');
        $(this).attr('aria-label', isPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
    });

    function resetLoginButton() {
        $('#loginButton')
            .html('<span class="btn-text">تسجيل الدخول</span>')
            .attr('disabled', false);
    }

    $("form#LoginForm").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var url = $('#LoginForm').attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $('#loginButton')
                    .html('<span class="spinner-border spinner-border-sm mr-2"></span> <span class="btn-text">جاري التحقق...</span>')
                    .attr('disabled', true);
            },
            success: function (data) {
                if (data == 200) {
                    toastr.success('مرحبا بعودتك');
                    window.setTimeout(function () {
                        window.location.href = '/admin';
                    }, 1000);
                } else {
                    toastr.error('كلمة المرور غير صحيحة');
                    resetLoginButton();
                }

            },
            error: function (data) {
                if (data.status === 500) {
                    resetLoginButton();
                    toastr.error('هناك خطأ ما');
                } else if (data.status === 422) {
                    resetLoginButton();
                    var errors = $.parseJSON(data.responseText);
                    $.each(errors, function (key, value) {
                        if ($.isPlainObject(value)) {
                            $.each(value, function (key, value) {
                                toastr.error(value);
                            });

                        } else {
                        }
                    });
                } else {
                    resetLoginButton();
                    toastr.error('تعذر تسجيل الدخول الآن');
                }
            },//end error method

            cache: false,
            contentType: false,
            processData: false
        });
    });

</script>
