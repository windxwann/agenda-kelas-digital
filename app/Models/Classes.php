<?php
// app/Models/Classes.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    
    protected $table = 'classes';
    
    protected $fillable = [
        'name', 'grade_level', 'academic_year',
        'homeroom_teacher_id', 'capacity', 'description', 'is_active'
    ];
    
    public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }
    
    public function students()
    {
        return $this->hasMany(User::class, 'class_id')->orderByRaw('LOWER(name) ASC');
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }
}