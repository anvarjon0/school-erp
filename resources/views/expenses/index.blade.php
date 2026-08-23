@extends('layouts.adminlte_app')
@section('title', 'Xarajatlar')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Xarajatlar ro\'yxati</h3>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Yangi xarajat</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sana</th>
                    <th>Kategoriya</th>
                    <th>Sarlavha</th>
                    <th>Summa</th>
                    <th>Kim tomonidan</th>
                    <th>Amallar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d.m.Y') }}</td>
                    <td>{{ $expense->category->name ?? '-' }}</td>
                    <td>{{ $expense->title }}</td>
                    <td>{{ number_format($expense->amount) }} so\'m</td>
                    <td>{{ $expense->user->name ?? '-' }}</td>
                    <td><a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $expenses->links() }}
    </div>
</div>
@endsection
