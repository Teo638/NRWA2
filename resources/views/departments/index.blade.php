@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Popis odjela</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Dodaj novi odjel</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naziv</th>
                <th>HOD ID</th>
                <th>Akcije</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->hod_id }}</td>
                    <td>
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">Uredi</a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Jesi li siguran?')" class="btn btn-sm btn-danger">Obriši</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
