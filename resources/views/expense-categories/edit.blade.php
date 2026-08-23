@extends('layouts.app')
@section('title', 'Tahrirlash')
@section('content')
<div class="card card-warning">
    <form action="{{ route('expense-categories.update', $expenseCategory) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" value="{{ $expenseCategory->name }}" required></div>
            <div class="form-group"><label>Tavsif</label><textarea name="description" class="form-control">{{ $expenseCategory->description }}</textarea></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-warning">Yangilash</button></div>
    </form>
</div>
@endsection
