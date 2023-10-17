<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AtteController extends Controller
{
    public function index()
    {
        $attendanceDate = [];
        return view('attendance.index', [attendance]);
    }
}
