@extends('layouts.app')
@section('title', 'Yangi o\'quv yili')
@section('content')
<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Yangi o\'quv yili</h3></div>
    <form action="{{ route('academic-years.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Nomi</label>
                <input type="text" name="name" class="form-control" required placeholder="2023-2024">
            </div>
            <div class="form-group">
                <label>Boshlanish sanasi</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tugash sanasi</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_current" value="1" class="form-check-input" id="is_current">
                <label class="form-check-label" for="is_current">Joriy qilib belgilash</label>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </div>
    </form>
</div>
@endsection
