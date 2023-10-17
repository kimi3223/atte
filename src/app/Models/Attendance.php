<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'start_time', 'end_time', 'break_time', 'work_time',
    ];
    protected $table = 'attendances';
    protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;

    public function breaks()
    {
        return $this->hasMany(BreakTime::class, 'attendance_id', 'attendance_id');
    }

    public function getWorkTimeFormattedAttribute()
    {
    return str_pad(intval($this->work_time / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($this->work_time % 60, 2, '0', STR_PAD_LEFT) . ':00';
    }
}
