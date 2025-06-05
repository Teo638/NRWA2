@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Dodaj novi odjel</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('departments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Naziv Odjela</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="hod_id" class="form-label">Predstojnik Odjela (HOD):</label>
            <select name="hod_id" id="hod_id" class="form-control" required>
                <option value="">Odaberite predstojnika</option>
                @if(isset($faculties))
                    @foreach($faculties as $facultyMember)
                        <option value="{{ $facultyMember->id }}" {{ old('hod_id') == $facultyMember->id ? 'selected' : '' }}>
                            {{ $facultyMember->first_name }} {{ $facultyMember->last_name }} (ID: {{ $facultyMember->id }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <button type="submit" class="btn btn-success">Spremi Odjel</button>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Natrag</a>
    </form>
</div>
@endsection