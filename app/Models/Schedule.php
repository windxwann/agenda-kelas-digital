<?php
// app/Models/Schedule.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'class_id', 'subject_id', 'teacher_id', 'day', 'start_time', 'end_time', 'room'
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function scopeCurrentlyOccupied($query, $now = null)
    {
        $now = $now ?? now();
        return $query->where('day', $now->format('l'))
                     ->where('start_time', '<=', $now->format('H:i:s'))
                     ->where('end_time', '>=', $now->format('H:i:s'));
    }

    // Helper untuk mengecek ketersediaan ruangan (hybrid check)
    public static function isRoomOccupied($roomName, $roomId = null)
    {
        $now = now();
        $query = self::currentlyOccupied($now);

        if ($roomId) {
            $query->where(function($q) use ($roomId, $roomName) {
                $q->where('room_id', $roomId)
                  ->orWhere('room', $roomName);
            });
        } else {
            $query->where('room', $roomName);
        }

        return $query->exists();
    }
}