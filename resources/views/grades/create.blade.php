@extends('layouts.adminlte_app')
@section('title', 'Yangi sinf')
@section('content')
<div class="card card-primary">
    <form action="{{ route('grades.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Daraja (Raqam)</label><input type="number" name="level" class="form-control" required></div>
            <div class="form-group"><label>Oylik to\'lov</label><input type="number" name="monthly_fee" class="form-control" required></div>
            <div class="form-group">
                <label>O\'quv yili</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Saqlash</button></div>
    </form>
</div>
@endsection
