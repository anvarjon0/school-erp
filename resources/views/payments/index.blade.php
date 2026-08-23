@extends('layouts.adminlte_app')
@section('title', 'To\'lovlar')
@section('page-title', 'Barcha to\'lovlar')
@section('breadcrumb')
<li class="breadcrumb-item active">To\'lovlar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">To\'lovlar ro\'yxati</h3>
        <div class="card-tools">
            <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Yangi to\'lov</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Kvitansiya yoki o\'quvchi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="month" class="form-control">
                        <option value="">Barcha oylar</option>
                        @foreach(['Yanvar','Fevral','Mart','Aprel','May','Iyun','Iyul','Avgust','Sentabr','Oktabr','Noyabr','Dekabr'] as $i => $month)
                        <option value="{{ $i+1 }}" {{ request('month') == $i+1 ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_type" class="form-control">
                        <option value="">Barcha turlar</option>
                        <option value="monthly" {{ request('payment_type') == 'monthly' ? 'selected' : '' }}>Oylik to\'lov</option>
                        <option value="admission" {{ request('payment_type') == 'admission' ? 'selected' : '' }}>Qabul to\'lovi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Qidirish</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-secondary"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kvitansiya</th>
                        <th>O\'quvchi</th>
                        <th>Turi</th>
                        <th>Oy</th>
                        <th>Summa</th>
                        <th>Holat</th>
                        <th>Sana</th>
                        <th>Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $payment->receipt_number }}</code></td>
                        <td>{{ $payment->student->full_name ?? '-' }}</td>
                        <td>{{ $payment->payment_type == 'monthly' ? 'Oylik' : 'Boshqa' }}</td>
                        <td>{{ $payment->month_name }}</td>
                        <td>{{ number_format($payment->paid_amount) }} so\'m</td>
                        <td>
                            @if($payment->status == 'paid')<span class="badge badge-success">To\'langan</span>
                            @elseif($payment->status == 'partial')<span class="badge badge-warning">Qisman</span>
                            @else<span class="badge badge-danger">Kutilmoqda</span>@endif
                        </td>
                        <td>{{ $payment->created_at->format('d.m.Y') }}</td>
                        <td>
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">Ma\'lumot topilmadi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $payments->appends(request()->query())->links() }}
    </div>
</div>
@endsection
