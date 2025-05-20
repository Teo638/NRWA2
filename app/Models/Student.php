<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory; 

    protected $table = 'students';
    protected $primaryKey = 'roll_num';

    
    public $incrementing = true;

    public $timestamps = false; 

    protected $fillable = [
        
        'first_name',
        'last_name',
        'department_id',
        'phone',
        'admission_date',
        'cet_marks',
    ];

    
    protected $casts = [
        'admission_date' => 'date', 
        'cet_marks' => 'integer',
        'department_id' => 'integer',
    ];

   
    public function department()
    {
        
        return $this->belongsTo(Department::class, 'department_id');
    }

    
    public function marks()
    {
       
        return $this->hasMany(Mark::class, 'student_roll_num', 'roll_num');
    }
}