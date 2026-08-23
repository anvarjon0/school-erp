@extends('layouts.adminlte_app')
@section('title', 'O\'quv yillari')
@section('page-title', 'O\'quv yillari')
@section('breadcrumb')
<li class="breadcrumb-item active">O\'quv yillari</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ro\'yxat</h3>
        <div class="card-tools">
            <a href="{{ route('academic-years.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Yangi qo\'shish</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomi</th>
                    <th>Boshlanish</th>
                    <th>Tugash</th>
                    <th>Holat</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($academicYears as $ay)
                <tr>
                    <td>{{ $ay->name }}</td>
                    <td>{{ $ay->start_date->format('d.m.Y') }}</td>
                    <td>{{ $ay->end_date->format('d.m.Y') }}</td>
                    <td>
                        @if($ay->is_current)
                        <span class="badge badge-success">Joriy</span>
                        @else
                        <span class="badge badge-secondary">Arxiv</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('academic-years.edit', $ay) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
