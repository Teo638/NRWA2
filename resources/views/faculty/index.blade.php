@extends('layouts.app')

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

    @auth 
        @can('create faculty')
            <a href="{{ route('faculty.create') }}" class="btn btn-primary mb-3">Dodaj novog profesora</a>
        @endcan
    @endauth

    @if($faculties->isEmpty())
        <p>Trenutno nema profesora u bazi.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Odjel</th>
                    <th>Telefon</th>
                    @auth 
                        @if(Auth::user()->can('edit faculty') || Auth::user()->can('delete faculty'))
                            <th>Akcije</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($faculties as $professor)
                    <tr>
                        <td>{{ $professor->id }}</td>
                        <td>{{ $professor->first_name }}</td>
                        <td>{{ $professor->last_name }}</td>
                        <td>{{ $professor->department->name ?? 'N/A' }}</td>
                        <td>{{ $professor->phone }}</td>
                        @auth
                            @if(Auth::user()->can('edit faculty') || Auth::user()->can('delete faculty'))
                                <td>
                                    @can('edit faculty')
                                        <a href="{{ route('faculty.edit', $professor->id) }}" class="btn btn-sm btn-warning">Uredi</a>
                                    @endcan
                                    @can('delete faculty')
                                        <form action="{{ route('faculty.destroy', $professor->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Jesi li siguran da želiš obrisati ovog profesora?')">Obriši</button>
                                        </form>
                                    @endcan
                                </td>
                            @else
                                <td></td> 
                            @endif
                        @endauth
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection