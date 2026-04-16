<div id="global-loader">
    <div class="charity-loader">
        @if (!empty($setting?->logo))
            <img src="{{ asset($setting->logo) }}" class="charity-loader__logo" alt="{{ $setting->title ?? 'Logo' }}">
        @else
            <div class="charity-loader__fallback">
                <span>{{ mb_substr($setting->title ?? 'ج', 0, 1) }}</span>
            </div>
        @endif
    </div>
</div>

<style>
    #global-loader {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.96);
    }

    .charity-loader {
        width: 190px;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 40px;
        background: linear-gradient(135deg, #ffffff 0%, #f7efe7 100%);
        box-shadow: 0 20px 50px rgba(179, 44, 47, 0.16);
        animation: charityLoaderPulse 1.6s ease-in-out infinite;
        overflow: hidden;
    }

    .charity-loader__logo {
        max-width: 132px;
        max-height: 132px;
        object-fit: contain;
        display: block;
    }

    .charity-loader__fallback {
        width: 132px;
        height: 132px;
        border-radius: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #b32c2f 0%, #d56b4e 100%);
        color: #fff;
        font-size: 58px;
        font-weight: 700;
    }

    @keyframes charityLoaderPulse {
        0%,
        100% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.06);
            opacity: 0.9;
        }
    }
</style>
