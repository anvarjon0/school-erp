@extends('layouts.adminlte_app')

@section('title', 'Foydalanuvchini Tahrirlash')
@section('page-title', 'Foydalanuvchini Tahrirlash')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Foydalanuvchilar</a></li>
<li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold"><i class="fas fa-user-edit mr-2 text-warning"></i> {{ $user->name }} ma'lumotlarini tahrirlash</h3>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- Ism -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">F.I.Sh (To'liq Ism) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Elektron Pochta (Email / Login) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Telefon -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Telefon Raqami</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                            </div>
                            @error('phone')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Biriktirilgan Rol <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-control select2 @error('role_id') is-invalid @enderror" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (old('role_id', $user->roles->first()?->id) == $role->id) ? 'selected' : '' }}>
                                        {{ $role->display_name }} ({{ $role->description ?? $role->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Yangi Parol -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Yangi Parol <small class="text-muted">(o'zgartirmaslik uchun bo'sh qoldiring)</small></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Yangi parol">
                            </div>
                            @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Parol Tasdig'i -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Parolni Qayta Tering</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Yangi parolni tasdiqlang">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Asosiy Maosh -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Asosiy Oylik Maosh (so'mda)</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span></div>
                                <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', $user->base_salary) }}" min="0" step="100000">
                            </div>
                            @error('base_salary')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Avatar -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Avatar</label>
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mr-3" style="width: 45px; height: 45px; object-fit: cover;" alt="">
                                @endif
                                <div class="custom-file flex-grow-1">
                                    <input type="file" name="avatar" class="custom-file-input" id="avatarInput" accept="image/*">
                                    <label class="custom-file-label" for="avatarInput">Yangi rasm tanlang...</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Bekor qilish
                    </a>
                    <button type="submit" class="btn btn-warning px-4 shadow font-weight-bold">
                        <i class="fas fa-save mr-1"></i> O'zgarishlarni Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#avatarInput').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Yangi rasm tanlang...');
    });
</script>
@endpush
