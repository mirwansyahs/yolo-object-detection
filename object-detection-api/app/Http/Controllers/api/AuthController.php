<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{
    
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Ambil semua data pergerakan karung beras",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
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
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();

        return response()->json(['message' => 'Berhasil logout']);
    }

    
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Ambil semua data profile pengguna",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
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
    public function profile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($user);
    }
}
