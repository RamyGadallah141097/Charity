<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::guard('admin')->user(); // Ensure you are checking for admin users

        if (!$user || !$user->hasPermissionTo($permission)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا الجزء.',
                ], 403);
            }

            return redirect()
                ->back()
                ->with('error', 'ليس لديك صلاحية للوصول إلى هذا الجزء.');
        }

        return $next($request);
    }
}
