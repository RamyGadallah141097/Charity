<title>
    {{ isset($setting) ? $setting->title : '' }}
 | تسجيل الدخول
</title>
<link rel="shortcut icon" type="image/x-icon"
    href="{{ !empty($setting?->logo) ? asset($setting->logo) : asset('assets/admin/images/favicon.ico') }}">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700&display=swap');

    :root {
        --primary: #b32c2f;
        --primary-dark: #8e2225;
        --ink: #1f2430;
        --muted: #6d7485;
        --line: rgba(31, 36, 48, 0.1);
        --surface: rgba(255, 255, 255, 0.94);
        --surface-soft: rgba(255, 255, 255, 0.72);
        --highlight: #f6ece3;
        --brand-deep: #231f20;
        --shadow-lg: 0 30px 80px rgba(31, 36, 48, 0.18);
        --shadow-md: 0 18px 45px rgba(31, 36, 48, 0.12);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Almarai';
    }

    html {
        font-size: 62.5%;
    }

    body {
        direction: rtl;
        font-family: "Almarai", sans-serif;
        line-height: 1.6;
        color: var(--ink);
        font-size: 1.6rem;
        min-height: 100vh;
        overflow-x: hidden;
        background:
            radial-gradient(circle at top right, rgba(179, 44, 47, 0.12), transparent 28%),
            radial-gradient(circle at bottom left, rgba(244, 204, 176, 0.38), transparent 30%),
            linear-gradient(135deg, #f4efe8 0%, #faf7f3 42%, #f7f1ea 100%);
    }

    a {
        color: var(--primary);
        text-decoration: none;
    }

    .login-page {
        display: grid;
        grid-template-columns: minmax(320px, 1.05fr) minmax(420px, 0.95fr);
        min-height: 100vh;
    }

    .brand-panel {
        position: relative;
        overflow: hidden;
        padding: 5.6rem;
        display: flex;
        align-items: flex-end;
        background:
            linear-gradient(160deg, rgba(35, 31, 32, 0.94), rgba(68, 28, 31, 0.88)),
            linear-gradient(120deg, #6e2527, #1f2430);
    }

    .brand-panel::before,
    .brand-panel::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        filter: blur(2px);
    }

    .brand-panel::before {
        width: 34rem;
        height: 34rem;
        top: -8rem;
        right: -12rem;
    }

    .brand-panel::after {
        width: 24rem;
        height: 24rem;
        bottom: 6rem;
        left: -10rem;
    }

    .brand-overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(0deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.02)),
            repeating-linear-gradient(135deg,
                rgba(255, 255, 255, 0.05) 0,
                rgba(255, 255, 255, 0.05) 2px,
                transparent 2px,
                transparent 18px);
        opacity: 0.55;
        pointer-events: none;
    }

    .brand-content {
        position: relative;
        z-index: 1;
        max-width: 56rem;
        color: #fff;
    }

    .eyebrow,
    .panel-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        width: fit-content;
        padding: 0.8rem 1.4rem;
        border-radius: 999px;
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .eyebrow {
        margin-bottom: 2rem;
        color: rgba(255, 255, 255, 0.92);
        background: rgba(255, 255, 255, 0.11);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .panel-tag {
        margin-bottom: 1.8rem;
        color: var(--primary);
        background: rgba(179, 44, 47, 0.08);
    }

    .brand-title {
        font-size: clamp(4rem, 5vw, 6.6rem);
        line-height: 1.05;
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        flex-wrap: wrap;
    }

    .brand-title-mark {
        color: #fff;
    }

    .brand-title-separator {
        color: rgba(255, 255, 255, 0.38);
        font-weight: 300;
    }

    .brand-title-name {
        color: #f2c7a6;
    }

    .brand-copy {
        max-width: 46rem;
        font-size: 1.85rem;
        color: rgba(255, 255, 255, 0.78);
        margin-bottom: 3.6rem;
    }

    .brand-highlights {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.6rem;
    }

    .highlight-card {
        padding: 1.8rem;
        border-radius: 2.2rem;
        background: rgba(255, 255, 255, 0.09);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    .highlight-card strong {
        display: block;
        font-size: 1.8rem;
        margin-bottom: 0.6rem;
    }

    .highlight-card span {
        color: rgba(255, 255, 255, 0.76);
        font-size: 1.45rem;
    }

    .login-panel {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem;
    }

    .login-card {
        width: min(100%, 52rem);
        background: var(--surface);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 3.2rem;
        box-shadow: var(--shadow-lg);
        padding: 3.2rem;
        backdrop-filter: blur(18px);
        position: relative;
    }

    .login-card-back {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 1.8rem;
    }

    .login-back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.75rem 1.2rem;
        border-radius: 999px;
        border: 1px solid rgba(179, 44, 47, 0.15);
        background: rgba(179, 44, 47, 0.06);
        color: var(--primary);
        font-size: 1.45rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .login-back-button:hover {
        color: var(--primary-dark);
        background: rgba(179, 44, 47, 0.1);
        transform: translateY(-1px);
    }

    .logo-badge {
        width: 8.6rem;
        height: 8.6rem;
        border-radius: 2.4rem;
        background: linear-gradient(135deg, #fff 0%, #f5ebe4 100%);
        border: 1px solid rgba(179, 44, 47, 0.12);
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2.4rem;
        overflow: hidden;
    }

    .logo-badge img,
    .logo-fallback {
        width: 100%;
        height: 100%;
    }

    .logo-badge img {
        object-fit: cover;
    }

    .logo-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary) 0%, #d76849 100%);
        color: #fff;
        font-size: 3.2rem;
        font-weight: 700;
    }

    .login-header {
        margin-bottom: 2.8rem;
    }

    .heading-primary {
        font-size: 4.2rem;
        line-height: 1.1;
        margin-bottom: 0.8rem;
    }

    .text-mute {
        color: var(--muted);
        font-size: 1.55rem;
    }

    .signup-form {
        display: flex;
        flex-direction: column;
        gap: 1.8rem;
    }

    .input-text {
        font-family: 'Almarai';
        font-size: 1.65rem;
        padding: 1.8rem 5.2rem 1.8rem 1.8rem;
        border: 1px solid var(--line);
        border-radius: 1.8rem;
        background: rgba(250, 247, 243, 0.9);
        width: 100%;
        color: var(--ink);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .input-text:focus {
        outline: none;
        border-color: rgba(179, 44, 47, 0.4);
        box-shadow: 0 0 0 0.4rem rgba(179, 44, 47, 0.08);
        transform: translateY(-1px);
    }

    .btn {
        padding: 1.7rem 2.4rem;
        border: none;
        background: linear-gradient(135deg, var(--primary) 0%, #d14548 100%);
        color: #fff;
        border-radius: 1.8rem;
        cursor: pointer;
        font-family: 'Almarai';
        font-weight: 700;
        font-size: 1.7rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .btn-login {
        width: 100%;
        margin-top: 0.8rem;
        box-shadow: 0 18px 30px rgba(179, 44, 47, 0.2);
    }

    .btn-login:active {
        transform: translateY(0);
        box-shadow: 0 8px 18px rgba(179, 44, 47, 0.16);
    }

    .btn-login:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
    }

    .btn[disabled] {
        opacity: 0.85;
        cursor: wait;
    }

    .inp {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .field-label {
        color: var(--ink);
        font-size: 1.45rem;
        font-weight: 700;
    }

    .input-text::placeholder {
        color: #9aa1af;
    }

    .input-icon {
        position: absolute;
        top: 4.6rem;
        right: 2rem;
        font-size: 1.8rem;
        color: #838a99;
        background: transparent;
        border: 0;
    }

    .input-icon-password {
        cursor: pointer;
        left: 1.8rem;
        right: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 50%;
    }

    .login-note {
        margin-top: 2.2rem;
        color: var(--muted);
        font-size: 1.35rem;
        text-align: center;
    }

    @media only screen and (max-width: 1180px) {
        .login-page {
            grid-template-columns: 1fr;
        }

        .brand-panel {
            min-height: 34rem;
            padding: 4rem 3rem;
        }

        .brand-highlights {
            grid-template-columns: 1fr;
        }
    }

    @media only screen and (max-width: 700px) {
        html {
            font-size: 54.5%;
        }

        .login-panel {
            padding: 2rem;
        }

        .login-card {
            padding: 2.4rem 2rem;
        }

        .brand-panel {
            min-height: auto;
            padding: 3.6rem 2rem 3rem;
        }

        .brand-title {
            font-size: 3.8rem;
        }
    }

    @media only screen and (max-width: 420px) {
        html {
            font-size: 50%;
        }
    }
</style>
@toastr_css
