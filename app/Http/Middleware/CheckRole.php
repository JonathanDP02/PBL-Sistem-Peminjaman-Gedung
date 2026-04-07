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
        
        $user = $request->user(); // Ambil data user yang sedang login
        $userRoleName = $user->role->name; // Ambil nama role user, pastikan relasi role sudah didefinisikan di model User
        
        // Cek apakah nama role user ada dalam daftar role yang diizinkan
        if (!in_array($userRoleName, $roles)) {
            return response()->json([
                'message' => 'Akses Ditolak. Role Anda tidak memiliki akses ke resource ini.'
            ], 403);
        }

        // Scope khusus AdminUnit: hanya boleh akses unit sendiri
        if ($userRoleName === 'AdminUnit') {
            $request->attributes->set('scope_unit_id', (int) $user->unit_id);

            $routeUnitId = $request->route('unit_id') ?? optional($request->route('unit'))->id;
            if ($routeUnitId && (int) $routeUnitId !== (int) $user->unit_id) {
                return response()->json([
                    'message' => 'Akses Ditolak. AdminUnit hanya boleh akses data unit sendiri.'
                ], 403);
            }
        }
        //pakai di controller bagian booking
        //$scopeUnitId = $request->attributes->get('scope_unit_id');
        
        return $next($request);
    }
}
