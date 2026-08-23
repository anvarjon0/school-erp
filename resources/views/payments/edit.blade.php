@extends('layouts.app')
@section('title', 'To\'lovni tahrirlash')
@section('page-title', 'To\'lovni tahrirlash')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('payments.index') }}">To\'lovlar</a></li>
<li class="breadcrumb-item active">Tahrirlash</li>
@endsection

@section('content')
<div class="card card-warning">
    <div class="card-header"><h3 class="card-title">Kvitansiya: {{ $payment->receipt_number }}</h3></div>
    <form action="{{ route('payments.update', $payment) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <p><strong>O\'quvchi:</strong> {{ $payment->student->full_name }}</p>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>To\'lov usuli</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Naqd pul</option>
                            <option value="card" {{ $payment->payment_method == 'card' ? 'selected' : '' }}>Plastik karta</option>
                            <option value="bank" {{ $payment->payment_method == 'bank' ? 'selected' : '' }}>Bank o\'tkazmasi</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Chegirma</label>
                        <input type="number" name="discount_amount" class="form-control" value="{{ $payment->discount_amount }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>To\'langan summa</label>
                        <input type="number" name="paid_amount" class="form-control" value="{{ $payment->paid_amount }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Izoh</label>
                <textarea name="note" class="form-control" rows="2">{{ $payment->note }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Yangilash</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Bekor qilish</a>
        </div>
    </form>
</div>
@endsection
