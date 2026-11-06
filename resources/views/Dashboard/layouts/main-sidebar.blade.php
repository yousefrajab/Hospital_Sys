{{-- Dashboard sidebar: brand, signed-in user, the role's menu. Opened/collapsed by public/hms/js/hms.js. --}}
<aside class="hms-sidebar" id="hmsSidebar" aria-label="{{ trans('ui.toggle_menu') }}">
    <div class="hms-brand">
        <a href="{{ $hmsUser->homeUrl() }}" class="hms-brand__link">
            <span class="hms-brand__mark"><i class="fas fa-plus"></i></span>
            <span class="hms-brand__name">{{ trans('ui.app_name') }}</span>
        </a>
        <button type="button" class="hms-icon-btn hms-only-mobile" data-hms-toggle="sidebar" aria-label="{{ trans('ui.toggle_menu') }}">
            <i class="fas fa-times"></i>
        </button>
    </div>

    @if ($hmsUser->check())
        <a href="{{ $hmsUser->profileUrl() ?? '#' }}" class="hms-user-card">
            @if ($hmsUser->avatarUrl())
                <img src="{{ $hmsUser->avatarUrl() }}" alt="" class="hms-avatar">
            @else
                <span class="hms-avatar hms-avatar--initials">{{ $hmsUser->initials() }}</span>
            @endif
            <span class="hms-user-card__text">
                <strong>{{ $hmsUser->name() }}</strong>
                <small>{{ $hmsUser->roleLabel() }}</small>
            </span>
        </a>
    @endif

    <div class="hms-menu-search">
        <i class="fas fa-search"></i>
        <input type="search" id="hmsMenuSearch" placeholder="{{ trans('ui.search') }}" autocomplete="off">
    </div>

    <nav class="hms-nav">
        @if ($hmsUser->guard)
            @include('Dashboard.layouts.menus.' . $hmsUser->guard)
        @endif
    </nav>
</aside>
