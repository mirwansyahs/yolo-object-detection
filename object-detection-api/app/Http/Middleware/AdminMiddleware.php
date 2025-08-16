<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        App::setLocale('id'); // Laravel locale
        Carbon::setLocale('id'); // Carbon locale
        // Cek jika tidak login melalui guard 'apps'
        if (!auth()->guard('web')->check()) {
            // Kalau request bukan AJAX/JSON, redirect ke login
            // if (! $request->expectsJson()) {
                // echo "masuk sini";
                // return redirect()->route('login'); // atau '/login'
            // }

            // Kalau request API, kembalikan unauthorized
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
    
}
