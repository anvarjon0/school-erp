@extends('layouts.adminlte_app')
@section('title', 'Xarajat kategoriyalari')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Kategoriyalar</h3>
        <a href="{{ route('expense-categories.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Yangi</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Nomi</th><th>Tavsif</th><th>Amallar</th></tr></thead>
            <tbody>
                @foreach($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->description }}</td>
                    <td><a href="{{ route('expense-categories.edit', $cat) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
