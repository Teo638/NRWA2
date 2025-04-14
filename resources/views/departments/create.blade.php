@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Dodaj novi odjel</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('departments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Naziv</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="hod_id" class="form-label">HOD ID</label>
            <input type="number" class="form-control" id="hod_id" name="hod_id" required>
        </div>

        <button type="submit" class="btn btn-success">Spremi</button>
        <a href="{{ route('departments.index') }}" class="btn btn-secondary">Natrag</a>
    </form>
</div>
@endsection
