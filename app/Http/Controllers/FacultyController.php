<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Department;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::with('department')->orderBy('last_name')->orderBy('first_name')->get();
        return view('faculty.index', compact('faculties'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('faculty.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'department_id' => 'required|integer|exists:departments,id',
            'phone' => 'nullable|string|max:10',
        ]);

        Faculty::create($validated);

        return redirect()->route('faculty.index')->with('success', 'Profesor je uspješno dodan.');
    }

    public function show(Faculty $faculty)
    {
        $faculty->load('department'); 
        return view('faculty.show', compact('faculty')); 
       
    }

    public function edit(Faculty $faculty)
    {
        $departments = Department::orderBy('name')->get();
        return view('faculty.edit', compact('faculty', 'departments'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'department_id' => 'required|integer|exists:departments,id',
            'phone' => 'nullable|string|max:10',
        ]);

        $faculty->update($validated);

        return redirect()->route('faculty.index')->with('success', 'Podaci o profesoru su ažurirani.');
    }

    public function destroy(Faculty $faculty)
    {
        try {
            if ($faculty->departmentHeaded()->exists()) {
                return redirect()->route('faculty.index')->with('error', 'Ne možete obrisati profesora koji je HOD. Prvo postavite drugog HOD-a.');
            }
            $faculty->delete();
            return redirect()->route('faculty.index')->with('success', 'Profesor je obrisan.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return redirect()->route('faculty.index')->with('error', 'Ne možete obrisati profesora. Povezan je s drugim zapisima (npr. predmeti).');
            }
            return redirect()->route('faculty.index')->with('error', 'Greška prilikom brisanja profesora.');
        }
    }
}