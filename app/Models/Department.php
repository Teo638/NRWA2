<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public $timestamps = false; 

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'hod_id'
    ];

    protected $casts = [
        'hod_id' => 'integer',
    ];

  
    public function hod()
    {
        
        return $this->belongsTo(Faculty::class, 'hod_id', 'id');
    }

   
    public function facultyMembers()
    {
        
        return $this->hasMany(Faculty::class, 'department_id', 'id');
    }

    public function students()
    {
        
        return $this->hasMany(Student::class, 'department_id', 'id');
    }

   
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'department_id', 'id');
    }
}