<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAcademicYear;

class Attendance extends Model
{
    use HasFactory, HasAcademicYear;

    protected $fillable = [
        'student_id',
        'class_id',
        'date',
        'status',
        'note',
        'check_in_time',
        'academic_year_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
