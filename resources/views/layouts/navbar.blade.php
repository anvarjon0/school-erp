<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link text-dark" data-widget="pushmenu" href="#" role="button" title="Menyuni yopish/ochish">
                <i class="fas fa-bars fa-lg"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block ml-2">
            <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 13px;">
                <i class="fas fa-calendar-check mr-1"></i> 2025-2026 O'quv yili
            </span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Live Status Pill -->
        <li class="nav-item d-none d-md-inline-block mr-3">
            <span class="badge badge-success px-3 py-2" style="font-size: 12px;">
                <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Tizim Faol (Online)
            </span>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center py-1 px-2 rounded-pill bg-light border" data-toggle="dropdown" href="#" style="text-decoration: none;">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle mr-2" alt="" style="width: 32px; height: 32px; object-fit: cover;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2 text-bold" style="width: 32px; height: 32px; font-size: 13px;">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="text-left mr-2 d-none d-sm-block">
                    <span class="d-block font-weight-bold text-dark" style="font-size: 13px; line-height: 1.1;">{{ auth()->user()->name }}</span>
                    <small class="text-muted font-weight-bold" style="font-size: 11px;">{{ auth()->user()->roles->first()?->display_name ?? 'Foydalanuvchi' }}</small>
                </div>
                <i class="fas fa-chevron-down text-muted" style="font-size: 10px;"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="border-radius: 12px; min-width: 200px;">
                <div class="px-3 py-2 border-bottom">
                    <p class="font-weight-bold text-dark mb-0">{{ auth()->user()->name }}</p>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>
                <a href="{{ route('profile.edit') }}" class="dropdown-item py-2">
                    <i class="fas fa-user-circle mr-2 text-primary"></i> Mening Profilim
                </a>
                <div class="dropdown-divider my-1"></div>
                <a href="#" class="dropdown-item py-2 text-danger font-weight-bold"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Tizimdan Chiqish
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
