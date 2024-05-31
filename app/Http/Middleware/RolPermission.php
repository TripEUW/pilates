<?php

namespace App\Http\Middleware;

use App\Helpers\Pilates;
use Closure;
use Illuminate\Http\Response;

class RolPermission
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
        return $next($request);
        if(Pilates::getRolPermissionStatus($request))
        return $next($request);
    
        if($request->ajax()){
        header('Content-Type: application/json; charset=utf-8');
        header('HTTP/1.1 500 Usted no tiene este permiso, contacte con el administrador.');
        die(json_encode(array('message' => 'ERROR', 'code' => 403),JSON_UNESCAPED_UNICODE));
        }
       
        //return response()->json(['response' => 'Usted no tiene permiso para realizar esta acción']);

        return redirect('restricted_permission');
    }
}
