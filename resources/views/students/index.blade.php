@extends('layouts.adminlte_app')

@section('title', 'O\'quvchilar')
@section('page-title', 'O\'quvchilar Boshqaruvi')

@section('breadcrumb')
<li class="breadcrumb-item active">O'quvchilar</li>
@endsection

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white">
        <h3 class="card-title text-bold"><i class="fas fa-user-graduate mr-2 text-primary"></i> O'quvchilar Ro'yxati</h3>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('administrator'))
        <div class="card-tools">
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-user-plus mr-1"></i> Yangi O'quvchi Qabul Qilish
            </a>
        </div>
        @endif
    </div>

    <div class="card-body">
        <!-- Filtrlar -->
        <form method="GET" action="{{ route('students.index') }}" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-4 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text bg-light"><i class="fas fa-search"></i></span></div>
                        <input type="text" name="search" class="form-control" placeholder="Ism, familiya yoki ID..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="grade_id" class="form-control select2">
                        <option value="">-- Barcha Sinflar --</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }} ({{ number_format($grade->monthly_fee) }} so'm)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <select name="status" class="form-control">
                        <option value="">-- Barcha Holatlar --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Faol</option>
                        <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Bitirgan</option>
                        <option value="expelled" {{ request('status') == 'expelled' ? 'selected' : '' }}>Chetlashtirilgan</option>
                        <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Ko'chirilgan</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <button type="submit" class="btn btn-info px-3"><i class="fas fa-filter mr-1"></i> Qidirish</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary px-2" title="Tozalash"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>

        <!-- O'quvchilar Jadvali -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Student ID</th>
                        <th>O'quvchi F.I.Sh</th>
                        <th>Sinf & Bo'lim</th>
                        <th>Jinsi</th>
                        <th>Qabul Sanasi</th>
                        <th>Holati</th>
                        <th style="width: 150px;" class="text-center">Amallar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                        <td><span class="badge badge-secondary px-2 py-1" style="font-size: 13px;"><code>{{ $student->student_id }}</code></span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle mr-2" style="width: 38px; height: 38px; object-fit: cover;" alt="">
                                @else
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-2 text-bold" style="width: 38px; height: 38px; font-size: 15px;">
                                        {{ mb_substr($student->first_name, 0, 1) }}{{ mb_substr($student->last_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('students.show', $student) }}" class="font-weight-bold text-dark d-block">
                                        {{ $student->full_name }}
                                    </a>
                                    @if($student->parentInfo && $student->parentInfo->father_phone)
                                        <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i> {{ $student->parentInfo->father_phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary px-2 py-1">{{ $student->grade->name ?? '-' }}</span>
                            <span class="badge badge-info px-2 py-1">{{ $student->section->name ?? '-' }}</span>
                        </td>
                        <td>{{ $student->gender == 'male' ? 'O\'g\'il bola' : 'Qiz bola' }}</td>
                        <td>{{ $student->admission_date ? $student->admission_date->format('d.m.Y') : '-' }}</td>
                        <td>
                            @php
                                $statusBadge = match($student->status) {
                                    'active' => 'success',
                                    'graduated' => 'primary',
                                    'expelled' => 'danger',
                                    default => 'warning'
                                };
                                $statusLabel = match($student->status) {
                                    'active' => 'Faol',
                                    'graduated' => 'Bitirgan',
                                    'expelled' => 'Chetlashtirilgan',
                                    default => 'Ko\'chirilgan'
                                };
                            @endphp
                            <span class="badge badge-{{ $statusBadge }} px-2 py-1">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info" title="Profil va To'lovlar">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if(auth()->user()->isSuperAdmin() || auth()->user()->hasRole('administrator'))
                                <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning" title="Tahrirlash">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete" title="O'chirish">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-user-graduate fa-3x mb-2 d-block"></i>
                            Hech qanday o'quvchi topilmadi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
            {{ $students->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
