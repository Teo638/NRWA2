<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;
    protected $table = 'marks';
    public $timestamps = false;
    protected $fillable = ['student_roll_num', 'subject_id', 'marks'];


    public function student() {
        return $this->belongsTo(Student::class, 'student_roll_num', 'roll_num');
    }
    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
