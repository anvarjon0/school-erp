@extends('layouts.app')

@section('title', 'Yangi Rol Yaratish')
@section('page-title', 'Yangi Rol Yaratish')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Rollar</a></li>
<li class="breadcrumb-item active">Yangi</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold"><i class="fas fa-shield-alt mr-2 text-primary"></i> Rol va Ruxsatlarni Belgilash</h3>
    </div>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Tizim Kodi (Inglizcha, noyob) <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="masalan: hr-manager" required>
                    <small class="text-muted">Faqat kichik harflar va chiziqcha (a-z, -)</small>
                    @error('name')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Ko'rsatiladigan Nomi <span class="text-danger">*</span></label>
                    <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name') }}" placeholder="masalan: HR Menejer" required>
                    @error('display_name')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label font-weight-bold">Tavsif</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Rolning vazifasi haqida qisqacha">
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold m-0"><i class="fas fa-key mr-2 text-warning"></i> Ruxsatlarni Biriktirish:</h5>
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
                                       {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
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
            <button type="submit" class="btn btn-primary px-4 shadow"><i class="fas fa-save mr-1"></i> Rolni Saqlash</button>
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
