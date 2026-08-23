@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php $user = auth()->user(); @endphp

{{-- Super Admin / Administrator Dashboard --}}
@if($user->isSuperAdmin() || $user->hasRole('administrator') || $user->hasRole('accountant'))
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalStudents ?? 0) }}</h3>
                <p>Jami o'quvchilar</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            @if($user->isSuperAdmin() || $user->hasRole('administrator'))
            <a href="{{ route('students.index') }}" class="small-box-footer">Batafsil <i class="fas fa-arrow-circle-right"></i></a>
            @else
            <span class="small-box-footer">&nbsp;</span>
            @endif
        </div>
    </div>

    @if($user->isSuperAdmin())
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalUsers ?? 0) }}</h3>
                <p>Foydalanuvchilar</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('users.index') }}" class="small-box-footer">Batafsil <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($monthlyIncome ?? 0) }}</h3>
                <p>Oylik daromad (so'm)</p>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
            <a href="{{ route('payments.index') }}" class="small-box-footer">Batafsil <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($debtorCount ?? 0) }}</h3>
                <p>Qarzdor o'quvchilar</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="{{ route('payments.debtors') }}" class="small-box-footer">Batafsil <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-area mr-1"></i> Daromad va Xarajat</h3>
            </div>
            <div class="card-body">
                <canvas id="incomeExpenseChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-1"></i> So'nggi to'lovlar</h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse(($recentPayments ?? []) as $payment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $payment->student->full_name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $payment->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                        <span class="badge badge-success">{{ number_format($payment->paid_amount) }} so'm</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">To'lovlar yo'q</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Class Teacher Dashboard --}}
@if($user->hasRole('class-teacher') && !$user->isSuperAdmin())
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $myStudents ?? 0 }}</h3>
                <p>Mening o'quvchilarim</p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('students.index') }}" class="small-box-footer">Batafsil <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $todayPresent ?? 0 }}</h3>
                <p>Bugun kelganlar</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $todayAbsent ?? 0 }}</h3>
                <p>Bugun kelmaganlar</p>
            </div>
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <span class="small-box-footer">&nbsp;</span>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $todayAttendance ?? 0 }}</h3>
                <p>Davomat yuritilgan</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-check"></i></div>
            <a href="{{ route('attendances.create') }}" class="small-box-footer">Davomat yuritish <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
@if(isset($chartLabels))
<script>
var ctx = document.getElementById('incomeExpenseChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Daromad',
            data: @json($chartIncome),
            backgroundColor: 'rgba(40, 167, 69, 0.7)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }, {
            label: 'Xarajat',
            data: @json($chartExpense),
            backgroundColor: 'rgba(220, 53, 69, 0.7)',
            borderColor: 'rgba(220, 53, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + " so'm";
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + " so'm";
                    }
                }
            }
        }
    }
});
</script>
@endif
@endpush
