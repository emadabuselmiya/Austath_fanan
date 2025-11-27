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
            <li class="menu-item  @if(\Request::routeIs('admin.users.*') || \Request::routeIs('admin.roles.*') || \Request::routeIs('admin.pharmacies.*')) active @endif">
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

            <!-- settings -->
            <li class="menu-item @if(\Request::routeIs('admin.business-setting.views')) active @endif">
                <a href="{{ route('admin.business-setting.views') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-adjustments-alt"></i>
                    <div data-i18n="{{ translate('Settings') }}">{{ translate('Settings') }}</div>
                </a>
            </li>

        </ul>
    </div>
</aside>
