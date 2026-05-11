<?php
// app/Models/Subject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'name', 'description', 'credit_hours', 'teacher_id'
    ];
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class);
    }
}