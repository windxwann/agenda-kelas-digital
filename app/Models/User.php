<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nis', 'nip', 'phone', 'address', 'avatar', 'class_id', 'status', 'gender',
        'nisn', 'tempat_lahir', 'tanggal_lahir', 'rt', 'rw', 'kelurahan', 'kecamatan'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function classes()
    {
        return $this->hasMany(Classes::class, 'homeroom_teacher_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'teacher_id');
    }

    public function classHomeroom()
    {
        return $this->hasMany(Classes::class, 'homeroom_teacher_id');
    }

    public function teachingSchedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    public function classHistories()
    {
        return $this->hasMany(ClassHistory::class);
    }

    public function academicYears()
    {
        return $this->belongsToMany(AcademicYear::class, 'class_histories');
    }

    public function getClassInAcademicYear($academicYearId)
    {
        $history = $this->classHistories()->where('academic_year_id', $academicYearId)->first();
        return $history ? $history->class : null;
    }

    public function scopeInClassAndAcademicYear($query, $classId, $academicYearId)
    {
        return $query->where(function($q) use ($classId, $academicYearId) {
            $q->whereHas('classHistories', function($qh) use ($classId, $academicYearId) {
                $qh->where('class_id', $classId)->where('academic_year_id', $academicYearId);
            })->orWhere(function($qo) use ($classId, $academicYearId) {
                $qo->where('class_id', $classId)
                   ->whereNotExists(function($qe) use ($academicYearId) {
                       $qe->select(\Illuminate\Support\Facades\DB::raw(1))
                          ->from('class_histories')
                          ->whereColumn('class_histories.user_id', 'users.id')
                          ->where('class_histories.academic_year_id', $academicYearId);
                   });
            });
        });
    }
}