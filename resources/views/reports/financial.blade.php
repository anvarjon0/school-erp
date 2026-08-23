@extends('layouts.adminlte_app')
@section('title', 'Moliyaviy hisobot')
@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Yillik moliya</h3></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jami tushumlar</span>
                        <span class="info-box-number">{{ number_format($totalIncome ?? 0) }} so\'m</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Jami xarajatlar</span>
                        <span class="info-box-number">{{ number_format($totalExpense ?? 0) }} so\'m</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Foyda</span>
                        <span class="info-box-number">{{ number_format(($totalIncome ?? 0) - ($totalExpense ?? 0)) }} so\'m</span>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted mt-4">Hisobot grafiklari tez orada qo\'shiladi.</p>
    </div>
</div>
@endsection
