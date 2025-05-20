<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\PingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('students', [StudentController::class, 'index']);
Route::post('students', [StudentController::class, 'store']);
Route::get('students/{roll_num}', [StudentController::class, 'show'])->where('roll_num', '[0-9]+');
Route::put('students/{roll_num}', [StudentController::class, 'update'])->where('roll_num', '[0-9]+');
Route::patch('students/{roll_num}', [StudentController::class, 'update'])->where('roll_num', '[0-9]+');
Route::delete('students/{roll_num}', [StudentController::class, 'destroy'])->where('roll_num', '[0-9]+');

Route::apiResource('departments', DepartmentController::class);
Route::apiResource('faculty', FacultyController::class);

