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

    <form action="{{ route('students.store') }}" method="POST">
        @csrf

        

        <div class="mb-3">
            <label for="first_name" class="form-label">Ime</label>
            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Prezime</label>
            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Odjel:</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">Odaberite odjel</option>
                @if(isset($departments))
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }} (ID: {{ $department->id }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefon</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label for="admission_date" class="form-label">Datum upisa</label>
            <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ old('admission_date') }}" required>
        </div>

        <div class="mb-3">
            <label for="cet_marks" class="form-label">CET bodovi</label>
            <input type="number" step="1" name="cet_marks" id="cet_marks" class="form-control" value="{{ old('cet_marks') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Spremi</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Natrag</a>
    </form>
</div>
@endsection