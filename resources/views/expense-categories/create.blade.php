@extends('layouts.app')
@section('title', 'Yangi kategoriya')
@section('content')
<div class="card card-primary">
    <form action="{{ route('expense-categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group"><label>Nomi</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label>Tavsif</label><textarea name="description" class="form-control"></textarea></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Saqlash</button></div>
    </form>
</div>
@endsection
