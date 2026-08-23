@extends('layouts.app')
@section('title', 'Davomat yo\'qlash')
@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Davomat belgishlash</h3></div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="section_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">Bo\'limni tanlang...</option>
                        @foreach($sections as $section)<option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}" readonly>
                </div>
            </div>
        </form>
        
        @if(isset($students) && $students->count() > 0)
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="section_id" value="{{ request('section_id') }}">
            <input type="hidden" name="attendance_date" value="{{ request('date', date('Y-m-d')) }}">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>O\'quvchi</th>
                        <th>Kelgan</th>
                        <th>Kelmagan</th>
                        <th>Kechikkan</th>
                        <th>Ruxsatli</th>
                        <th>Izoh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->full_name }}</td>
                        <td><input type="radio" name="attendance[{{ $student->id }}][status]" value="present" checked></td>
                        <td><input type="radio" name="attendance[{{ $student->id }}][status]" value="absent"></td>
                        <td><input type="radio" name="attendance[{{ $student->id }}][status]" value="late"></td>
                        <td><input type="radio" name="attendance[{{ $student->id }}][status]" value="excused"></td>
                        <td><input type="text" name="attendance[{{ $student->id }}][note]" class="form-control form-control-sm"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-3">Saqlash</button>
        </form>
        @endif
    </div>
</div>
@endsection
