@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Popis studenata</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @auth
        @can('create students')
            <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Dodaj novog studenta</a>
        @endcan
    @endauth

    @if($students->isEmpty())
        <p>Trenutno nema studenata u bazi.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Roll broj</th>
                    <th>Ime</th>
                    <th>Prezime</th>
                    <th>Telefon</th>
                    <th>Datum upisa</th>
                    <th>CET bodovi</th>
                    @auth
                        @if(Auth::user()->can('edit students') || Auth::user()->can('delete students'))
                            <th>Akcije</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->roll_num }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>{{ $student->admission_date }}</td>
                        <td>{{ $student->cet_marks }}</td>
                        @auth
                            @if(Auth::user()->can('edit students') || Auth::user()->can('delete students'))
                                <td>
                                    @can('edit students')
                                        <a href="{{ route('students.edit', $student->roll_num) }}" class="btn btn-sm btn-warning">Uredi</a>
                                    @endcan
                                    @can('delete students')
                                        <form action="{{ route('students.destroy', $student->roll_num) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Jesi li siguran da želiš obrisati ovog studenta?')">Obriši</button>
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