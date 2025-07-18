<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SackMovement;
use App\Models\Camera;
use Illuminate\Support\Facades\Redis;
use App\Jobs\ProcessSackDetection;

class SackMovementsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sack-movements",
     *     summary="Ambil semua data pergerakan karung beras",
     *     tags={"Sack Movements"},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="camera_id", type="integer", example=2),
     *                 @OA\Property(property="direction", type="string", example="in"),
     *                 @OA\Property(property="detected_at", type="string", format="date-time", example="2025-06-29T09:30:00Z"),
     *                 @OA\Property(property="sack_count", type="integer", example=5),
     *                 @OA\Property(property="image_path", type="string", example="images/in_001.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
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

    /**
     * @OA\Post(
     *     path="/api/sack-movements",
     *     summary="Simpan data pergerakan karung",
     *     tags={"Sack Movements"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"camera_id", "direction", "detected_at", "sack_count"},
     *             @OA\Property(property="camera_id", type="integer"),
     *             @OA\Property(property="direction", type="string", enum={"in", "out"}),
     *             @OA\Property(property="detected_at", type="string", format="date-time"),
     *             @OA\Property(property="sack_count", type="integer"),
     *             @OA\Property(property="image_path", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success"),
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'camera_id' => 'required|integer|exists:cameras,id',
                'direction' => 'required|string|in:in,out',
                'detected_at' => 'required|date',
                'sack_count' => 'required|integer|min:1',
                'image_path' => 'nullable|string|max:255',
            ]);    

            // dispatch(new ProcessSackDetection($validated));
            dispatch((new ProcessSackDetection($validated))->onConnection('rabbitmq')->onQueue('karung_detection'))->delay(now()->addSeconds(30));


            return response()->json(['message' => 'Data pergerakan karung berhasil disimpan.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal server error', 'error' => $th->getMessage()], 500);
        }
    }
}
