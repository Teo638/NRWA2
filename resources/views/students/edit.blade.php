@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Uredi studenta</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('students/' . $student->roll_num) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Ime</label>
            <input type="text" name="first_name" class="form-control" value="{{ $student->first_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Prezime</label>
            <input type="text" name="last_name" class="form-control" value="{{ $student->last_name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Odjel (department_id)</label>
            <input type="number" name="department_id" class="form-control" value="{{ $student->department_id }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefon</label>
            <input type="text" name="phone" class="form-control" value="{{ $student->phone }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Datum upisa</label>
            <input type="date" name="admission_date" class="form-control" value="{{ $student->admission_date }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CET bodovi</label>
            <input type="number" step="0.01" name="cet_marks" class="form-control" value="{{ $student->cet_marks }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Spremi</button>
        <a href="{{ url('students') }}" class="btn btn-secondary">Natrag</a>
    </form>
</div>
@endsection
