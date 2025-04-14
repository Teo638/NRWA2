<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'roll_num';
    public $incrementing = false; // ako roll_num nije AUTO_INCREMENT
    public $timestamps = false;

    protected $fillable = [
        'roll_num',
        'first_name',
        'last_name',
        'department_id',
        'phone',
        'admission_date',
        'cet_marks',
    ];
}



