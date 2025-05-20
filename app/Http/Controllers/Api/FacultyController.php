<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Resources\FacultyResource;
use Illuminate\Support\Facades\Validator;

class FacultyController extends Controller
{
   
    public function index()
    {
       
        return FacultyResource::collection(Faculty::with(['department', 'departmentHeaded'])->paginate(15));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'department_id' => 'required|integer|exists:departments,id', 
            'phone' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $faculty = Faculty::create($validator->validated());
        return new FacultyResource($faculty->load(['department', 'departmentHeaded']));
    }

  
    public function show(Faculty $faculty)
    {
        return new FacultyResource($faculty->load(['department', 'departmentHeaded', 'subjects']));
    }

  
    public function update(Request $request, Faculty $faculty)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:25',
            'last_name' => 'sometimes|required|string|max:25',
            'department_id' => 'sometimes|required|integer|exists:departments,id',
            'phone' => 'sometimes|nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $faculty->update($validator->validated());
        return new FacultyResource($faculty->load(['department', 'departmentHeaded']));
    }

   
    public function destroy(Faculty $faculty)
    {
        try {
           
            if ($faculty->departmentHeaded()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete faculty. This faculty member is HOD of a department. Please assign a new HOD or set HOD to null for that department first.',
                ], 409); 
            }

          

            $faculty->delete();
            return response()->json(null, 204); 
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => 'Cannot delete faculty. It might be associated with subjects or other records.',
                    'error_details' => $e->getMessage()
                ], 409); 
            }
            return response()->json([
                'message' => 'Error deleting faculty.',
                'error_details' => $e->getMessage()
            ], 500); 
        }
    }
}