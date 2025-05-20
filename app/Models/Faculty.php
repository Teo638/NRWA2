<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

   
    protected $table = 'faculty';

    
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'department_id',
        'phone',
    ];

   
    protected $casts = [
        'department_id' => 'integer',
    ];

    
    public function department()
    {
       
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    
    public function departmentHeaded()
    {
       
        return $this->hasOne(Department::class, 'hod_id', 'id');
    }

   
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'faculty_id', 'id');
    }
}