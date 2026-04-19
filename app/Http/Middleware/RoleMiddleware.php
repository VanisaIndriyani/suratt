<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $userRole = strtolower(trim((string) $user->role));
        $allowedRoles = array_map(static fn (string $role) => strtolower(trim($role)), $roles);

        if ($allowedRoles !== [] && ! in_array($userRole, $allowedRoles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
