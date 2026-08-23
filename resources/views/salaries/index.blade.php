@extends('layouts.adminlte_app')
@section('title', 'Oylik maoshlar')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Maoshlar ro\'yxati</h3>
        <a href="{{ route('salaries.create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Yangi to\'lov</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Xodim</th>
                    <th>Oy / Yil</th>
                    <th>Asosiy maosh</th>
                    <th>Bonus</th>
                    <th>Ushlanma</th>
                    <th>To\'langan summa</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salaries as $salary)
                <tr>
                    <td>{{ $salary->user->name ?? '-' }}</td>
                    <td>{{ $salary->month_name }} {{ $salary->year }}</td>
                    <td>{{ number_format($salary->base_salary) }}</td>
                    <td>{{ number_format($salary->bonus) }}</td>
                    <td>{{ number_format($salary->deduction) }}</td>
                    <td>{{ number_format($salary->net_salary) }}</td>
                    <td>{{ $salary->payment_date->format('d.m.Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $salaries->links() }}
    </div>
</div>
@endsection
