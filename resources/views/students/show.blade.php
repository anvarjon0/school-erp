@extends('layouts.adminlte_app')

@section('title', $student->full_name)
@section('page-title', 'O\'quvchi Profili')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('students.index') }}">O'quvchilar</a></li>
<li class="breadcrumb-item active">{{ $student->student_id }}</li>
@endsection

@section('content')
<div class="row">
    <!-- Chap tomon: O'quvchi ma'lumotlari -->
    <div class="col-lg-4 mb-3">
        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-body box-profile text-center">
                @if($student->photo)
                    <img class="profile-user-img img-fluid img-circle shadow-sm mb-3" style="width: 110px; height: 110px; object-fit: cover;" src="{{ asset('storage/' . $student->photo) }}" alt="">
                @else
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm mb-3 text-bold" style="width: 100px; height: 100px; font-size: 36px;">
                        {{ mb_substr($student->first_name, 0, 1) }}{{ mb_substr($student->last_name, 0, 1) }}
                    </div>
                @endif

                <h3 class="profile-username text-center font-weight-bold">{{ $student->full_name }}</h3>
                <p class="text-muted text-center"><span class="badge badge-secondary px-3 py-1">ID: {{ $student->student_id }}</span></p>

                <ul class="list-group list-group-unbordered text-left mb-3">
                    <li class="list-group-item">
                        <b>Sinf & Bo'lim:</b>
                        <span class="float-right font-weight-bold text-primary">{{ $student->grade->name ?? '-' }} ({{ $student->section->name ?? '-' }})</span>
                    </li>
                    <li class="list-group-item">
                        <b>O'quv Yili:</b>
                        <span class="float-right">{{ $student->academicYear->name ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Oylik To'lov Miqdori:</b>
                        <span class="float-right font-weight-bold text-success">{{ number_format($student->grade->monthly_fee ?? 0) }} so'm</span>
                    </li>
                    <li class="list-group-item">
                        <b>Jinsi:</b>
                        <span class="float-right">{{ $student->gender == 'male' ? 'O\'g\'il bola' : 'Qiz bola' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Tug'ilgan Sana:</b>
                        <span class="float-right">{{ $student->date_of_birth ? $student->date_of_birth->format('d.m.Y') : '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Qabul Sanasi:</b>
                        <span class="float-right">{{ $student->admission_date ? $student->admission_date->format('d.m.Y') : '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Holat:</b>
                        <span class="float-right">
                            @if($student->status == 'active')<span class="badge badge-success">Faol</span>
                            @elseif($student->status == 'graduated')<span class="badge badge-primary">Bitirgan</span>
                            @elseif($student->status == 'expelled')<span class="badge badge-danger">Chetlashtirilgan</span>
                            @else<span class="badge badge-warning">Ko'chirilgan</span>@endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <b>Manzili:</b>
                        <span class="float-right text-right small text-muted">{{ $student->address ?? '-' }}</span>
                    </li>
                </ul>

                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('administrator'))
                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-block font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> O'quvchini Tahrirlash
                </a>
                @endif
            </div>
        </div>

        @if($student->parentInfo)
        <div class="card card-outline card-info shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold"><i class="fas fa-users mr-2 text-info"></i> Ota-ona Ma'lumotlari</h3>
            </div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt class="text-muted"><i class="fas fa-male mr-1"></i> Otasi:</dt>
                    <dd class="font-weight-bold">{{ $student->parentInfo->father_name ?? '-' }} ({{ $student->parentInfo->father_phone ?? 'Telefon yo\'q' }})</dd>

                    <dt class="text-muted"><i class="fas fa-female mr-1"></i> Onasi:</dt>
                    <dd class="font-weight-bold">{{ $student->parentInfo->mother_name ?? '-' }} ({{ $student->parentInfo->mother_phone ?? 'Telefon yo\'q' }})</dd>

                    <dt class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Manzil:</dt>
                    <dd class="small text-muted">{{ $student->parentInfo->address ?? '-' }}</dd>
                </dl>
            </div>
        </div>
        @endif
    </div>

    <!-- O'ng tomon: To'lovlar va Davomat tarixi -->
    <div class="col-lg-8 mb-3">
        <div class="card card-outline card-success shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title text-bold"><i class="fas fa-money-bill-wave mr-2 text-success"></i> To'lovlar Tarixi</h3>
                <div class="card-tools">
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasAnyRole(['administrator', 'accountant']))
                    <a href="{{ route('payments.create') }}?student_id={{ $student->id }}" class="btn btn-success btn-sm rounded-pill px-3">
                        <i class="fas fa-plus mr-1"></i> To'lov Qabul Qilish
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Kvitansiya #</th>
                                <th>To'lov Turi</th>
                                <th>Oy & Yil</th>
                                <th>To'langan Summa</th>
                                <th>Usuli</th>
                                <th>Holati</th>
                                <th>Sana</th>
                                <th class="text-center">Kvitansiya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->payments as $payment)
                            <tr>
                                <td><code>{{ $payment->receipt_number }}</code></td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $payment->payment_type == 'monthly' ? 'Oylik To\'lov' : ($payment->payment_type == 'admission' ? 'Qabul to\'lovi' : 'Boshqa') }}
                                    </span>
                                </td>
                                <td>{{ $payment->month_name }} {{ $payment->year }}</td>
                                <td class="font-weight-bold text-success">{{ number_format($payment->paid_amount) }} so'm</td>
                                <td>
                                    {{ $payment->payment_method == 'cash' ? 'Naqd' : ($payment->payment_method == 'card' ? 'Karta' : 'Bank o\'tkazmasi') }}
                                </td>
                                <td>
                                    @if($payment->status == 'paid')<span class="badge badge-success">To'liq</span>
                                    @elseif($payment->status == 'partial')<span class="badge badge-warning">Qisman</span>
                                    @else<span class="badge badge-danger">Kutilmoqda</span>@endif
                                </td>
                                <td>{{ $payment->created_at->format('d.m.Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('payments.receipt', $payment) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Chekni ko'rish / chop etish">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                                    Hali to'lovlar mavjud emas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
