@extends('layouts.adminlte_app')
@section('title', 'O\'quvchi to\'lovlari')
@section('page-title', $student->full_name . ' to\'lovlari')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('students.index') }}">O\'quvchilar</a></li>
<li class="breadcrumb-item"><a href="{{ route('students.show', $student) }}">{{ $student->student_id }}</a></li>
<li class="breadcrumb-item active">To\'lovlar</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">To\'lovlar tarixi</h3>
        <div class="card-tools">
            <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Yangi to\'lov</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kvitansiya</th>
                    <th>Turi</th>
                    <th>Oy</th>
                    <th>Summa</th>
                    <th>Chegirma</th>
                    <th>To\'langan</th>
                    <th>Holat</th>
                    <th>Sana</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td><code>{{ $payment->receipt_number }}</code></td>
                    <td>{{ $payment->payment_type == 'monthly' ? 'Oylik' : ($payment->payment_type == 'admission' ? 'Qabul' : 'Boshqa') }}</td>
                    <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                    <td>{{ number_format($payment->amount) }}</td>
                    <td>{{ number_format($payment->discount_amount) }}</td>
                    <td>{{ number_format($payment->paid_amount) }}</td>
                    <td>
                        @if($payment->status == 'paid')<span class="badge badge-success">To\'langan</span>
                        @elseif($payment->status == 'partial')<span class="badge badge-warning">Qisman</span>
                        @else<span class="badge badge-danger">Kutilmoqda</span>@endif
                    </td>
                    <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">To\'lovlar yo\'q</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $payments->links() }}
    </div>
</div>
@endsection
