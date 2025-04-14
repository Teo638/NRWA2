@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Dodaj studenta</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('students') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Broj indeksa (roll_num)</label>
            <input type="number" name="roll_num" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ime</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Prezime</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Odjel (department_id)</label>
            <input type="number" name="department_id" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefon</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Datum upisa</label>
            <input type="date" name="admission_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">CET bodovi</label>
            <input type="number" step="0.01" name="cet_marks" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Spremi</button>
        <a href="{{ url('students') }}" class="btn btn-secondary">Natrag</a>
    </form>
</div>
@endsection
