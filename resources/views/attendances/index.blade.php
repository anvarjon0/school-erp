@extends('layouts.app')
@section('title', 'Davomat')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Davomat ro\'yxati</h3>
        <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-check"></i> Davomat yo\'qlash</a>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <select name="section_id" class="form-control">
                        <option value="">Bo\'limni tanlang...</option>
                        @foreach($sections as $section)<option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info">Ko\'rish</button>
                </div>
            </div>
        </form>
        @if(isset($attendances) && $attendances->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>O\'quvchi</th>
                    <th>Holat</th>
                    <th>Izoh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                <tr>
                    <td>{{ $att->student->full_name }}</td>
                    <td>
                        @if($att->status == 'present')<span class="badge badge-success">Kelgan</span>
                        @elseif($att->status == 'absent')<span class="badge badge-danger">Kelmagan</span>
                        @elseif($att->status == 'late')<span class="badge badge-warning">Kechikkan</span>
                        @else<span class="badge badge-info">Ruxsat so\'ragan</span>@endif
                    </td>
                    <td>{{ $att->note }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-center">Ushbu sana uchun ma\'lumot topilmadi.</p>
        @endif
    </div>
</div>
@endsection
