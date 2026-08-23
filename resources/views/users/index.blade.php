@extends('layouts.app')

@section('title', 'Foydalanuvchilar')
@section('page-title', 'Foydalanuvchilar Boshqaruvi')

@section('breadcrumb')
<li class="breadcrumb-item active">Foydalanuvchilar</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold"><i class="fas fa-users-cog mr-2 text-primary"></i> Foydalanuvchilar Ro'yxati</h3>
        <div class="card-tools">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-user-plus mr-1"></i> Yangi Foydalanuvchi Qo'shish
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filtr va Qidiruv formasi -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Ism, email yoki telefon..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="role" class="form-control select2">
                        <option value="">-- Barcha Rollar --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Barcha Holatlar --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Faol</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Faolsiz</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-info px-3"><i class="fas fa-filter mr-1"></i> Qidirish</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-2" title="Tozalash"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>

        <!-- Foydalanuvchilar Jadvali -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Foydalanuvchi</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Biriktirilgan Rol</th>
                        <th>Asosiy Maosh</th>
                        <th>Holat</th>
                        <th style="width: 150px;" class="text-center">Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mr-2" style="width: 38px; height: 38px; object-fit: cover;" alt="">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2 text-bold" style="width: 38px; height: 38px; font-size: 16px;">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <span class="font-weight-bold d-block">{{ $user->name }}</span>
                                    <small class="text-muted">Qo'shildi: {{ $user->created_at->format('d.m.Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @forelse($user->roles as $role)
                                @php
                                    $badgeColor = match($role->name) {
                                        'super-admin' => 'danger',
                                        'administrator' => 'primary',
                                        'accountant' => 'success',
                                        'class-teacher' => 'warning',
                                        default => 'info'
                                    };
                                @endphp
                                <span class="badge badge-{{ $badgeColor }} px-2 py-1" style="font-size: 13px;">
                                    <i class="fas fa-shield-alt mr-1"></i> {{ $role->display_name }}
                                </span>
                            @empty
                                <span class="badge badge-secondary">Rolsiz</span>
                            @endforelse
                        </td>
                        <td>{{ number_format($user->base_salary) }} so'm</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Faol</span>
                            @else
                                <span class="badge badge-danger px-2 py-1"><i class="fas fa-ban mr-1"></i> Faolsiz</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if(!$user->isSuperAdmin() || auth()->id() !== $user->id)
                                    <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'secondary' : 'success' }}" title="{{ $user->is_active ? 'Faolsizlantirish' : 'Faollashtirish' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>

                                    @if(!$user->isSuperAdmin())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete" title="O'chirish">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-user-slash fa-3x mb-2 d-block"></i>
                            Hech qanday foydalanuvchi topilmadi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
