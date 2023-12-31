<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $table = 'breaks'; // テーブル名を指定

    protected $fillable = ['attendance_id', 'date', 'start_time', 'end_time']; // ホワイトリストで許可するカラムを指定

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo('App\Models\Attendance');
    }
}
