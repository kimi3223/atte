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
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <p>{{ Auth::user()->name }}さん お疲れ様です！</p>
</div>

<div class="attendance__content">
    <div class="attendance__panel">
            <form class="attendance__button" action="{{ route('start.work') }}" method="post">
                @csrf
                <button class="attendance__button-start" type="submit" {{ !$workStarted ? 'disabled' : '' }}>勤務開始</button>
            </form>
            <form class="attendance__button" action="{{ route('end.work') }}" method="post" >
                @csrf
                <button class="attendance__button-start" type="submit" {{ $workEnded ? 'disabled' : '' }}>勤務終了</button>
            </form>
    </div>
    <div class="attendance__panel">
            <form class="attendance__button_break"  action="{{ url('/break/start') }}" method="post" >
                @csrf
                <button id="startBreakButton" class="attendance__button-start" type="submit" >休憩開始</button>
            </form>
            <form class="attendance__button_break" action="{{ url('/break/end') }}" method="post" >
                @csrf
                <button id="endBreakButton" class="attendance__button-start" type="submit" >休憩終了</button>
            </form>
    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    document.addEventListener('DOMContentLoaded', function() {

    var startBreakButton = document.getElementById('startBreakButton');
    var endBreakButton = document.getElementById('endBreakButton');
    var workStarted = false;
    var workEnded = {{ $workEnded ? 'true' : 'false' }}; // ビューからの変数を取得

    // 勤務が開始された時の処理
    function handleWorkStart() {
        workStarted = true; // 勤務が開始された状態に設定
        startBreakButton.disabled = false; // 休憩開始ボタンを有効化
        endBreakButton.disabled = true; // 休憩終了ボタンを無効化
    }

    // 勤務が終了された時の処理
    function handleWorkEnd() {
        workStarted = false; // 勤務が終了された状態に設定
        startBreakButton.disabled = true; // 休憩開始ボタンを無効化
        endBreakButton.disabled = true; // 休憩終了ボタンを無効化
    }

    // 休憩開始ボタンの初期状態
    startBreakButton.disabled = {{ $breakStarted ? 'true' : 'false' }};
    endBreakButton.disabled = {{ $breakStarted ? 'false' : 'true' }};

    // 勤務開始がされた場合、休憩開始ボタンを活性化
    document.querySelector('.attendance__button').addEventListener('submit', function() {

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/break/start', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                var response = JSON.parse(xhr.responseText);
                alert(response.message); // メッセージを表示
                startBreakButton.disabled = true;
                endBreakButton.disabled = false;
            } else {
                // エラーの場合の処理
                alert('エラーが発生しました。');
            }
        };
        xhr.onerror = function () {
            // ネットワークエラー等の場合の処理
            alert('ネットワークエラーが発生しました。');
        };
        xhr.send();
        return false;
        });

    // 終了ボタンを押した場合の処理
    endBreakButton.addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/break/end', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                var response = JSON.parse(xhr.responseText);
                alert(response.message); // メッセージを表示
                startBreakButton.disabled = false;
                endBreakButton.disabled = true;
            } else {
                // エラーの場合の処理
                alert('エラーが発生しました。');
            }
        };
        xhr.onerror = function () {
            // ネットワークエラー等の場合の処理
            alert('ネットワークエラーが発生しました。');
        };
        xhr.send();
    });
        // 勤務開始されたら休憩開始ボタンを活性化
        if (workStarted) {
            startBreakButton.disabled = false;
        }

        // 勤務終了したら休憩開始ボタンと休憩終了ボタンを非活性化
        if (workEnded) {
            startBreakButton.disabled = true;
            endBreakButton.disabled = true;
        }
});
</script>
@endsection