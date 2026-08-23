@extends('layouts.app')

@section('title', 'Boshqaruv Paneli')
@section('page-title', 'Boshqaruv Paneli')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Tezkor Qutlov & Sana --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h3 class="font-weight-bold text-dark m-0">Xush kelibsiz, {{ $user->name }} 👋</h3>
        <p class="text-muted mb-0">Maktab boshqaruvi va moliyaviy ko'rsatkichlarning umumiy statistikasi.</p>
    </div>
    <div class="mt-2 mt-sm-0">
        <span class="badge badge-secondary px-3 py-2 font-weight-bold shadow-sm">
            <i class="far fa-clock mr-1 text-primary"></i> {{ now()->translatedFormat('d-F, Y-yil (l)') }}
        </span>
    </div>
</div>

{{-- Super Admin / Administrator / Buxgalter Dashboard --}}
@if($user->isSuperAdmin() || $user->hasRole('administrator') || $user->hasRole('accountant'))

<!-- 4 Asosiy KPI Kartalari -->
<div class="row g-3 mb-4">
    <!-- 1. Jami O'quvchilar -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <a href="{{ route('students.index') }}" class="kpi-card kpi-card-indigo">
            <div class="kpi-badge"><i class="fas fa-user-graduate mr-1"></i> O'quvchilar</div>
            <div class="kpi-value">{{ number_format($totalStudents ?? 0) }}</div>
            <div class="kpi-label">Faol O'quvchilar Soni</div>
            <i class="fas fa-graduation-cap kpi-icon-bg"></i>
        </a>
    </div>

    <!-- 2. Oylik Daromad -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <a href="{{ route('payments.index') }}" class="kpi-card kpi-card-emerald">
            <div class="kpi-badge"><i class="fas fa-arrow-down mr-1"></i> Oylik Kassa</div>
            <div class="kpi-value">{{ number_format($monthlyIncome ?? 0) }} <small style="font-size: 14px;">so'm</small></div>
            <div class="kpi-label">Joriy Oydagi Tushum</div>
            <i class="fas fa-coins kpi-icon-bg"></i>
        </a>
    </div>

    <!-- 3. Oylik Xarajat -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <a href="{{ route('expenses.index') }}" class="kpi-card kpi-card-amber">
            <div class="kpi-badge"><i class="fas fa-arrow-up mr-1"></i> Oylik Chiqim</div>
            <div class="kpi-value">{{ number_format($monthlyExpense ?? 0) }} <small style="font-size: 14px;">so'm</small></div>
            <div class="kpi-label">Joriy Oydagi Xarajatlar</div>
            <i class="fas fa-receipt kpi-icon-bg"></i>
        </a>
    </div>

    <!-- 4. Qarzdor O'quvchilar -->
    <div class="col-xl-3 col-sm-6 mb-3">
        <a href="{{ route('payments.debtors') }}" class="kpi-card kpi-card-rose">
            <div class="kpi-badge"><i class="fas fa-exclamation-triangle mr-1"></i> Qarzdorlik</div>
            <div class="kpi-value">{{ number_format($debtorCount ?? 0) }} <small style="font-size: 14px;">nafar</small></div>
            <div class="kpi-label">To'lov Qilmagan O'quvchilar</div>
            <i class="fas fa-user-times kpi-icon-bg"></i>
        </a>
    </div>
</div>

<!-- Tezkor Harakatlar (Quick Actions) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title m-0 font-weight-bold text-dark">
            <i class="fas fa-bolt text-warning mr-2"></i> Tezkor Amallar
        </h5>
    </div>
    <div class="card-body py-3">
        <div class="row g-2">
            @if($user->isSuperAdmin() || $user->hasAnyRole(['administrator', 'accountant']))
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('payments.create') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-success text-white">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">To'lov Qabul Qilish</div>
                        <small class="text-muted">Kvitansiya berish</small>
                    </div>
                </a>
            </div>
            @endif

            @if($user->isSuperAdmin() || $user->hasRole('administrator'))
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('students.create') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-primary text-white">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">Yangi O'quvchi Qabul</div>
                        <small class="text-muted">Ro'yxatga olish</small>
                    </div>
                </a>
            </div>
            @endif

            @if($user->isSuperAdmin() || $user->hasRole('accountant'))
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('expenses.create') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-danger text-white">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">Xarajat Kiritish</div>
                        <small class="text-muted">Chiqim chekini biriktirish</small>
                    </div>
                </a>
            </div>
            @endif

            @if($user->isSuperAdmin())
            <div class="col-md-3 col-6 mb-2">
                <a href="{{ route('users.create') }}" class="quick-action-btn">
                    <div class="quick-action-icon bg-indigo text-white" style="background-color: #6366f1;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold">Yangi Xodim Qo'shish</div>
                        <small class="text-muted">Rol belgilash</small>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Grafik va So'nggi To'lovlar -->
<div class="row">
    <!-- Daromad va Xarajat Grafigi -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 font-weight-bold text-dark">
                    <i class="fas fa-chart-bar text-primary mr-2"></i> Oylik Daromad va Xarajat Tahlili (6 oylik)
                </h5>
                <span class="badge badge-light border">So'mda</span>
            </div>
            <div class="card-body">
                <canvas id="incomeExpenseChart" style="min-height: 280px; height: 280px; max-height: 280px; width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- So'nggi To'lovlar -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0 font-weight-bold text-dark">
                    <i class="fas fa-history text-success mr-2"></i> So'nggi To'lovlar
                </h5>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-primary btn-xs rounded-pill px-2">Barchasi</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse(($recentPayments ?? []) as $payment)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-light text-success rounded-circle d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 38px; height: 38px; font-size: 14px; background: #ecfdf5;">
                                💳
                            </div>
                            <div>
                                <span class="font-weight-bold text-dark d-block">{{ $payment->student->full_name ?? 'Noma\'lum o\'quvchi' }}</span>
                                <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ $payment->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success font-weight-bold" style="font-size: 13px;">+{{ number_format($payment->paid_amount) }}</span>
                            <small class="d-block text-muted">{{ $payment->receipt_number }}</small>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-5">
                        <i class="fas fa-receipt fa-3x mb-3 text-muted d-block opacity-50"></i>
                        Hozircha to'lovlar mavjud emas.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Sinf Rahbari Dashboard --}}
@if($user->hasRole('class-teacher') && !$user->isSuperAdmin())
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('students.index') }}" class="kpi-card kpi-card-indigo">
            <div class="kpi-badge"><i class="fas fa-users mr-1"></i> Mening Sinfim</div>
            <div class="kpi-value">{{ $myStudents ?? 0 }} <small style="font-size: 14px;">nafar</small></div>
            <div class="kpi-label">Biriktirilgan O'quvchilar</div>
            <i class="fas fa-user-graduate kpi-icon-bg"></i>
        </a>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <div class="kpi-card kpi-card-emerald">
            <div class="kpi-badge"><i class="fas fa-check mr-1"></i> Davomat</div>
            <div class="kpi-value">{{ $todayPresent ?? 0 }} <small style="font-size: 14px;">nafar</small></div>
            <div class="kpi-label">Bugun Darsga Kelganlar</div>
            <i class="fas fa-check-circle kpi-icon-bg"></i>
        </div>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <div class="kpi-card kpi-card-rose">
            <div class="kpi-badge"><i class="fas fa-times mr-1"></i> Sababli / Sababsiz</div>
            <div class="kpi-value">{{ $todayAbsent ?? 0 }} <small style="font-size: 14px;">nafar</small></div>
            <div class="kpi-label">Bugun Kelmaganlar</div>
            <i class="fas fa-times-circle kpi-icon-bg"></i>
        </div>
    </div>

    <div class="col-lg-3 col-6 mb-3">
        <a href="{{ route('attendances.create') }}" class="kpi-card kpi-card-amber">
            <div class="kpi-badge"><i class="fas fa-edit mr-1"></i> Jurnal</div>
            <div class="kpi-value">{{ $todayAttendance ?? 0 }} <small style="font-size: 14px;">belgilandi</small></div>
            <div class="kpi-label">Bugungi Davomat Holati</div>
            <i class="fas fa-clipboard-check kpi-icon-bg"></i>
        </a>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if(isset($chartLabels))
<script>
document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Daromad (Tushum)',
                    data: @json($chartIncome),
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                },
                {
                    label: 'Xarajat (Chiqim)',
                    data: @json($chartExpense),
                    backgroundColor: '#f43f5e',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { family: 'Plus Jakarta Sans', size: 12, weight: 600 },
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 700 },
                    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + Number(context.parsed.y).toLocaleString() + " so'm";
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 600 } }
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                            return value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endif
@endpush
