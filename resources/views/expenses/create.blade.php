@extends('layouts.adminlte_app')
@section('title', 'Yangi xarajat')
@section('content')
<div class="card card-primary">
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Kategoriya</label>
                <select name="expense_category_id" class="form-control" required>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Sarlavha</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Summa</label><input type="number" name="amount" class="form-control" required></div>
            <div class="form-group"><label>Sana</label><input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
            <div class="form-group"><label>Tavsif</label><textarea name="description" class="form-control"></textarea></div>
            <div class="form-group"><label>Chek / Hujjat (ixtiyoriy)</label><input type="file" name="receipt_file" class="form-control-file"></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Saqlash</button></div>
    </form>
</div>
@endsection
