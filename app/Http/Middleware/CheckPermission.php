<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\Group_user;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response

    {
        $group_id = $request->route('group_id');
        $user_id = Auth::user()->id;
        $data = Group_user::query()->where("group_id",$group_id )->where("user_id",$user_id)->first();
        if ($data) {
            return $next($request);
        } else {
            abort(404);
        }
    }
}
