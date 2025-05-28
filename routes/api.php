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
Route::get('students/{roll_num}', [StudentController::class, 'show'])->where('roll_num', '[0-9]+');

Route::get('departments', [DepartmentController::class, 'index']);
Route::get('departments/{department}', [DepartmentController::class, 'show']);

Route::middleware('auth.basic')->group(function () {
    Route::post('students', [StudentController::class, 'store'])
        ->middleware('permission:create students');
    Route::put('students/{roll_num}', [StudentController::class, 'update'])
        ->middleware('permission:edit students')->where('roll_num', '[0-9]+');
    Route::patch('students/{roll_num}', [StudentController::class, 'update'])
        ->middleware('permission:edit students')->where('roll_num', '[0-9]+');
    Route::delete('students/{roll_num}', [StudentController::class, 'destroy'])
        ->middleware('permission:delete students')->where('roll_num', '[0-9]+');

    Route::post('departments', [DepartmentController::class, 'store'])
        ->middleware('permission:create departments');
    Route::put('departments/{department}', [DepartmentController::class, 'update'])
        ->middleware('permission:edit departments');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])
        ->middleware('permission:delete departments');

    Route::get('faculty', [FacultyController::class, 'index'])
        ->middleware('permission:view faculty');
    Route::post('faculty', [FacultyController::class, 'store'])
        ->middleware('permission:create faculty');
    Route::get('faculty/{faculty}', [FacultyController::class, 'show'])
        ->middleware('permission:view faculty');
    Route::put('faculty/{faculty}', [FacultyController::class, 'update'])
        ->middleware('permission:edit faculty');
    Route::delete('faculty/{faculty}', [FacultyController::class, 'destroy'])
        ->middleware('permission:delete faculty');

    Route::get('ping', [PingController::class, 'pong']);
});