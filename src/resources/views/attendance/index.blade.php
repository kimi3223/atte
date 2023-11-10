@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/data.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-pagination.css') }}">
@endsection

@section('content')
<table class="Table">
    <thead class="Table-Head">
        <div class="Table-Head-data">
            <a href="{{ route('attendance.index', $selectedDate->copy()->subDay()->format('Y-m-d')) }}"><</a>
            <span>{{ $selectedDate->format('Y-m-d') }}</span>
            <a href="{{ route('attendance.index', $selectedDate->copy()->addDay()->format('Y-m-d')) }}">></a>
        </div>
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
            <td class="Table-Body-Row-Cell">{{ $attendance->user->name }}</td>
            <td class="Table-Body-Row-Cell">{{ $attendance->start_time->format('H:i:s') }}</td>
            <td class="Table-Body-Row-Cell">
                @if($attendance->end_time)
                {{ $attendance->end_time->format('H:i:s') }}
                @else
                勤務終了していません
                @endif
            </td>
            <td class="Table-Body-Row-Cell">{{ \Carbon\CarbonInterval::minutes($attendance->break_time)->cascade()->format('%H:%I:%S') }}</td>
            <td class="Table-Body-Row-Cell">{{ \Carbon\CarbonInterval::minutes($attendance->work_time)->cascade()->format('%H:%I:%S') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination" style="text-align: center;">
    @if ($attendanceData->onFirstPage())
        <span class="pagination-box pagination-disabled">&lsaquo;</span>
    @else
        <a href="{{ $attendanceData->previousPageUrl() }}" class="pagination-item pagination-prev">&lsaquo;</a>
    @endif

    @for ($i = 1; $i <= $attendanceData->lastPage(); $i++)
        @if ($i == $attendanceData->currentPage())
            <span class="pagination-box pagination-current-box">
                <span class="pagination-item pagination-current">{{ $i }}</span>
            </span>
        @else
            <span class="pagination-box">
                <a href="{{ $attendanceData->url($i) }}" class="pagination-item">{{ $i }}</a>
            </span>
        @endif
    @endfor


    @if ($attendanceData->hasMorePages())
        <a href="{{ $attendanceData->nextPageUrl() }}" class="pagination-box" style="border-radius: 0 10px 10px 0;">&rsaquo;</a>
    @else
        <span class="pagination-box pagination-disabled" style="border-radius: 0 10px 10px 0;">&rsaquo;</span>
    @endif
</div>
@endsection