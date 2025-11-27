<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-xxl">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
            <a href="{{ route('admin.dashboard') }}" class="app-brand-link gap-2">
                @php($logo=get_business_settings('logo', false))

                <img onerror="this.src='/assets/logo.png'"
                     src="{{ $logo ? asset('storage/' . $logo) : asset('assets/logo2.png') }}"
                     height="100px" alt="logo">
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ti ti-x ti-sm align-middle"></i>
            </a>
        </div>

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>


        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class="ti ti-md"></i>
                    </a>
                </li>



                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ '/assets/logo.png' }}" alt="image_profile"
                                 onerror="this.src='/assets/logo.png'"
                                 class="h-auto rounded-circle"/>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img alt="image_profile"
                                                 src="{{ '/assets/logo.png' }}"
                                                 onerror="this.src='/assets/logo.png'"
                                                 class="h-auto rounded-circle"/>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ auth('admin')->user()->name }}</span>
                                        <span class="fw-semibold d-block">{{ auth('admin')->user()->job_title }}</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                <i class="ti ti-user-check me-2 ti-sm"></i>
                                <span class="align-middle">{{ translate('Profile') }}</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);"
                               onclick="document.getElementById('logout').submit()">
                                <i class="ti ti-logout me-2 ti-sm"></i>
                                <span class="align-middle">{{ translate('Logout') }}</span>
                            </a>
                            <form action="{{ route('admin.logout') }}" method="post" class="d-none" id="logout">
                                @csrf
                                <button type="submit"></button>
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>

        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
            <input
                type="text"
                class="form-control search-input border-0"
                placeholder="Search..."
                aria-label="Search..."/>
            <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
        </div>
    </div>
</nav>
