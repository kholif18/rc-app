<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectAdminDeletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');
    
        if ($user->role === 'superadmin' && $user->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat menghapus superadmin');
        }

        return $next($request);
    }
}
