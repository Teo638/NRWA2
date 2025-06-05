<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty; 
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        
        $departments = Department::with('hod')->orderBy('name')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('last_name')->orderBy('first_name')->get(); 
        return view('departments.create', compact('faculties')); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name', 
            'hod_id' => 'required|integer|exists:faculty,id',
        ]);

        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Odjel uspješno dodan.');
    }

    public function show(Department $department) 
    {
        $department->load('hod', 'students', 'facultyMembers'); 
        return view('departments.show', compact('department')); 
    }

    public function edit(Department $department) 
    {
        $faculties = Faculty::orderBy('last_name')->orderBy('first_name')->get();
        
        return view('departments.edit', compact('department', 'faculties')); 
    }

    public function update(Request $request, Department $department) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id, 
            'hod_id' => 'required|integer|exists:faculty,id',
        ]);

        
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Odjel uspješno ažuriran.');
    }

    public function destroy(Department $department) 
    {
        
        try {
            $department->delete();
            return redirect()->route('departments.index')->with('success', 'Odjel uspješno obrisan.');
        } catch (\Illuminate\Database\QueryException $e) {
            
             if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 return redirect()->route('departments.index')->with('error', 'Ne možete obrisati odjel jer je povezan s drugim zapisima (npr. profesori ili studenti).');
             }
            return redirect()->route('departments.index')->with('error', 'Greška prilikom brisanja odjela.');
        }
    }
}