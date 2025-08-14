<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminOrSpecificController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Allow all admins
        if ($user->hasRole('admin') ) {
            return $next($request);
        }
        
        // Allow only specific controllers (check by user ID or other attribute)
        if ($user->hasRole('controller')  && $this->isAllowedController($user)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }

    protected function isAllowedController($user)
    {
        // Implement your logic to identify specific controllers
        // For example, check user ID, email, or other attributes:
        return in_array($user->id, [20]); // IDs of allowed controllers
        // OR:
        // return $user->hasPermission('import_boxes'); // If you have permissions
    }
}
