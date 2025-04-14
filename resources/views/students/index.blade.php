@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Popis studenata</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Dodaj novog studenta</a>

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
                    <th>Akcije</th>
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
                        <td>
                        <a href="{{ route('students.edit', ['student' => $student->roll_num]) }}" class="btn btn-sm btn-warning">Uredi</a>

<form action="{{ route('students.destroy', ['student' => $student->roll_num]) }}" method="POST" style="display:inline;">

                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Jesi li siguran da želiš obrisati ovog studenta?')">Obriši</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
