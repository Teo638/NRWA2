@extends('layouts.app') {{-- Pretpostavljam da imaš layouts.app --}}

@section('content')
<div class="container mt-4">
    <h2>Popis profesora</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ route('faculty.create') }}" class="btn btn-primary mb-3">Dodaj novog profesora</a>

    @if($faculties->isEmpty())
        <p>Trenutno nema profesora u bazi.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Odjel</th> {{-- Dodajemo prikaz odjela --}}
                    <th>Telefon</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faculties as $professor) {{-- Promijenio varijablu u $professor radi jasnoće --}}
                    <tr>
                        <td>{{ $professor->id }}</td>
                        <td>{{ $professor->first_name }}</td>
                        <td>{{ $professor->last_name }}</td>
                        <td>{{ $professor->department->name ?? 'N/A' }}</td> {{-- Prikaz imena odjela, koristi relaciju --}}
                        <td>{{ $professor->phone }}</td>
                        <td>
                            <a href="{{ route('faculty.edit', $professor->id) }}" class="btn btn-sm btn-warning">Uredi</a>
                            <form action="{{ route('faculty.destroy', $professor->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Jesi li siguran da želiš obrisati ovog profesora?')">Obriši</button>
                            </form>
                            {{-- Ako želiš link za prikaz detalja (faculty.show ruta) --}}
                            {{-- <a href="{{ route('faculty.show', $professor->id) }}" class="btn btn-sm btn-info">Detalji</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection