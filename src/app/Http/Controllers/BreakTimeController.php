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

            $break = new \App\Models\BreakTime(); // 名前空間を指定
            $break->user_id = auth()->user()->id;
            $break->date = now()->toDateString(); // 今日の日付を取得
            $break->start_time = now(); // Carbonクラスを使用
            $break->save();

            $break_time = 0; // 初期値は 0
            session(['break_time' => $break_time]);

            return redirect()->back()->with('success', '休憩開始しました');
        }

    public function endBreak(Request $request)
        {
            $user = Auth::user();
            $break = BreakTime::where('user_id', $user->id)
                ->whereNull('end_time')
                ->first();

            if (!$break) {
                return redirect()->back()->with('error', '休憩情報が見つかりません。');
            }

            // 休憩終了時間を現在の時刻で設定
            $break->end_time = now();
            $break->save();

            // 休憩時間の計算
            $today = now()->toDateString();
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('start_time', $today)
                ->first();

            if (!$attendance) {
                return redirect()->back()->with('error', '勤務情報が見つかりません。');
            }

            if ($attendance->end_time) {
                return redirect()->back()->with('error', '既に勤務を終了しています。');
            }


            // 休憩時間の計算
            $breaks = BreakTime::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->whereNotNull('end_time')
                ->get();

            $start_time = Carbon::parse($break->start_time);
            $end_time = Carbon::parse($break->end_time);

            $break_time = session('break_time', 0); // セッションから休憩時間を取得（初期値は 0）

            $break_time += $start_time->diffInMinutes($end_time);
            session(['break_time' => $break_time]); // 更新後の値をセッションに保存

            foreach ($breaks as $break) {
                $start_time = Carbon::parse($break->start_time);
                $end_time = Carbon::parse($break->end_time);
                $break_time += $start_time->diffInMinutes($end_time);
            }

            session(['break_time' => $break_time]); // 更新後の値をセッションに保存

            return redirect()->back()->with('success', '勤務を終了しました。');
        }
    }
