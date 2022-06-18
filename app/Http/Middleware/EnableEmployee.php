<?php

namespace App\Http\Middleware;

use Closure;

class EnableEmployee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->permission())
         return $next($request);
         $request->session()->invalidate();
         return redirect('login')->withErrors(['error'=>'Tu cuenta ha sido deshabilitada']);
    }

    private function permission(){
    return auth()->user()->status=='enable' ? true : false;
    }
}
