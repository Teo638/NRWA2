<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department; 

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('department')->orderBy('last_name')->orderBy('first_name')->get(); // Dodao with i orderBy
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get(); 
        return view('students.create', compact('departments')); 
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department_id' => 'required|integer|exists:departments,id', 
            'phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'cet_marks' => 'required|numeric',
        ]);

        
        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student je uspješno dodan.');
    }

    
    public function show(Student $student) 
    {
        $student->load('department'); 
        return view('students.show', compact('student')); 
    }

    public function edit(Student $student) 
    {
        $departments = Department::orderBy('name')->get(); 
        return view('students.edit', compact('student', 'departments')); 
    }

    public function update(Request $request, Student $student) 
    {
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'department_id' => 'required|integer|exists:departments,id', 
            'phone' => 'required|string|max:20',
            'admission_date' => 'required|date',
            'cet_marks' => 'required|numeric',
        ]);

        
        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Podaci su ažurirani.');
    }

    public function destroy(Student $student) 
    {
       
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student je obrisan.');
    }
}