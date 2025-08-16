<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\SackMovement;
use Illuminate\Support\Facades\Redis;

class SackMovementsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SackMovement::with('camera')->orderBy('detected_at', 'desc');
            // dd($data);
            return DataTables::of($data)
                ->addColumn('direction', function ($row) {
                    return $row->direction_label;
                })
                ->addColumn('detected_at', function ($row) {
                    return $row->detected_at_label;
                })
                ->make(true);
        }
        
        return view('apps.sack_movements.index');
    }

    public function sackMovements(Request $request)
    {
        try {
            $cached = Redis::get('sackMovement:all');
            if(isset($cached)) {
                $sackMovement = json_decode($cached, FALSE);
            }else {
                $query = SackMovement::with('camera');

                if ($request->has('date')) {
                    $query->whereDate('detected_at', $request->date);
                }

                $sackMovement = $query->latest()->get();

                if (!$request->has('date')) {
                    Redis::setex('sackMovement:all', (60*10), $sackMovement->toJson());
                }
            }
            
            return response()->json($sackMovement, 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Gagal mengambil data pergerakan karung: ' . $th->getMessage()
            ], 404);
        }
    }
}
