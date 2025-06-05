@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Dodaj Novog Profesora</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Greška!</strong> Molimo ispravite sljedeće:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('faculty.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="first_name" class="form-label">Ime:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Prezime:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Odjel:</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">Odaberite odjel</option>
                @if(isset($departments))
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefon:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
        </div>

        <button type="submit" class="btn btn-primary">Spremi Profesora</button>
        <a href="{{ route('faculty.index') }}" class="btn btn-secondary">Odustani</a>
    </form>
</div>
@endsection