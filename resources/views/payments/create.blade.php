@extends('layouts.app')
@section('title', 'Yangi to\'lov')
@section('page-title', 'Yangi to\'lov qabul qilish')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('payments.index') }}">To\'lovlar</a></li>
<li class="breadcrumb-item active">Yangi</li>
@endsection

@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">To\'lov ma\'lumotlari</h3></div>
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sinf <span class="text-danger">*</span></label>
                        <select id="grade_id" class="form-control select2" required>
                            <option value="">Tanlang...</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Bo\'lim <span class="text-danger">*</span></label>
                        <select id="section_id" class="form-control select2" required>
                            <option value="">Avval sinfni tanlang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>O\'quvchi <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-control select2" required>
                            <option value="">Avval bo\'limni tanlang</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>To\'lov turi <span class="text-danger">*</span></label>
                        <select name="payment_type" class="form-control" required>
                            <option value="monthly">Oylik to\'lov</option>
                            <option value="admission">Qabul to\'lovi</option>
                            <option value="other">Boshqa</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Oy</label>
                        <select name="month" class="form-control">
                            @foreach(['Yanvar','Fevral','Mart','Aprel','May','Iyun','Iyul','Avgust','Sentabr','Oktabr','Noyabr','Dekabr'] as $i => $m)
                            <option value="{{ $i+1 }}" {{ date('n') == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Yil</label>
                        <input type="number" name="year" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>To\'lov usuli <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control" required>
                            <option value="cash">Naqd pul</option>
                            <option value="card">Plastik karta</option>
                            <option value="bank">Bank o\'tkazmasi</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jami summa <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Chegirma</label>
                        <input type="number" name="discount_amount" class="form-control" value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>To\'lanayotgan summa <span class="text-danger">*</span></label>
                        <input type="number" name="paid_amount" class="form-control" value="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Izoh</label>
                <textarea name="note" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Saqlash</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Bekor qilish</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$('#grade_id').on('change', function() {
    var gradeId = $(this).val();
    if(gradeId) {
        $.get('/api/sections-by-grade/'+gradeId, function(data) {
            var options = '<option value="">Tanlang...</option>';
            data.forEach(function(s) { options += '<option value="'+s.id+'">'+s.name+'</option>'; });
            $('#section_id').html(options);
        });
        $.get('/api/grade-fee/'+gradeId, function(data) {
            $('#amount').val(data.fee);
        });
    }
});
$('#section_id').on('change', function() {
    var sectionId = $(this).val();
    if(sectionId) {
        $.get('/api/students-by-section/'+sectionId, function(data) {
            var options = '<option value="">Tanlang...</option>';
            data.forEach(function(s) { options += '<option value="'+s.id+'">'+s.full_name+'</option>'; });
            $('#student_id').html(options);
        });
    }
});
</script>
@endpush
