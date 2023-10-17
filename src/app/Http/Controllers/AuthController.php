<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //public function index()
    //{
    //    return view('index');
    //}
    //
    public function index()
    {
        $user = Auth::user();
        $welcomeMessage = $user->name . 'さんお疲れ様です！';

        // ログインユーザーのIDを取得
        $userId = auth()->user()->id;
        // 今日の日付を取得
        $today = now()->toDateString();

        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('start_time', $today)
            ->first();
        // 勤務開始ボタンの状態
        $workStarted = !$existingAttendance;
        // 勤務終了ボタンの状態
        $workEnded = $existingAttendance && $existingAttendance->end_time;
        // 1日に一度しか押せないようにする
        if (!$workStarted) {
            $workEnded = false;
        }
        // 休憩ボタンの活性化条件（例：勤務開始されていれば休憩開始ボタンを活性化）
        $breakStarted = $workStarted && !$workEnded;
        // 勤務が開始されていて、終了しておらず、休憩が既に始まっている場合に活性化
        $breakEnded = $breakStarted && $existingAttendance && $existingAttendance->break_start_time && !$existingAttendance->break_end_time;

        return view('index', [
            'welcomeMessage' => $welcomeMessage,
            'user' => $user,
            'selectedDate' => $today,
            'workStarted' => $workStarted,
            'workEnded' => $workEnded,
            'breakStarted' => $breakEnded,
            'breakEnded' => $breakEnded,
        ]);
    }
}
