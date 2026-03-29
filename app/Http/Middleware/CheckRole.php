<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!$request->user()) {
            return response()->json([
                'message' => 'Tidak Terotorisasi. Silahkan login terlebih dahulu.'
            ], 401);
        }

        // Cek apakah nama role user ada dalam daftar role yang diizinkan
        $userRoleName = $request->user()->role->name;
        
        if (!in_array($userRoleName, $roles)) {
            return response()->json([
                'message' => 'Akses Ditolak. Role Anda tidak memiliki akses ke resource ini.'
            ], 403);
        }

        return $next($request);
    }
}
