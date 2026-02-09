<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response('Test OK - ' . date('Y-m-d H:i:s'), 200)
            ->header('Content-Type', 'text/plain');
    }
    
    public function testBlade()
    {
        return view('test');
    }
    
    public function testSimple()
    {
        return view('test_simple');
    }
    
    public function testLayout()
    {
        return view('test_layout');
    }
    
    public function testFinal()
    {
        return view('test_final');
    }
}