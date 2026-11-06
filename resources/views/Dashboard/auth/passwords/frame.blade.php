{{-- Shared frame for the password pages: small centred card with the language/theme bar. Card body = @yield('form'). --}}
<div class="hms-auth hms-auth--single">
    <section class="hms-auth__panel">
        <div class="hms-auth__top">
            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                @if ($localeCode !== App::getLocale())
                    <a class="hms-icon-btn hms-icon-btn--text" hreflang="{{ $localeCode }}"
                        href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                        <i class="fas fa-globe"></i> {{ $properties['native'] }}
                    </a>
                @endif
            @endforeach
            <button type="button" class="hms-icon-btn" data-hms-toggle="theme" aria-label="{{ trans('ui.dark_mode') }}">
                <i class="fas fa-moon hms-when-light"></i><i class="fas fa-sun hms-when-dark"></i>
            </button>
        </div>
        <div class="hms-auth__card hms-auth__card--boxed">
            <div class="hms-auth__icon"><i class="fas {{ $icon }}"></i></div>
            @yield('form')
        </div>
    </section>
</div>
