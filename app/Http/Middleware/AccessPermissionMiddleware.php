<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;
use App\Models\RolePermission;
use Auth;
use App\Models\User;

class AccessPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // $user = User::with('roles')->find(Auth::id());
        // $roles = [];
        // foreach ($user->roles as $key => $role) {
        //     $roles[] = $role->id;
        // }

        // $permission = RolePermission::Where('route', Route::currentRouteName())
        //     ->whereIn('role_id', $roles)->where('status', 1)->first();

        // $status = $permission ? $permission->status : 0 ;
        // if ($status == 1) {
        //     return $next($request);
        // }else if(Route::currentRouteName() == 'routes'){
        //     return $next($request);
        // }else{
        //     return response()->json([
        //         'status' => 'warning',
        //         'status_code' => 201,
        //         'message' => 'Sorry, Access Denied!',
        //     ]);
        // }

        return $next($request);
    }
}
