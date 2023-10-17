@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/data.css') }}">
@endsection

@section('content')
<table class="Table">
    <thead class="Table-Head">
        <div class="Table-Head-data">
            <a href="{{ route('attendance.index', $selectedDate->copy()->subDay()->format('Y-m-d')) }}"><</a>
            <span>{{ $selectedDate->format('Y-m-d') }}</span>
            <a href="{{ route('attendance.index', $selectedDate->copy()->addDay()->format('Y-m-d')) }}">></a>
        </div>
        <!-- ページネーションリンクを表示 -->
        <tr class="Table-Head-Row">
            <th class="Table-Head-Row-Cell">名前</th>
            <th class="Table-Head-Row-Cell">勤務開始</th>
            <th class="Table-Head-Row-Cell">勤務終了</th>
            <th class="Table-Head-Row-Cell">休憩時間</th>
            <th class="Table-Head-Row-Cell">勤務時間</th>
        </tr>
    </thead>
    <tbody class="Table-Body">
        @foreach($attendanceData as $attendance)
        <tr class="Table-Head-Row">
            <td class="Table-Body-Row-Cell">{{ $user->name }}</td>
            <td class="Table-Body-Row-Cell">{{ $attendance->start_time->format('H:i:s') }}</td>
            <td class="Table-Body-Row-Cell">
                @if($attendance->end_time)
                {{ $attendance->end_time->format('H:i:s') }}</td>
                @else
                勤務終了していません
                @endif
            <td class="Table-Body-Row-Cell">{{ \Carbon\CarbonInterval::minutes($attendance->break_time)->cascade()->format('%H:%I:%S') }}</td>
            <td class="Table-Body-Row-Cell">{{ \Carbon\CarbonInterval::minutes($attendance->work_time)->cascade()->format('%H:%I:%S') }}</td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
            </tr>
        </tfoot>
    </table>
@endsection