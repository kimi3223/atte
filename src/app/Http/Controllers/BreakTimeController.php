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
        $user = auth()->user();

           // 前回の休憩が終了しているか確認
        $lastBreak = BreakTime::where('user_id', $user->id)
            ->whereNotNull('end_time')
            ->latest()
            ->first();

        if (!$lastBreak || $lastBreak->end_time) {
            // 休憩が終了している場合、新しい休憩を開始する
            $break = new \App\Models\BreakTime(); // 名前空間を指定
            $break->user_id = $user->id;
            $break->date = now()->toDateString(); // 今日の日付を取得
            $break->start_time = now(); // Carbonクラスを使用

            if (!$lastBreak) {
                // 直前の休憩が存在しない場合は新しい休憩を開始
                $break->save();

                $break_time = 0; // 初期値は 0
                session(['break_time' => $break_time]);
                // 休憩が開始された場合のフラグを設定
                $breakStarted = true;
            } else {
                // 直前の休憩が終了している場合は新しい休憩を開始
                $break->save();

                // ここで何か追加の処理が必要な場合は追加してください

                // 休憩が開始された場合のフラグを設定
                $breakStarted = true;

                return redirect()->back()->with('success', '休憩開始しました');
            }
        }
    }
    public function endBreak(Request $request)
        {
            $userId = auth()->user()->id;
            // 前回の休憩が終了しているか確認
            $lastBreak = BreakTime::where('user_id', $userId)
                ->whereNull('end_time')
                ->latest()
                ->first();

            if ($lastBreak && is_null($lastBreak->end_time)) {
                // 休憩がまだ終了していない場合、終了処理を行う
                $lastBreak->end_time = now();
                $lastBreak->save();

                // 休憩終了が成功した場合に、休憩開始ボタンを活性化するJavaScriptを実行
                echo "<script>enableBreakStartButton();</script>";

                // 休憩が終了した場合のフラグを設定
                $breakEnded = true;
                // 休憩時間の計算
                $today = now()->toDateString();

                $user = auth()->user();
                $attendance = Attendance::where('user_id', $userId)
                    ->whereDate('start_time', $today)
                    ->first();

                } else {
                    // 前回の休憩が終了している場合はエラーメッセージを表示
                    return redirect()->back()->with('error', '前回の休憩が終了していません。');
                }

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

            return redirect()->back()->with('success', '休憩を終了しました。');

            // 前回の休憩が終了している場合はエラーメッセージを表示
            return redirect()->back()->with('error', '前回の休憩が終了していません。');
        }
    }
