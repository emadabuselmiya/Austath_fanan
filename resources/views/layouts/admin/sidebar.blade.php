<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboards -->
            <li class="menu-item @if(\Request::routeIs('admin.dashboard')) active @endif">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div data-i18n="{{ translate('Dashboard') }}">{{ translate('Dashboard') }}</div>
                </a>
            </li>

            <!-- users -->
            <li class="menu-item  @if(\Request::routeIs('admin.users.*') || \Request::routeIs('admin.roles.*')) active @endif">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div data-i18n="{{ translate('Users') }}">{{ translate('Users') }}</div>
                </a>

                <ul class="menu-sub">

                    <li class="menu-item @if(\Request::routeIs('admin.users.*')) active @endif">
                        <a href="{{ route('admin.users.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons ti ti-users"></i>
                            <div data-i18n="{{ translate('Users') }}">{{ translate('Users') }}</div>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.students.*')) active @endif">
                <a href="{{ route('admin.students.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-school"></i>
                    <div data-i18n="{{ translate('الطلاب') }}">{{ translate('الطلاب') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.codes.*')) active @endif">
                <a href="{{ route('admin.codes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('اكواد التفعيل') }}">{{ translate('اكواد التفعيل') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.classes.*')) active @endif">
                <a href="{{ route('admin.classes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('الفصول') }}">{{ translate('الفصول') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.courses.*')) active @endif">
                <a href="{{ route('admin.courses.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('المواد') }}">{{ translate('المواد') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.subjects.*')) active @endif">
                <a href="{{ route('admin.subjects.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('المواضيع') }}">{{ translate('المواضيع') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.lessons.*')) active @endif">
                <a href="{{ route('admin.lessons.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('الدروس') }}">{{ translate('الدروس') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.sales.*')) active @endif">
                <a href="{{ route('admin.sales.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('العروض') }}">{{ translate('العروض') }}</div>
                </a>
            </li>

            <li class="menu-item @if(\Request::routeIs('admin.notifications.*')) active @endif">
                <a href="{{ route('admin.notifications.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-auth-2fa"></i>
                    <div data-i18n="{{ translate('الاشعارات') }}">{{ translate('الاشعارات') }}</div>
                </a>
            </li>

            {{--            <!-- settings -->--}}
            {{--            <li class="menu-item @if(\Request::routeIs('admin.business-setting.views')) active @endif">--}}
            {{--                <a href="{{ route('admin.business-setting.views') }}" class="menu-link">--}}
            {{--                    <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>--}}
            {{--                    <div data-i18n="{{ translate('Settings') }}">{{ translate('Settings') }}</div>--}}
            {{--                </a>--}}
            {{--            </li>--}}

        </ul>
    </div>
</aside>
