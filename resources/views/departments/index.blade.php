@extends('layouts.app')

@section('content')




<div class="container mt-4">
    <h2>Popis odjela</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @auth 
        @can('create departments') 
            <a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Dodaj novi odjel</a>
        @endcan
    @endauth

    @if($departments->isEmpty()) 
        <p>Trenutno nema odjela u bazi.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naziv</th>
                    <th>HOD ID</th> 
                    @auth 
                        @if(Auth::user()->can('edit departments') || Auth::user()->can('delete departments'))
                            <th>Akcije</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->hod_id }}</td>
                        @auth
                            @if(Auth::user()->can('edit departments') || Auth::user()->can('delete departments'))
                                <td>
                                    @can('edit departments')
                                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">Uredi</a>
                                    @endcan
                                    @can('delete departments')
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Jesi li siguran?')" class="btn btn-sm btn-danger">Obriši</button>
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