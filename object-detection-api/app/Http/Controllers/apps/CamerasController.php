<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Camera;
use Yajra\DataTables\DataTables;

class CamerasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Camera::orderBy('name', 'ASC');
            // dd($data);
            return DataTables::of($data)
                ->addColumn('created_at', function ($row) {
                    return $row->created_at_label;
                })
                ->make(true);
        }
        
        return view('apps.camera.index');
    }
}
