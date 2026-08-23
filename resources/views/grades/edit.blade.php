@extends('layouts.app')
@section('title', 'Tahrirlash')
@section('content')
<div class="card card-warning">
    <form action="{{ route('grades.update', $grade) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" value="{{ $grade->name }}" required></div>
            <div class="form-group"><label>Daraja</label><input type="number" name="level" class="form-control" value="{{ $grade->level }}" required></div>
            <div class="form-group"><label>Oylik to\'lov</label><input type="number" name="monthly_fee" class="form-control" value="{{ $grade->monthly_fee }}" required></div>
            <div class="form-group">
                <label>O\'quv yili</label>
                <select name="academic_year_id" class="form-control" required>
                    @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $grade->academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-warning">Yangilash</button></div>
    </form>
</div>
@endsection
