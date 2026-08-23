@extends('layouts.app')
@section('title', 'Kvitansiya')
@section('page-title', 'Kvitansiya #' . $payment->receipt_number)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('payments.index') }}">To\'lovlar</a></li>
<li class="breadcrumb-item active">{{ $payment->receipt_number }}</li>
@endsection

@section('content')
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-school"></i> School ERP
                <small class="float-right">Sana: {{ $payment->created_at->format('d.m.Y H:i') }}</small>
            </h4>
        </div>
    </div>
    <div class="row invoice-info mt-4">
        <div class="col-sm-4 invoice-col">
            Maktab
            <address>
                <strong>School ERP Tizimi</strong><br>
                Manzil: Toshkent shahar<br>
                Tel: +998 90 123 45 67<br>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            O\'quvchi
            <address>
                <strong>{{ $payment->student->full_name }}</strong><br>
                ID: {{ $payment->student->student_id }}<br>
                Sinf: {{ $payment->student->grade->name ?? '-' }} ({{ $payment->student->section->name ?? '-' }})<br>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Kvitansiya #{{ $payment->receipt_number }}</b><br>
            <br>
            <b>To\'lov usuli:</b> {{ ucfirst($payment->payment_method) }}<br>
            <b>To\'lov turi:</b> {{ $payment->payment_type == 'monthly' ? 'Oylik to\'lov' : 'Boshqa' }}
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tavsif</th>
                        <th>Oy/Yil</th>
                        <th>Summa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>O\'quv to\'lovi</td>
                        <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                        <td>{{ number_format($payment->amount) }} so\'m</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-6">
            <p class="lead">Izoh:</p>
            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                {{ $payment->note ?? 'Izoh kiritilmagan' }}
            </p>
        </div>
        <div class="col-6">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Jami summa:</th>
                        <td>{{ number_format($payment->amount) }} so\'m</td>
                    </tr>
                    <tr>
                        <th>Chegirma:</th>
                        <td>{{ number_format($payment->discount_amount) }} so\'m</td>
                    </tr>
                    <tr>
                        <th>To\'langan summa:</th>
                        <td>{{ number_format($payment->paid_amount) }} so\'m</td>
                    </tr>
                    <tr>
                        <th>Qarz:</th>
                        <td>{{ number_format($payment->amount - $payment->discount_amount - $payment->paid_amount) }} so\'m</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="row no-print mt-4">
        <div class="col-12">
            <a href="{{ route('payments.pdf', $payment) }}" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Chop etish (PDF)</a>
        </div>
    </div>
</div>
@endsection
