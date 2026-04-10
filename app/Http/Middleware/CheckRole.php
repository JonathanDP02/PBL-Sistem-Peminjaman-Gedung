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

        // Scope khusus Admin_Unit: kontrol akses berdasarkan level unit
        if ($userRoleName === 'Admin_Unit') {
            $userUnit = $user->unit;
            
            if ($userUnit) {
                // Ambil unit_id dari route parameter jika ada
                $routeUnitId = $request->route('unit_id') ?? optional($request->route('unit'))->id;
                
                if ($routeUnitId) {
                    // Validasi akses berdasarkan level unit
                    $targetUnit = \App\Models\Unit::find($routeUnitId);
                    
                    if ($targetUnit) {
                        // Jika user level = Jurusan (level 2), bisa akses Jurusan sendiri atau Organisasi (level 3) anak
                        if ($userUnit->level === 'Jurusan') {
                            // Bisa akses unit sendiri atau unit dengan parent_id = unit_id sendiri
                            if ($targetUnit->id !== $userUnit->id && $targetUnit->parent_id !== $userUnit->id) {
                                return response()->json([
                                    'message' => 'Akses Ditolak. Anda hanya dapat akses unit Anda sendiri atau bawahan langsung.'
                                ], 403);
                            }
                        }
                        // Jika user level = Organisasi (level 3), hanya bisa akses unit sendiri
                        else if ($userUnit->level === 'Organisasi') {
                            if ($targetUnit->id !== $userUnit->id) {
                                return response()->json([
                                    'message' => 'Akses Ditolak. Anda hanya dapat mengelola unit Anda sendiri.'
                                ], 403);
                            }
                        }
                    }
                }
                
                // Set scope unit_id untuk referensi di controller
                $request->attributes->set('scope_unit_id', (int) $user->unit_id);
                $request->attributes->set('user_unit_level', $userUnit->level);
            }
        }
        
        return $next($request);
    }
}
