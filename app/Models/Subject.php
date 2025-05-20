<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';
    public $timestamps = false;
    protected $fillable = ['department_id', 'start_date', 'end_date', 'name', 'faculty_id'];

    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function faculty() {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    public function marks() {
        return $this->hasMany(Mark::class, 'subject_id', 'id');
    }
}