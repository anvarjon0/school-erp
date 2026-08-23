@extends('layouts.adminlte_app')

@section('title', 'Rollar va Ruxsatlar')
@section('page-title', 'Rollar va Ruxsatlar Boshqaruvi')

@section('breadcrumb')
<li class="breadcrumb-item active">Rollar</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold"><i class="fas fa-user-shield mr-2 text-primary"></i> Tizimdagi Rollar Ro'yxati</h3>
        <div class="card-tools">
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus mr-1"></i> Yangi Rol Yaratish
            </a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Rol Nomi</th>
                        <th>Tizim Kodi</th>
                        <th>Tavsifi</th>
                        <th>Foydalanuvchilar Soni</th>
                        <th style="width: 150px;" class="text-center">Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            @php
                                $badgeColor = match($role->name) {
                                    'super-admin' => 'danger',
                                    'administrator' => 'primary',
                                    'accountant' => 'success',
                                    'class-teacher' => 'warning',
                                    default => 'info'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeColor }} px-2 py-1" style="font-size: 14px;">
                                <i class="fas fa-shield-alt mr-1"></i> {{ $role->display_name }}
                            </span>
                        </td>
                        <td><code>{{ $role->name }}</code></td>
                        <td>{{ $role->description ?? '-' }}</td>
                        <td><span class="badge badge-info px-2 py-1">{{ $role->users_count }} nafar</span></td>
                        <td class="text-center">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning" title="Tahrirlash va Ruxsatlar">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!in_array($role->name, ['super-admin', 'administrator', 'accountant', 'class-teacher']))
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="O'chirish">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
