@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <p>{{ Auth::user()->name }} さんお疲れ様です！</p>
</div>

<div class="attendance__content">
    <div class="attendance__panel">
            <form class="attendance__button" action="{{ route('start.work') }}" method="post">
                @csrf
                    <button class="attendance__button-start" type="submit" {{ $workStarted ? '' : 'disabled' }}>勤務開始</button>
            </form>

            <form class="attendance__button" action="{{ route('end.work') }}" method="post" {{ $workStarted ? 'disabled' : '' }}>
                @csrf
                <button class="attendance__button-start" type="submit" >勤務終了</button>
            </form>
    </div>
    <div class="attendance__panel">
            <form class="attendance__button_break"  action="{{ url('/break/start') }}" method="post" {{ $workStarted ? 'disabled' : '' }}>
                @csrf
                <button class="attendance__button-start" type="submit" >休憩開始</button>
            </form>
            <form class="attendance__button_break" action="{{ url('/break/end') }}" method="post" {{ $breakEnded || !$breakStarted ? '' : 'disabled' }}>
                @csrf
                <button class="attendance__button-start" type="submit">休憩終了</button>
            </form>
    </div>
</div>
@endsection