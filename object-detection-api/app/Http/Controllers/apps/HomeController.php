<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SackMovement;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    function index(Request $request)
    {
        // Hitung jumlah karung masuk dan keluar
        $countIn = SackMovement::where('direction', 'in')->count();
        $countOut = SackMovement::where('direction', 'out')->count();

        // Tampilkan halaman dashboard
        return view('apps.index', compact('countIn', 'countOut'));
    }
}
