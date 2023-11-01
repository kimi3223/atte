<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakTimeController;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('/start-work', [AttendanceController::class, 'startWork'])->name('start.work');
});
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/start-work', [AttendanceController::class, 'startWork']);
Route::post('/start-work', [AttendanceController::class, 'startWork'])->name('start.work');
Route::get('/end-work/{id}', [AttendanceController::class, 'endWork'])->name('end.work');
Route::post('/end-work', [AttendanceController::class, 'endWork'])->name('end.work');
Route::get('/attendance/{date?}', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/break/start', [BreakTimeController::class, 'startBreak'])->name('break.start');
Route::post('/break/end', [BreakTimeController::class, 'endBreak'])->name('break.end');
Route::post('/break/start', [BreakTimeController::class, 'startBreak']);
