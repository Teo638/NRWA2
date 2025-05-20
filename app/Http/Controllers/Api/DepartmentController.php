<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
 
    public function index()
    {
        
        return DepartmentResource::collection(Department::with('hod')->paginate(10));
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30|unique:departments,name', 
            'hod_id' => 'nullable|integer|exists:faculty,id', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $department = Department::create($validator->validated());
        return new DepartmentResource($department->load('hod'));
    }

    
    public function show(Department $department)
    {
       
        return new DepartmentResource($department->load(['hod', 'students', 'facultyMembers']));
    }

    
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            
            'name' => 'sometimes|required|string|max:30|unique:departments,name,' . $department->id,
            'hod_id' => 'sometimes|nullable|integer|exists:faculty,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $department->update($validator->validated());
        return new DepartmentResource($department->load('hod'));
    }

   
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return response()->json(null, 204); 
        } catch (\Illuminate\Database\QueryException $e) {
            
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => 'Cannot delete department. It is referenced by other records (e.g., faculty, students, or subjects).',
                    'error_details' => $e->getMessage() 
                ], 409); 
            }
            
            return response()->json([
                'message' => 'Error deleting department.',
                'error_details' => $e->getMessage() 
            ], 500); 
        }
    }
}