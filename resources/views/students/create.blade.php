@extends('layouts.adminlte_app')

@section('title', 'Yangi O\'quvchi Qabul Qilish')
@section('page-title', 'Yangi O\'quvchi Qabul Qilish')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('students.index') }}">O'quvchilar</a></li>
<li class="breadcrumb-item active">Yangi Qabul</li>
@endsection

@section('content')
<form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- O'quvchi ma'lumotlari -->
        <div class="col-lg-8 mb-3">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-user-graduate mr-2 text-primary"></i> O'quvchi Ma'lumotlari</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Ismi <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" placeholder="Masalan: Sardor" required>
                            @error('first_name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">Familiyasi <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="Masalan: Azizov" required>
                            @error('last_name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Tug'ilgan Sanasi</label>
                            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Jinsi <span class="text-danger">*</span></label>
                            <select name="gender" class="form-control" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>O'g'il bola</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Qiz bola</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Qabul Sanasi <span class="text-danger">*</span></label>
                            <input type="date" name="admission_date" class="form-control @error('admission_date') is-invalid @enderror" value="{{ old('admission_date', date('Y-m-d')) }}" required>
                            @error('admission_date')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Sinf <span class="text-danger">*</span></label>
                            <select name="grade_id" id="grade_id" class="form-control select2 @error('grade_id') is-invalid @enderror" required>
                                <option value="">-- Sinfni Tanlang --</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                        {{ $grade->name }} ({{ number_format($grade->monthly_fee) }} so'm)
                                    </option>
                                @endforeach
                            </select>
                            @error('grade_id')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">Bo'lim (Sinf guruhi) <span class="text-danger">*</span></label>
                            <select name="section_id" id="section_id" class="form-control select2 @error('section_id') is-invalid @enderror" required>
                                <option value="">Avval sinfni tanlang</option>
                            </select>
                            @error('section_id')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label font-weight-bold">O'quv Yili <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-control" required>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ ($currentYear && $currentYear->id == $ay->id) ? 'selected' : '' }}>
                                        {{ $ay->name }} {{ $ay->is_current ? '(Joriy)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Yashash Manzili</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Toshkent sh., ...">{{ old('address') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">O'quvchi Rasmi</label>
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photoInput" accept="image/*">
                            <label class="custom-file-label" for="photoInput">Rasm tanlang...</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ota-ona ma'lumotlari -->
        <div class="col-lg-4 mb-3">
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-users mr-2 text-info"></i> Ota-ona Ma'lumotlari</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Otasining F.I.Sh</label>
                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="Masalan: Azizov Botir">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Otasining Telefoni</label>
                        <input type="text" name="father_phone" class="form-control" value="{{ old('father_phone') }}" placeholder="+998 90 123 45 67">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Onasining F.I.Sh</label>
                        <input type="text" name="mother_name" class="form-control" value="{{ old('mother_name') }}" placeholder="Masalan: Azizova Nigora">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Onasining Telefoni</label>
                        <input type="text" name="mother_phone" class="form-control" value="{{ old('mother_phone') }}" placeholder="+998 90 987 65 43">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Oila Manzili</label>
                        <textarea name="parent_address" class="form-control" rows="2">{{ old('parent_address') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg shadow font-weight-bold mb-2">
                <i class="fas fa-save mr-1"></i> O'quvchini Saqlash
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
        $(this).next('.custom-file-label').html(fileName || 'Rasm tanlang...');
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
        } else {
            sectionSelect.html('<option value="">Avval sinfni tanlang</option>');
        }
    });
</script>
@endpush
