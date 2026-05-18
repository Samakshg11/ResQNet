<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        $user = $request->user();

        if (!in_array($user->role, $roles)) {
            if ($request->is('sos/my') && in_array($user->role, ['gov_admin', 'super_admin'])) {
                return redirect('/dashboard');
            }
            if ($request->is('agencies*')) {
                abort(403, 'Forbidden');
            }
            
            // Default fallback
            switch ($user->role) {
                case 'victim': return redirect('/sos/my');
                case 'volunteer': return redirect('/volunteer/dashboard');
                case 'agency_admin': return redirect('/agency/dashboard');
                case 'gov_admin':
                case 'super_admin': return redirect('/dashboard');
                default: abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
