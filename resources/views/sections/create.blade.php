@extends('layouts.adminlte_app')
@section('title', 'Yangi bo\'lim')
@section('content')
<div class="card card-primary">
    <form action="{{ route('sections.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group">
                <label>Sinf</label>
                <select name="grade_id" class="form-control" required>
                    @foreach($grades as $grade)<option value="{{ $grade->id }}">{{ $grade->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Rahbar</label>
                <select name="teacher_id" class="form-control">
                    <option value="">Tanlang...</option>
                    @foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Sig\'im</label><input type="number" name="capacity" class="form-control" value="30" required></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Saqlash</button></div>
    </form>
</div>
@endsection
