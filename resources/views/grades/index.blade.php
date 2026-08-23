@extends('layouts.app')
@section('title', 'Sinflar')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sinflar ro\'yxati</h3>
        <a href="{{ route('grades.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Yangi sinf</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sinf nomi</th>
                    <th>Daraja</th>
                    <th>Oylik to\'lov</th>
                    <th>O\'quv yili</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grades as $grade)
                <tr>
                    <td>{{ $grade->name }}</td>
                    <td>{{ $grade->level }}</td>
                    <td>{{ number_format($grade->monthly_fee) }} so\'m</td>
                    <td>{{ $grade->academicYear->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
