{{-- Dashboard top bar. Data: $hmsUser (CurrentUser) and the HeaderDataComposer variables. --}}
<header class="hms-topbar">
    <button type="button" class="hms-icon-btn" data-hms-toggle="sidebar" aria-label="{{ trans('ui.toggle_menu') }}">
        <i class="fas fa-bars"></i>
    </button>

    <div class="hms-topbar__spacer"></div>

    {{-- Language --}}
    <div class="dropdown">
        <button type="button" class="hms-icon-btn hms-icon-btn--text" data-toggle="dropdown" data-display="static" aria-label="{{ trans('ui.language') }}">
            <i class="fas fa-globe"></i>
            <span class="hms-hide-sm">{{ LaravelLocalization::getCurrentLocaleNative() }}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end hms-dropdown">
            @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <a class="dropdown-item {{ App::getLocale() === $localeCode ? 'active' : '' }}" rel="alternate" hreflang="{{ $localeCode }}"
                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                    {{ $properties['native'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Theme --}}
    <button type="button" class="hms-icon-btn" data-hms-toggle="theme" title="{{ trans('ui.dark_mode') }} / {{ trans('ui.light_mode') }}"
        aria-label="{{ trans('ui.dark_mode') }}">
        <i class="fas fa-moon hms-when-light"></i>
        <i class="fas fa-sun hms-when-dark"></i>
    </button>

    {{-- Messages (doctor and patient chat) --}}
    @if ($chatRouteForHeader ?? null)
        <div class="dropdown">
            <button type="button" class="hms-icon-btn" data-toggle="dropdown" data-display="static" aria-label="{{ trans('ui.messages') }}">
                <i class="far fa-comment-dots"></i>
                <span class="hms-badge" id="headerChatCountBadge" @if (! ($unreadMessagesForHeader ?? 0)) style="display:none" @endif>{{ $unreadMessagesForHeader ?? 0 }}</span>
            </button>
            <div class="dropdown-menu dropdown-menu-end hms-dropdown hms-dropdown--wide">
                <div class="hms-dropdown__head">{{ trans('ui.messages') }}</div>
                <div class="hms-dropdown__list" id="headerMessageListContainer">
                    @forelse ($latestMessagesForHeader ?? [] as $headerMessage)
                        <a href="{{ $chatRouteForHeader }}" class="hms-dropdown__item">
                            <span class="hms-dropdown__icon"><i class="fas fa-user"></i></span>
                            <span>
                                <strong>{{ $headerMessage->sender_display_name }}</strong>
                                <small class="d-block text-muted">{{ \Illuminate\Support\Str::limit($headerMessage->body, 60) }}</small>
                                <small class="hms-dropdown__time">{{ $headerMessage->created_at->diffForHumans() }}</small>
                            </span>
                        </a>
                    @empty
                        <p class="hms-dropdown__empty text-center text-muted">{{ trans('ui.no_messages') }}</p>
                    @endforelse
                </div>
                <a href="{{ $chatRouteForHeader }}" class="hms-dropdown__foot">{{ trans('ui.view_all') }}</a>
            </div>
        </div>
    @endif

    {{-- Notifications --}}
    <div class="dropdown">
        <button type="button" class="hms-icon-btn" data-toggle="dropdown" data-display="static" aria-label="{{ trans('ui.notifications') }}">
            <i class="far fa-bell"></i>
            <span class="hms-badge" id="generalNotificationsCountBadge" @if (! ($generalNotificationsCountForHeader ?? 0)) style="display:none" @endif>{{ $generalNotificationsCountForHeader ?? 0 }}</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end hms-dropdown hms-dropdown--wide">
            <div class="hms-dropdown__head">{{ trans('ui.notifications') }}</div>
            <div class="hms-dropdown__list" id="hmsNotificationList">
                @forelse ($latestGeneralNotificationsForHeader ?? [] as $notification)
                    <div class="hms-dropdown__item">
                        <span class="hms-dropdown__icon"><i class="fas fa-file-medical"></i></span>
                        <span>
                            <span class="d-block">{{ $notification->message }}</span>
                            <small class="hms-dropdown__time">{{ $notification->created_at->diffForHumans() }}</small>
                        </span>
                    </div>
                @empty
                    <p class="hms-dropdown__empty text-center text-muted">{{ trans('ui.no_notifications') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Account --}}
    @if ($hmsUser->check())
        <div class="dropdown">
            <button type="button" class="hms-account" data-toggle="dropdown" data-display="static">
                @if ($hmsUser->avatarUrl())
                    <img src="{{ $hmsUser->avatarUrl() }}" alt="" class="hms-avatar hms-avatar--sm">
                @else
                    <span class="hms-avatar hms-avatar--sm hms-avatar--initials">{{ $hmsUser->initials() }}</span>
                @endif
                <span class="hms-account__name hms-hide-sm">{{ $hmsUser->name() }}</span>
                <i class="fas fa-chevron-down hms-hide-sm"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end hms-dropdown">
                <div class="hms-dropdown__head">
                    <strong class="d-block">{{ $hmsUser->name() }}</strong>
                    <small class="text-muted">{{ $hmsUser->email() }}</small>
                </div>
                @if ($hmsUser->profileUrl())
                    <a class="dropdown-item" href="{{ $hmsUser->profileUrl() }}"><i class="far fa-user"></i> {{ trans('ui.profile') }}</a>
                @endif
                @if ($hmsUser->profileEditUrl())
                    <a class="dropdown-item" href="{{ $hmsUser->profileEditUrl() }}"><i class="far fa-edit"></i> {{ trans('ui.edit_profile') }}</a>
                @endif
                @if ($hmsUser->guard === 'patient' && Route::has('home'))
                    <a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-globe-africa"></i> {{ trans('ui.website') }}</a>
                @endif
                @if ($hmsUser->logoutUrl())
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ $hmsUser->logoutUrl() }}" id="logoutFormGlobal">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt"></i> {{ trans('ui.logout') }}</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</header>

@if (auth('doctor')->check())
    @push('hms-scripts')
        <script>
            // Live "new examination" notification for doctors (Pusher via Laravel Echo).
            if (window.Echo) {
                window.Echo.private('create-invoice.{{ auth('doctor')->id() }}').listen('.create-invoice', function (data) {
                    window.hms && window.hms.pushNotification((data.message || '') + ' ' + (data.patient || ''), data.created_at || '');
                });
            }
        </script>
    @endpush
@endif
