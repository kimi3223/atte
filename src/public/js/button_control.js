document.addEventListener('DOMContentLoaded', function() {
    var breakStartButton = document.getElementById('breakStartButton');
    var breakEndButton = document.getElementById('breakEndButton');

    // 休憩開始ボタンがクリックされたときの処理
    breakStartButton.addEventListener('click', function() {
        breakStartButton.disabled = true; // 休憩開始ボタンを無効化
        breakEndButton.disabled = false; // 休憩終了ボタンを有効化
    });

    // 休憩終了ボタンがクリックされたときの処理
    breakEndButton.addEventListener('click', function() {
        breakStartButton.disabled = false; // 休憩開始ボタンを有効化
        breakEndButton.disabled = true; // 休憩終了ボタンを無効化
    });
});
