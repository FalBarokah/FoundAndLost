<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::guard('admin')->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        $normalizedUserRole = in_array($user->role, ['super_admin', 'admin'], true) ? 'super_admin' : $user->role;
        $normalizedRoles = array_map(fn ($r) => in_array($r, ['super_admin', 'admin'], true) ? 'super_admin' : $r, $roles);

        if (! in_array($normalizedUserRole, $normalizedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized action.'], 403);
            }

            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Halaman ini hanya dapat diakses oleh '.implode(' / ', $roles).'.');
        }

        return $next($request);
    }
}
