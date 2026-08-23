@extends('layouts.app')
@section('title', 'Tahrirlash')
@section('content')
<div class="card card-warning">
    <form action="{{ route('sections.update', $section) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" value="{{ $section->name }}" required></div>
            <div class="form-group">
                <label>Sinf</label>
                <select name="grade_id" class="form-control" required>
                    @foreach($grades as $grade)<option value="{{ $grade->id }}" {{ $section->grade_id == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Rahbar</label>
                <select name="teacher_id" class="form-control">
                    <option value="">Tanlang...</option>
                    @foreach($teachers as $teacher)<option value="{{ $teacher->id }}" {{ $section->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Sig\'im</label><input type="number" name="capacity" class="form-control" value="{{ $section->capacity }}" required></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-warning">Yangilash</button></div>
    </form>
</div>
@endsection
