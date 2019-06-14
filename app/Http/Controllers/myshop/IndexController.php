<?php

namespace App\Http\Controllers\myshop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function upload()
    {
        return view('myshop/upload');

    }

    public function do_upload(Request $request)
    {
        $path = $request->file('img')->store('');
        echo asset('storage'.'/'.$path); 
        dd($path);
    }
}
