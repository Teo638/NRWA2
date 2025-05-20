<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Validator; 

class StudentController extends Controller
{
    
    public function index()
    {
        
        return StudentResource::collection(Student::with('department')->paginate(15));
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'department_id' => 'nullable|integer|exists:departments,id', 
            'phone' => 'nullable|string|max:10',
            'admission_date' => 'required|date_format:Y-m-d', 
            'cet_marks' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); 
        }

        $student = Student::create($validator->validated());
        
        return new StudentResource($student->load('department'));
    }

   
    public function show($roll_num)
    {
      
        $student = Student::with('department')->findOrFail($roll_num);
        return new StudentResource($student);
    }

   
    public function update(Request $request, $roll_num)
    {
        $student = Student::findOrFail($roll_num);

        $validator = Validator::make($request->all(), [
           
            'first_name' => 'sometimes|required|string|max:25',
            'last_name' => 'sometimes|required|string|max:25',
            'department_id' => 'sometimes|nullable|integer|exists:departments,id',
            'phone' => 'sometimes|nullable|string|max:10',
            'admission_date' => 'sometimes|required|date_format:Y-m-d',
            'cet_marks' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $student->update($validator->validated());
        return new StudentResource($student->load('department'));
    }

    
    public function destroy($roll_num)
    {
        $student = Student::findOrFail($roll_num);
        
        $student->delete();
        return response()->json(null, 204); 
    }
}