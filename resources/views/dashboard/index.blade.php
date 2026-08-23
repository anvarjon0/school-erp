@extends('layouts.app')

@section('title', 'Bosh sahifa')
@section('page-title', 'Boshqaruv paneli')

@section('content')
@php $user = auth()->user(); @endphp

<div class="row mb-4">
    <div class="col-12">
        <h4 class="m-0 text-dark">Xush kelibsiz, {{ $user->name }}!</h4>
        <p class="text-muted">Tizimdagi so'nggi ma'lumotlar va qisqacha hisobotlar</p>
    </div>
</div>

{{-- Super Admin & Administrator & Accountant Dashboard --}}
@if($user->isSuperAdmin() || $user->hasRole('administrator') || $user->hasRole('accountant'))
<div class="row">
    @if($user->isSuperAdmin() || $user->hasRole('administrator') || $user->hasRole('class-teacher'))
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalStudents ?? 0) }}</h3>
                <p>Jami O'quvchilar</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <a href="{{ route('students.index') }}" class="small-box-footer">Barchasini ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'accountant']))
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($monthlyIncome ?? 0) }}<sup style="font-size: 16px">UZS</sup></h3>
                <p>Bu Oydagi Daromad</p>
            </div>
            <div class="icon">
                <i class="fas fa-coins"></i>
            </div>
            <a href="{{ route('payments.index') }}" class="small-box-footer">Barchasini ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    @if($user->isSuperAdmin() || $user->hasRole('accountant'))
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($monthlyExpense ?? 0) }}<sup style="font-size: 16px">UZS</sup></h3>
                <p>Bu Oydagi Xarajat</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="{{ route('expenses.index') }}" class="small-box-footer">Barchasini ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'accountant']))
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($debtorCount ?? 0) }}</h3>
                <p>Qarzdorlar</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="{{ route('payments.debtors') }}" class="small-box-footer" style="color: #fff !important;">Ro'yxatni ko'rish <i class="fas fa-arrow-circle-right" style="color: #fff !important;"></i></a>
        </div>
    </div>
    @endif
</div>

<!-- Grafik va So'nggi To'lovlar -->
<div class="row mt-4">
    <!-- Daromad va Xarajat Grafigi -->
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i>
                    6 Oylik Daromad va Xarajat
                </h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="incomeExpenseChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- So'nggi To'lovlar -->
    <div class="col-lg-4">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    So'nggi To'lovlar
                </h3>
                <div class="card-tools">
                    <a href="{{ route('payments.index') }}" class="btn btn-tool btn-sm">Barchasi</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>O'quvchi</th>
                            <th>Summa</th>
                            <th>Sana</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($recentPayments ?? []) as $payment)
                        <tr>
                            <td>{{ $payment->student->full_name ?? 'Noma\'lum o\'quvchi' }}</td>
                            <td><span class="badge bg-success">+{{ number_format($payment->paid_amount) }}</span></td>
                            <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">Hozircha to'lovlar mavjud emas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Sinf Rahbari Dashboard --}}
@if($user->hasRole('class-teacher') && !$user->isSuperAdmin())
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $myStudents ?? 0 }}</h3>
                <p>Mening Sinfim</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('students.index') }}" class="small-box-footer">Ro'yxatni ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $todayPresent ?? 0 }}</h3>
                <p>Bugun kelganlar</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('attendances.index') }}" class="small-box-footer">Barchasini ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $todayAbsent ?? 0 }}</h3>
                <p>Bugun kelmaganlar</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('attendances.index') }}" class="small-box-footer">Barchasini ko'rish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $todayAttendance ?? 0 }}</h3>
                <p>Davomat (belgilangan)</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <a href="{{ route('attendances.create') }}" class="small-box-footer">Davomat olish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if(isset($chartLabels))
<script>
$(function () {
    var ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Daromad (Tushum)',
                    data: @json($chartIncome),
                    backgroundColor: 'rgba(40, 167, 69, 0.9)',
                    borderColor: 'rgba(40, 167, 69, 0.8)',
                    borderWidth: 1
                },
                {
                    label: 'Xarajat (Chiqim)',
                    data: @json($chartExpense),
                    backgroundColor: 'rgba(220, 53, 69, 0.9)',
                    borderColor: 'rgba(220, 53, 69, 0.8)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }
    });
});
</script>
@endif
@endpush
