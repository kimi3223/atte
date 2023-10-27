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

        $breakStarted = false;
        $breakEnded = false;

        if ($workStarted) {
            $workEnded = $existingAttendance && $existingAttendance->end_time; // 1日に一度しか押せないようにする

            if ($workEnded) {
                $workEnded = false; // すでに勤務終了している場合
            } else {
                $workEnded = true; // 勤務終了していない場合
            }
        } else {
            $workEnded = false; // 勤務が開始されていない場合は常に非活性
        }

        return view('index', [
            'welcomeMessage' => $welcomeMessage,
            'user' => $user,
            'selectedDate' => $today,
            'workStarted' => $workStarted,
            'workEnded' => $workEnded,
            'breakStarted' => $breakStarted,
            'breakEnded' => $breakEnded,
        ]);
    }
}
