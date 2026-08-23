@extends('layouts.app')
@section('title', 'Xarajatni tahrirlash')
@section('content')
<div class="card card-warning">
    <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Kategoriya</label>
                <select name="expense_category_id" class="form-control" required>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $expense->expense_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label>Sarlavha</label><input type="text" name="title" class="form-control" value="{{ $expense->title }}" required></div>
            <div class="form-group"><label>Summa</label><input type="number" name="amount" class="form-control" value="{{ $expense->amount }}" required></div>
            <div class="form-group"><label>Sana</label><input type="date" name="expense_date" class="form-control" value="{{ $expense->expense_date->format('Y-m-d') }}" required></div>
            <div class="form-group"><label>Tavsif</label><textarea name="description" class="form-control">{{ $expense->description }}</textarea></div>
            <div class="form-group"><label>Chek (ixtiyoriy)</label><input type="file" name="receipt_file" class="form-control-file"></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-warning">Yangilash</button></div>
    </form>
</div>
@endsection
