@extends('layouts.adminlte_app')

@section('title', 'Rolni Tahrirlash')
@section('page-title', 'Rolni Tahrirlash')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Rollar</a></li>
<li class="breadcrumb-item active">{{ $role->display_name }}</li>
@endsection

@section('content')
<div class="card card-outline card-warning shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold"><i class="fas fa-user-shield mr-2 text-warning"></i> {{ $role->display_name }} Ruxsatlarini Tahrirlash</h3>
    </div>

    <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Tizim Kodi</label>
                    <input type="text" class="form-control bg-light" value="{{ $role->name }}" disabled>
                    <small class="text-muted">Tizim kodi o'zgartirilmaydi.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Ko'rsatiladigan Nomi <span class="text-danger">*</span></label>
                    <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name', $role->display_name) }}" required>
                    @error('display_name')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Tavsif</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $role->description) }}">
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold m-0"><i class="fas fa-key mr-2 text-warning"></i> Biriktirilgan Ruxsatlar:</h5>
                <div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="checkAll">Barchasini tanlash</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="uncheckAll">Barchasini bekor qilish</button>
                </div>
            </div>

            <div class="row">
                @foreach($permissions as $group => $perms)
                <div class="col-md-4 mb-3">
                    <div class="card card-outline card-info h-100 shadow-sm">
                        <div class="card-header bg-light py-2">
                            <h6 class="card-title font-weight-bold text-dark m-0">{{ $group }}</h6>
                        </div>
                        <div class="card-body py-2">
                            @foreach($perms as $perm)
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                       class="custom-control-input perm-checkbox" id="perm_{{ $perm->id }}"
                                       {{ in_array($perm->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-normal cursor-pointer" for="perm_{{ $perm->id }}">
                                    {{ $perm->display_name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer bg-light d-flex justify-content-between">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary px-4">Bekor qilish</a>
            <button type="submit" class="btn btn-warning px-4 shadow font-weight-bold"><i class="fas fa-save mr-1"></i> O'zgarishlarni Saqlash</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $('#checkAll').click(function() {
        $('.perm-checkbox').prop('checked', true);
    });
    $('#uncheckAll').click(function() {
        $('.perm-checkbox').prop('checked', false);
    });
</script>
@endpush
