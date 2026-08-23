@extends('layouts.adminlte_app')
@section('title', 'Maoshni tahrirlash')
@section('content')
<div class="card card-warning">
    <form action="{{ route('salaries.update', $salary) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <p><strong>Xodim:</strong> {{ $salary->user->name }}</p>
            <div class="row">
                <div class="col-md-4"><div class="form-group"><label>Bonus</label><input type="number" name="bonus" class="form-control" value="{{ $salary->bonus }}"></div></div>
                <div class="col-md-4"><div class="form-group"><label>Ushlanma</label><input type="number" name="deduction" class="form-control" value="{{ $salary->deduction }}"></div></div>
            </div>
            <div class="form-group"><label>Izoh</label><textarea name="note" class="form-control">{{ $salary->note }}</textarea></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-warning">Yangilash</button></div>
    </form>
</div>
@endsection
