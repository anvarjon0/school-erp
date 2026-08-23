@extends('layouts.adminlte_app')
@section('title', 'Qarzdorlar')
@section('page-title', 'Qarzdor o\'quvchilar')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('payments.index') }}">To\'lovlar</a></li>
<li class="breadcrumb-item active">Qarzdorlar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Qarzdorlar ro\'yxati</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="month" class="form-control">
                        @foreach(['Yanvar','Fevral','Mart','Aprel','May','Iyun','Iyul','Avgust','Sentabr','Oktabr','Noyabr','Dekabr'] as $i => $m)
                        <option value="{{ $i+1 }}" {{ request('month', date('n')) == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-info"><i class="fas fa-filter"></i> Filtrlash</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>O\'quvchi</th>
                    <th>Sinf</th>
                    <th>Oy</th>
                    <th>Qarz summasi</th>
                    <th>Aloqa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($debtors as $debtor)
                <tr>
                    <td>{{ $debtor->full_name }}</td>
                    <td>{{ $debtor->grade->name ?? '-' }} ({{ $debtor->section->name ?? '-' }})</td>
                    <td>Tanlangan oy</td>
                    <td>{{ number_format($debtor->debt_amount ?? 0) }} so\'m</td>
                    <td>{{ $debtor->parentInfo->father_phone ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Qarzdorlar yo\'q</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
