<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('admin.auth.css')
</head>

<body>
    <div class="login-page">
        <section class="brand-panel">
            <div class="brand-overlay"></div>
            <div class="brand-content">
                <span class="eyebrow">نظام إدارة الجمعية</span>
                <h1 class="brand-title">
                    <span class="brand-title-mark">جمعيتى</span>
                    <span class="brand-title-separator">|</span>
                    <span class="brand-title-name">{{ isset($setting) && $setting->title ? $setting->title : 'اسم المؤسسة' }}</span>
                </h1>
                <p class="brand-copy">
                    منصة واحدة لمتابعة المستفيدين، التبرعات، الإعانات، والقروض بسهولة ووضوح.
                </p>

                <div class="brand-highlights">
                    <div class="highlight-card">
                        <strong>إدارة منظمة</strong>
                        <span>وصول سريع للبيانات والمهام اليومية.</span>
                    </div>
                    <div class="highlight-card">
                        <strong>تقارير أوضح</strong>
                        <span>متابعة مباشرة للحركة المالية وحالات المستفيدين.</span>
                    </div>
                </div>
            </div>
        </section>

        <main class="login-panel">
            <div class="login-card">



                <div class="login-header">
                    <span class="panel-tag">أهلا بعودتك</span>
                    <h2 class="heading-primary">تسجيل الدخول</h2>
                    <p class="text-mute">أدخل بياناتك للانتقال إلى لوحة تحكم الجمعية.</p>
                </div>

                
                <form class="signup-form" action="{{ route('admin.login') }}" method="post" id="LoginForm">
                    @csrf

                    <label class="inp">
                        <span class="field-label">البريد الإلكتروني</span>
                        <input type="email" name="email" class="input-text" placeholder="اكتب البريد الالكترونى" autocomplete="username">
                        <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                    </label>

                    <label class="inp">
                        <span class="field-label">كلمة المرور</span>
                        <input type="password" name="password" class="input-text" placeholder="اكتب كلمة المرور" id="password" autocomplete="current-password">
                        <button type="button" class="input-icon input-icon-password" data-password-toggle aria-label="إظهار كلمة المرور">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </label>

                    <button class="btn btn-login" id="loginButton" type="submit">
                        <span class="btn-text">تسجيل الدخول</span>
                    </button>
                </form>

                <p class="login-note">الدخول مخصص للمشرفين المصرح لهم فقط.</p>
            </div>
        </main>
    </div>

    @include('admin.auth.js')
</body>
</html>
