<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAcademicYear;

class Agenda extends Model
{
    use HasFactory, HasAcademicYear;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'subject_id',
        'room',
        'date',
        'title',
        'description',
        'attachments',
        'status',
        'academic_year_id',
    ];

    protected $casts = [
        'date' => 'date',
        'attachments' => 'json',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
