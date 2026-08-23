@extends('layouts.adminlte_app')
@section('title', 'Tahrirlash')
@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Tahrirlash</h3></div>
    <form action="{{ route('academic-years.update', $academicYear) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Nomi</label>
                <input type="text" name="name" class="form-control" value="{{ $academicYear->name }}" required>
            </div>
            <div class="form-group">
                <label>Boshlanish sanasi</label>
                <input type="date" name="start_date" class="form-control" value="{{ $academicYear->start_date->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Tugash sanasi</label>
                <input type="date" name="end_date" class="form-control" value="{{ $academicYear->end_date->format('Y-m-d') }}" required>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_current" value="1" class="form-check-input" id="is_current" {{ $academicYear->is_current ? 'checked' : '' }}>
                <label class="form-check-label" for="is_current">Joriy qilib belgilash</label>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning">Yangilash</button>
        </div>
    </form>
</div>
@endsection
