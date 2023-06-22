<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use App\Http\Model\User;

class CheckAuthApi
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('api_token');
        $user = User::where('api_token',$token)->first();
        if(!$user || !$token){
            return \response()->json([],401);
        }
        return $next($request);
    }
}
