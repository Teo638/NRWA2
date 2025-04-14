<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DepartmentController;


Route::get('/', function () {
    return view('welcome');
});


Route::resource('students', StudentController::class);
Route::resource('departments', DepartmentController::class);


