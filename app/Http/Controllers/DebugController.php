<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function test()
    {
        return response()->json(['status' => 'OK', 'message' => 'Debug controller works']);
    }
}
