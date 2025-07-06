<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            // Update last_activity hanya jika lebih dari 5 menit sejak terakhir update
            if (!$user->last_activity || $user->last_activity->diffInMinutes(now()) >= 5) {
                $user->last_activity = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
