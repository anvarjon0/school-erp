<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="img-circle" alt="" style="width:25px;height:25px;object-fit:cover;">
                @else
                    <i class="fas fa-user-circle fa-lg"></i>
                @endif
                <span class="ml-1">{{ auth()->user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Chiqish
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
