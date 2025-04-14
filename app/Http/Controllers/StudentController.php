<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'roll_num' => 'required|integer|unique:students,roll_num',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department_id' => 'required|integer',
            'phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'cet_marks' => 'required|numeric',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student je uspješno dodan.');
    }

    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department_id' => 'required|integer',
            'phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'cet_marks' => 'required|numeric',
        ]);

        $student = Student::findOrFail($id);
        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Podaci su ažurirani.');
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student je obrisan.');
    }
}
