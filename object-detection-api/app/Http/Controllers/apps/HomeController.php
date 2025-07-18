<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index(Request $request)
    {
        // Tampilkan halaman dashboard
        return view('apps.index');
    }
}
