@extends('layouts.app')
@section('title', 'Oylik to\'lash')
@section('content')
<div class="card card-primary">
    <form action="{{ route('salaries.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Xodim</label>
                <select name="user_id" class="form-control select2" required>
                    @foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }} (Asosiy: {{ number_format($user->base_salary) }})</option>@endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Oy (1-12)</label><input type="number" name="month" class="form-control" value="{{ date('n') }}" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Yil</label><input type="number" name="year" class="form-control" value="{{ date('Y') }}" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Bonus</label><input type="number" name="bonus" class="form-control" value="0"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Ushlanma (Jarima)</label><input type="number" name="deduction" class="form-control" value="0"></div></div>
                <div class="col-md-4"><div class="form-group"><label>To\'lov sanasi</label><input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required></div></div>
            </div>
            <div class="form-group"><label>Izoh</label><textarea name="note" class="form-control"></textarea></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary">Saqlash</button></div>
    </form>
</div>
@endsection
