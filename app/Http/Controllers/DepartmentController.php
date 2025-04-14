<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hod_id' => 'required|integer',
        ]);

        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Odjel uspješno dodan.');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hod_id' => 'required|integer',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Odjel uspješno ažuriran.');
    }

    public function destroy($id)
    {
        Department::destroy($id);
        return redirect()->route('departments.index')->with('success', 'Odjel uspješno obrisan.');
    }
}

