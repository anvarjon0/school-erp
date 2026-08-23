@extends('layouts.adminlte_app')

@section('title', 'O\'quvchini Tahrirlash')
@section('page-title', 'O\'quvchini Tahrirlash')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('students.index') }}">O'quvchilar</a></li>
<li class="breadcrumb-item active">{{ $student->full_name }}</li>
@endsection

@section('content')
<form action="{{ route('students.update', $student) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- O'quvchi ma'lumotlari -->
        <div class="col-lg-8 mb-3">
            <div class="card card-outline card-warning shadow-sm h-100">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-user-edit mr-2 text-warning"></i> {{ $student->full_name }} ({{ $student->student_id }})</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Ismi <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $student->first_name) }}" required>
                            @error('first_name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Familiyasi <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $student->last_name) }}" required>
                            @error('last_name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Tug'ilgan Sanasi</label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Jinsi <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>O'g'il bola</option>
                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Qiz bola</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Qabul Sanasi <span class="text-danger">*</span></label>
                            <input type="date" name="admission_date" class="form-control" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label font-weight-bold">Holat <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Faol</option>
                                <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Bitirgan</option>
                                <option value="expelled" {{ old('status', $student->status) == 'expelled' ? 'selected' : '' }}>Chetlashtirilgan</option>
                                <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>Ko'chirilgan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Sinf <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control select2" required>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id', $student->grade_id) == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} ({{ number_format($grade->monthly_fee) }} so'm)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Bo'lim <span class="text-danger">*</span></label>
                            <select name="section_id" id="section_id" class="form-control select2" required>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id', $student->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">O'quv Yili <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-control" required>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ old('academic_year_id', $student->academic_year_id) == $ay->id ? 'selected' : '' }}>
                                        {{ $ay->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Yashash Manzili</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $student->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">O'quvchi Rasmi</label>
                        <div class="d-flex align-items-center">
                            @if($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle mr-3" style="width: 45px; height: 45px; object-fit: cover;" alt="">
                            @endif
                            <div class="custom-file flex-grow-1">
                                <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                                <label class="custom-file-label" for="photoInput">Yangi rasm tanlang...</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ota-ona ma'lumotlari -->
        <div class="col-lg-4 mb-3">
            @php $parent = $student->parentInfo; @endphp
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-users mr-2 text-info"></i> Ota-ona Ma'lumotlari</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Otasining F.I.Sh</label>
                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $parent->father_name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Otasining Telefoni</label>
                        <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone', $parent->father_phone ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Onasining F.I.Sh</label>
                        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name', $parent->mother_name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Onasining Telefoni</label>
                        <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone', $parent->mother_phone ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Oila Manzili</label>
                        <textarea name="parent_address" class="form-control" rows="2">{{ old('parent_address', $parent->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-warning btn-block btn-lg shadow font-weight-bold mb-2">
                <i class="fas fa-save mr-1"></i> O'zgarishlarni Saqlash
            </button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-block">Bekor qilish</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    $('#photoInput').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Yangi rasm tanlang...');
    });

    $('#grade_id').on('change', function() {
        var gradeId = $(this).val();
        var sectionSelect = $('#section_id');
        sectionSelect.html('<option value="">Yuklanmoqda...</option>');

        if (gradeId) {
            $.get('/api/sections-by-grade/' + gradeId, function(data) {
                sectionSelect.html('<option value="">-- Bo\'limni Tanlang --</option>');
                data.forEach(function(section) {
                    sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                });
            });
        }
    });
</script>
@endpush
