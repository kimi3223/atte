<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BreakTime;
use App\Models\Attendance;
use Carbon\Carbon;
use Auth;

class BreakTimeController extends Controller
{
    public function startBreak(Request $request)
        {
            // ログインユーザーのIDを取得
            $userId = auth()->user()->id;
            $user = auth()->user();
            $today = now()->toDateString();
           // 前回の休憩が終了しているか確認
            $lastBreak = BreakTime::where('user_id', $user->id)
                ->whereNotNull('end_time')
                ->latest()
                ->first();

            if (!$lastBreak) {
                // 休憩が終了していない場合はエラーメッセージを返す            }

                $break = new \App\Models\BreakTime(); // 名前空間を指定
                $break->user_id = $user->id;
                $break->date = now()->toDateString(); // 今日の日付を取得
                $break->start_time = now(); // Carbonクラスを使用
                $break->save();
                $break_time = 0; // 初期値は 0
                session(['break_time' => $break_time]);

                $attendance = Attendance::where('user_id', $user->id)
                    ->whereDate('start_time', $today)
                    ->first();
                $workEnded = $attendance && !is_null($attendance->start_time) && !is_null($attendance->end_time);
                // 勤務開始ボタンの状態
                $workStarted = false;
                $workEnded = false;
                $breakStarted = true;
                $breakEnded = $breakStarted ? true : false;

                return view('index', [
                        'workStarted' => $workStarted,
                        'workEnded' => $workEnded,
                        'breakStarted' => $breakStarted,
                        'breakEnded' => $breakEnded,
                    ])->with('success', '休憩を開始しました。');
            }
                return response()->json(['message' => '休憩を開始しました']);
            }

    public function endBreak(Request $request)
        {
            $userId = auth()->user()->id;
            $user = auth()->user();
            $today = now()->toDateString();
            // 前回の休憩が終了しているか確認
            $lastBreak = BreakTime::where('user_id', $userId)
                ->whereNull('end_time')
                ->latest()
                ->first();

            if ($lastBreak && is_null($lastBreak->end_time)) {
                // 休憩がまだ終了していない場合、終了処理を行う
                $lastBreak->end_time = now();
                $lastBreak->save();

                // 休憩時間の計算
                $attendance = Attendance::where('user_id', $userId)
                    ->whereDate('start_time', $today)
                    ->first();

                if (!$attendance) {
                    return redirect()->back()->with('success', '休憩を終了しました。');
                }

                if ($attendance->end_time) {
                    return redirect()->back()->with('error', '既に勤務を終了しています。');
                }

                // 休憩時間の計算
                $breaks = BreakTime::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->whereNotNull('end_time')
                    ->get();

                $break_time = session('break_time', 0); // セッションから休憩時間を取得（初期値は 0）

                foreach ($breaks as $break) {
                    $start_time = Carbon::parse($break->start_time);
                    $end_time = Carbon::parse($break->end_time);
                    $break_time += $start_time->diffInMinutes($end_time);
                }

                session(['break_time' => $break_time]); // 更新後の値をセッションに保存

                $workStarted = true;
                $workEnded = true;
                $breakStarted = true;
                $breakEnded = $breakStarted ? true : false;

                return redirect('/');

            return response()->json(['success' => '休憩を終了しました。']);
            } else {
            return response()->json(['error', '休憩が開始していません。']);
        }
    }
}