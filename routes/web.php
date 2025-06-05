<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/students/create', [StudentController::class, 'create'])->name('students.create')
    ->middleware(['auth', 'permission:create students']);
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create')
    ->middleware(['auth', 'permission:create departments']);
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');


Route::middleware(['auth'])->group(function () {

    Route::post('/students', [StudentController::class, 'store'])
        ->name('students.store')->middleware('permission:create students');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
        ->name('students.edit')->middleware('permission:edit students');
    Route::put('/students/{student}', [StudentController::class, 'update'])
        ->name('students.update')->middleware('permission:edit students');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])
        ->name('students.destroy')->middleware('permission:delete students');


    Route::post('/departments', [DepartmentController::class, 'store'])
        ->name('departments.store')->middleware('permission:create departments');
    Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])
        ->name('departments.edit')->middleware('permission:edit departments');
    Route::put('/departments/{department}', [DepartmentController::class, 'update'])
        ->name('departments.update')->middleware('permission:edit departments');
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])
        ->name('departments.destroy')->middleware('permission:delete departments');

   
    Route::get('/faculty', [FacultyController::class, 'index'])
        ->name('faculty.index')
        ->middleware('permission:view faculty');

    
    Route::get('/faculty/create', [FacultyController::class, 'create'])
        ->name('faculty.create')->middleware('permission:create faculty');
    Route::post('/faculty', [FacultyController::class, 'store'])
        ->name('faculty.store')->middleware('permission:create faculty');
    Route::get('/faculty/{faculty}', [FacultyController::class, 'show'])
        ->name('faculty.show')->middleware('permission:view faculty');
    Route::get('/faculty/{faculty}/edit', [FacultyController::class, 'edit'])
        ->name('faculty.edit')->middleware('permission:edit faculty');
    Route::put('/faculty/{faculty}', [FacultyController::class, 'update'])
        ->name('faculty.update')->middleware('permission:edit faculty');
    Route::delete('/faculty/{faculty}', [FacultyController::class, 'destroy'])
        ->name('faculty.destroy')->middleware('permission:delete faculty');

    
});