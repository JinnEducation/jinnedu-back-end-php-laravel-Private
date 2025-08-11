<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SingleLoginSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $userInfo = User::find($user->id);
        if($userInfo->remember_token != $request->bearerToken()){
            $user->currentAccessToken()->delete();

            return response([
                'success' => false,
                'message' => 'تم تسجيل دخولك من مكان اخر',
                'msg_code' => '999'
            ],511);
        }
        return $next($request);
    }
}
