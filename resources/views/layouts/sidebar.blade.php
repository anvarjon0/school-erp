<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-graduation-cap brand-image ml-3" style="font-size: 1.8rem; margin-top: 3px;"></i>
        <span class="brand-text font-weight-light"><b>School</b> ERP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="img-circle elevation-2" alt="">
                @else
                    <i class="fas fa-user-circle fa-2x text-light"></i>
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ auth()->user()->name }}</a>
                <small class="text-muted">
                    @foreach(auth()->user()->roles as $role)
                        {{ $role->display_name }}@if(!$loop->last), @endif
                    @endforeach
                </small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @php $user = auth()->user(); @endphp

                {{-- Super Admin Menu --}}
                @if($user->isSuperAdmin())
                <li class="nav-header">BOSHQARUV</li>
                <li class="nav-item {{ request()->routeIs('users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Foydalanuvchilar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ro'yxat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}" class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Yangi qo'shish</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>Rollar</p>
                    </a>
                </li>
                @endif

                {{-- Academic Structure --}}
                @if($user->isSuperAdmin() || $user->hasRole('administrator'))
                <li class="nav-header">O'QUV TUZILMASI</li>
                <li class="nav-item">
                    <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>O'quv yillari</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('grades.index') }}" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Sinflar</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sections.index') }}" class="nav-link {{ request()->routeIs('sections.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Bo'limlar</p>
                    </a>
                </li>
                @endif

                {{-- Students --}}
                @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'class-teacher']))
                <li class="nav-header">O'QUVCHILAR</li>
                <li class="nav-item {{ request()->routeIs('students.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>O'quvchilar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ro'yxat</p>
                            </a>
                        </li>
                        @if($user->isSuperAdmin() || $user->hasRole('administrator'))
                        <li class="nav-item">
                            <a href="{{ route('students.create') }}" class="nav-link {{ request()->routeIs('students.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Yangi qabul</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Payments --}}
                @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'accountant']))
                <li class="nav-header">MOLIYA</li>
                <li class="nav-item {{ request()->routeIs('payments.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>To'lovlar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ro'yxat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.create') }}" class="nav-link {{ request()->routeIs('payments.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>To'lov qabul</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('payments.debtors') }}" class="nav-link {{ request()->routeIs('payments.debtors') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Qarzdorlar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Expenses --}}
                @if($user->isSuperAdmin() || $user->hasRole('accountant'))
                <li class="nav-item {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Xarajatlar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ro'yxat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Kategoriyalar</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Salaries --}}
                <li class="nav-item {{ request()->routeIs('salaries.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Maoshlar<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('salaries.index') }}" class="nav-link {{ request()->routeIs('salaries.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ro'yxat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('salaries.create') }}" class="nav-link {{ request()->routeIs('salaries.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Maosh hisoblash</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Attendance --}}
                @if($user->isSuperAdmin() || $user->hasRole('class-teacher'))
                <li class="nav-header">DAVOMAT</li>
                <li class="nav-item {{ request()->routeIs('attendances.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>Davomat<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Ko'rish</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendances.create') }}" class="nav-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Davomat yuritish</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('attendances.report') }}" class="nav-link {{ request()->routeIs('attendances.report') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i><p>Hisobot</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Reports --}}
                @if($user->isSuperAdmin() || $user->hasRole('accountant'))
                <li class="nav-header">HISOBOTLAR</li>
                <li class="nav-item">
                    <a href="{{ route('reports.financial') }}" class="nav-link {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Moliyaviy hisobot</p>
                    </a>
                </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>
