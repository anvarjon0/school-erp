@extends('layouts.adminlte_app')
@section('title', 'Bo\'limlar')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Bo\'limlar (Guruhlar)</h3>
        <a href="{{ route('sections.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Yangi bo\'lim</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sinf</th>
                    <th>Bo\'lim nomi</th>
                    <th>O\'qituvchi (Rahbar)</th>
                    <th>Sig\'im</th>
                    <th>O\'quvchilar soni</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                <tr>
                    <td>{{ $section->grade->name ?? '-' }}</td>
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->teacher->name ?? 'Biriktirilmagan' }}</td>
                    <td>{{ $section->capacity }}</td>
                    <td>{{ $section->students_count ?? 0 }}</td>
                    <td><a href="{{ route('sections.edit', $section) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
