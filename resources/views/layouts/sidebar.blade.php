<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <div class="brand-logo-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
            <span class="brand-text-title">School<span class="text-indigo" style="color: #818cf8;">ERP</span></span>
            <span class="brand-text-badge">v2.0</span>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-2">
        <!-- Sidebar user panel -->
        <div class="user-panel d-flex align-items-center">
            <div class="image mr-2">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover;" alt="">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center text-bold" style="width: 38px; height: 38px; font-size: 15px;">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="info text-truncate">
                <a href="{{ route('profile.edit') }}" class="d-block font-weight-bold text-white text-truncate" style="font-size: 13px;">
                    {{ auth()->user()->name }}
                </a>
                <small class="text-muted d-block text-truncate font-weight-bold" style="font-size: 11px;">
                    <i class="fas fa-shield-alt mr-1 text-primary"></i>
                    {{ auth()->user()->roles->first()?->display_name ?? 'Foydalanuvchi' }}
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie text-primary"></i>
                        <p>Boshqaruv Paneli</p>
                    </a>
                </li>

                @php $user = auth()->user(); @endphp

                {{-- Super Admin Menu --}}
                @if($user->isSuperAdmin())
                <li class="nav-header">XODIMLAR VA ROLLAR</li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users text-indigo" style="color: #a5b4fc;"></i>
                        <p>Foydalanuvchilar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Barcha xodimlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}" class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Yangi xodim qo'shish</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield text-warning"></i>
                        <p>Rollar va Ruxsatlar</p>
                    </a>
                </li>
                @endif

                {{-- Academic Structure --}}
                @if($user->isSuperAdmin() || $user->hasRole('administrator'))
                <li class="nav-header">MAKTAB STRUKTURASI</li>
                <li class="nav-item">
                    <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt text-info"></i>
                        <p>O'quv Yillari</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group text-primary"></i>
                        <p>Sinflar & Narxlar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sections.index') }}" class="nav-link {{ request()->routeIs('sections.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large text-emerald" style="color: #34d399;"></i>
                        <p>Bo'limlar (Sinf guruhlari)</p>
                    </a>
                </li>
                @endif

                {{-- Students --}}
                @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'class-teacher']))
                <li class="nav-header">O'QUVCHILAR</li>
                <li class="nav-item {{ request()->routeIs('students.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate text-success"></i>
                        <p>O'quvchilar Bazasi<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>O'quvchilar ro'yxati</p>
                            </a>
                        </li>
                        @if($user->isSuperAdmin() || $user->hasRole('administrator'))
                        <li class="nav-item">
                            <a href="{{ route('students.create') }}" class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Yangi o'quvchi qabul</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Payments --}}
                @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'accountant']))
                <li class="nav-header">MOLIYA & TO'LOVLAR</li>
                <li class="nav-item {{ request()->routeIs('payments.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave text-emerald" style="color: #10b981;"></i>
                        <p>To'lovlar & Kassa<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Barcha to'lovlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.create') }}" class="nav-link {{ request()->routeIs('payments.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>To'lov qabul qilish</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.debtors') }}" class="nav-link {{ request()->routeIs('payments.debtors') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p class="text-danger font-weight-bold">Qarzdorlar ro'yxati</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Expenses & Salaries --}}
                @if($user->isSuperAdmin() || $user->hasRole('accountant'))
                <li class="nav-item {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt text-rose" style="color: #fb7185;"></i>
                        <p>Xarajatlar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Barcha xarajatlar</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Xarajat turlari</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->routeIs('salaries.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet text-amber" style="color: #fbbf24;"></i>
                        <p>Oylik Maoshlar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('salaries.index') }}" class="nav-link {{ request()->routeIs('salaries.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Maoshlar ro'yxati</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('salaries.create') }}" class="nav-link {{ request()->routeIs('salaries.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Maosh hisoblash</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Attendance --}}
                @if($user->isSuperAdmin() || $user->hasRole('class-teacher'))
                <li class="nav-header">DAVOMAT JURNALI</li>
                <li class="nav-item {{ request()->routeIs('attendances.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-check text-sky" style="color: #38bdf8;"></i>
                        <p>Davomat Nazorati<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Davomatni ko'rish</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendances.create') }}" class="nav-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Bugungi davomat olish</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendances.report') }}" class="nav-link {{ request()->routeIs('attendances.report') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon" style="font-size: 10px;"></i><p>Oylik hisobot</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Reports --}}
                @if($user->isSuperAdmin() || $user->hasRole('accountant'))
                <li class="nav-header">TAHLIL VA HISOBOTLAR</li>
                <li class="nav-item">
                    <a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-info"></i>
                        <p>Moliyaviy Hisobot</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
