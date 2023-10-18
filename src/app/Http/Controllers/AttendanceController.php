<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;
use App\Http\Controllers\BreakTimeController;

class AttendanceController extends Controller
{
    public function index($date = null)
    {
        // コントローラー内でフラグを設定
        $totalBreakTime = 0; // 変数を定義
        $totalWorkTime = 0; // totalWorkTime も追加
        $selectedDate = $date ? Carbon::parse($date) : now();

        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $user = Auth::user();
        $attendance_id = 20231011;
        $attendances = Attendance::where('user_id', $user->id)->simplePaginate(5);

        $user = Auth::user();
        $attendanceData = Attendance::where('user_id', $user->id)
            ->whereDate('start_time', $selectedDate)
            ->get();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('start_time', $selectedDate)
            ->first();

        return view('attendance.index', [
            'user' => $user,
            'attendanceData' => $attendanceData,
            'totalBreakTime' =>$totalBreakTime,
            'totalWorkTime' => $totalWorkTime,
            'attendances' => $attendances,
            'selectedDate' => $selectedDate,
        ])->with('success', '勤務を開始しました。');
    }

    public function startWork(Request $request)
    {
        if(!Auth::check()) {
            return redirect()->route('login')->with('error', 'ログインしてください');
            }

            // ログインユーザーのIDを取得
        $userId = auth()->user()->id;
        $attendanceStarted = true;
            // ユーザーデータを取得
        $user = Auth::user();

            // 今日の日付を取得
        $today = now()->toDateString();

             // 前日の出勤情報を確認
        $yesterdayAttendance = Attendance::where('user_id', $userId)
                ->whereDate('start_time', now()->subDay()->toDateString())
                ->first();

            // 前日の出勤情報が存在し、かつ勤務終了していない場合は強制的に勤務終了させる
        if ($yesterdayAttendance && !$yesterdayAttendance->end_time) {
                $yesterdayAttendance->update(['end_time' => now()]);
            }


            // 既に勤務開始済みかどうかを確認
        $existingAttendance = Attendance::where('user_id', $userId)
                ->whereDate('start_time', $today)
                ->first();

        if ($existingAttendance) {
                return redirect()->back()->with('error', '今日は既に勤務を開始しています。');
            }

            // 勤務開始時間を現在の時刻で設定
        \Log::info("Start Work method called");

        $userId = auth()->user()->id;
        $startTime = now();

            // データベースに登録
        Attendance::create([
            'user_id' => $userId,
            'start_time' => $startTime,
        ]);

        $attendanceData = Attendance::where('user_id', $user->id)->get();
        foreach($attendanceData as $data) {
            $data->start_time = Carbon::parse($data->start_time);
        }

         // 日が跨いでいるかどうかを確認
        $currentDate = now()->toDateString();
        $lastAttendance = Attendance::where('user_id', $userId)
            ->whereDate('start_time', now()->subDay()->toDateString())
            ->first();

        if ($lastAttendance && !$lastAttendance->end_time) {
            $lastAttendance->update(['end_time' => now()]);
        }

        // 既に勤務開始済みかどうかを確認
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('start_time', $currentDate)
            ->first();

        if ($existingAttendance) {
            return redirect()->route('attendance.index')->with('error', '今日は既に勤務を開始しています。');
        }

        // 勤務開始時間を現在の時刻で設定
        \Log::info("Start Work method called");

        $startTime = now();

        // データベースに登録
        Attendance::create([
            'user_id' => $userId,
            'start_time' => $startTime,
        ]);


        return view('attendance.index', [
            'user' => $user,
            'attendanceData' => $attendanceData,
            'totalBreakTime' =>$totalBreakTime,
            'totalWorkTime' => $totalWorkTime,
            'attendances' => $attendances,
            'selectedDate' => $selectedDate,
            'attendanceStarted' => $attendanceStarted,
        ])->with('success', '勤務を開始しました。');
    }

    public function endWork(Request $request)
    {
            \Log::info("endWork method called");
            $user = Auth::user();
            $today = now();
            $break_time = 0;
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('start_time', $today)
                ->first();

            if (!$attendance) {
                return redirect()->back()->with('error', '勤務情報が見つかりません。');
            }

            if ($attendance->end_time) {
                return redirect()->back()->with('error', '既に勤務を終了しています。');
            }
            // 勤務終了時間を現在の時刻で設定
            $attendance->end_time = now();

            // 休憩時間の計算
            $breaks = BreakTime::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->whereNotNull('end_time')
                ->get();

            foreach ($breaks as $break) {
                $start_time = Carbon::parse($break->start_time);
                $end_time = Carbon::parse($break->end_time);
                $break_time += $start_time->diffInMinutes($end_time);
            }

             // attendancesテーブルのbreak_timeに休憩時間を追加
            $attendance->break_time = $break_time;

            $attendance->save();

            // 勤務時間の計算
            $start_time = Carbon::parse($attendance->start_time);
            $end_time = Carbon::parse($attendance->end_time);
            $work_time = $start_time->diffInMinutes($end_time);

            $attendance->work_time = $work_time;

            $attendance->save();

            return redirect()->back()->with('success', '勤務を終了しました。');
    }


    protected $dates = ['break_time', 'work_time'];

    public function getBreakTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getWorkTimeAttribute($value)
    {
        return Carbon::parse($value);
    }



    public function show($id)
    {
        $attendance = Attendance::find($id);
        $attendance->break_time = Carbon::parse($attendance->break_time);
        $attendance->work_time = Carbon::parse($attendance->work_time);
        $breakTimes = $attendance->breaks;

        return view('attendance.show', compact('attendance', 'breaks'));
    }

    public function store(Request $request)
    {
        $attendance = new Attendance;
        $attendance->attendance_id = now()->format('Ymd'); // 今日の日付を YYYYMMDD 形式でセットする
        // 他のプロパティにも値をセットする...
        $attendance->save();
        // ...
    }
}