@extends('layouts.adminlte_app')

@section('title', 'Yangi Foydalanuvchi')
@section('page-title', 'Yangi Foydalanuvchi Qo\'shish')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('users.index') }}">Foydalanuvchilar</a></li>
<li class="breadcrumb-item active">Yangi Qo'shish</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold"><i class="fas fa-user-plus mr-2 text-primary"></i> Foydalanuvchi Ma'lumotlarini Kiritish</h3>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Ism -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">F.I.Sh (To'liq Ism) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masalan: Sardor Azizov" required>
                            </div>
                            @error('name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Elektron Pochta (Email / Login) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Masalan: user@school.uz" required>
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
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+998 90 123 45 67">
                            </div>
                            @error('phone')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Biriktiriladigan Rol <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-control select2 @error('role_id') is-invalid @enderror" required>
                                <option value="">-- Rolni Tanlang --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }} ({{ $role->description ?? $role->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Parol -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Parol <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kamida 6 ta belgi" required>
                            </div>
                            @error('password')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Parol Tasdig'i -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Parolni Tasdiqlang <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Parolni qayta tering" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Asosiy Maosh -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Asosiy Oylik Maosh (so'mda)</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span></div>
                                <input type="number" name="base_salary" class="form-control @error('base_salary') is-invalid @enderror" value="{{ old('base_salary', 0) }}" min="0" step="100000" placeholder="0">
                            </div>
                            <small class="text-muted">Buxgalteriya oylik maosh hisoblashida foydalaniladi.</small>
                            @error('base_salary')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <!-- Avatar -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Rasm (Avatar)</label>
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input @error('avatar') is-invalid @enderror" id="avatarInput" accept="image/*">
                                <label class="custom-file-label" for="avatarInput">Rasm tanlang...</label>
                            </div>
                            @error('avatar')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Bekor qilish
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow">
                        <i class="fas fa-save mr-1"></i> Foydalanuvchini Saqlash
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
        $(this).next('.custom-file-label').html(fileName || 'Rasm tanlang...');
    });
</script>
@endpush
