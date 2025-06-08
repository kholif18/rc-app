    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
            <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img 
                    src="{{ $setting && $setting->logo ? asset('storage/logo/' . $setting->logo) : asset('logo.png') }}" 
                    alt="Logo" 
                    height="40"
                />
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">
                {{ $setting && $setting->app_name ? $setting->app_name : 'Ravaa Crtv' }}
            </span>
        </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item  {{ Request::is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="dashboard">Dashboard</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Order</span>
            </li>
            
            <li class="menu-item  {{ Request::is('order*') ? 'active' : '' }}">
                <a href="{{ route('order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-clipboard"></i>
                <div data-i18n="Semua Order">Semua Order</div>
                </a>
            </li>

            <li class="menu-item  {{ Request::is('debts*') ? 'active' : '' }}">
                <a href="{{ route('debts.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div data-i18n="Debt">Debts</div>
                </a>
            </li>

            <li class="menu-item  {{ Request::is('payments*') ? 'active' : '' }}">
                <a href="{{ route('payments.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dollar"></i>
                <div data-i18n="Lunas">Lunas</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>
            
            <li class="menu-item  {{ Request::is('customers*') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Customers">Customers</div>
                </a>
            </li>

            <li class="menu-item  {{ Request::is('bahan-cetak*') ? 'active' : '' }}">
                <a href="{{ route('bahan-cetak.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-paper-plane"></i>
                <div data-i18n="Bahan Cetak">Bahan Cetak</div>
                </a>
            </li>

            <li class="menu-item {{ Request::is('reports/*') ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-bar-chart-square"></i>
                    <div data-i18n="Reports">Reports</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::is('reports/debt') ? 'active' : '' }}">
                    <a href="{{ route('reports.debt') }}" class="menu-link">
                        <div data-i18n="Debt">Debt</div>
                    </a>
                    </li>
                    <li class="menu-item {{ Request::is('reports/order') ? 'active' : '' }}">
                    <a href="{{ route('reports.order') }}" class="menu-link">
                        <div data-i18n="Order">Order</div>
                    </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">System Setting</span>
            </li>

            <li class="menu-item  {{ Request::is('users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Basic">User</div>
                </a>
            </li>

            <li class="menu-item  {{ Request::is('admin/database*') ? 'active' : '' }}">
                <a href="{{ route('backup.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-data"></i>
                <div data-i18n="Basic">Backup</div>
                </a>
            </li>
            
            <li class="menu-item  {{ Request::is('settings*') ? 'active' : '' }}">
                <a href="{{ route('settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-cog"></i>
                <div data-i18n="Basic">System Settings</div>
                </a>
            </li>

        </ul>
    </aside>