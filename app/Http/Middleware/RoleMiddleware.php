<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\Roles;
use Illuminate\Support\Facades\View;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {

        //die();
        if(!empty(Auth::user()->email) ){
            $roles = new Roles();
            $rolUser = $roles->getRoles();
            //var_dump($rolUser);
            $currentPath = $request->path(); // Obtiene la URI de la solicitud actual
            $currentPath = explode("/", $currentPath)[0];
            $currentRouteName = $request->route() ? $request->route()->getName() : 'Route name not available';
            //var_dump('Current Path: ' . $currentPath);
            //var_dump('Current Route Name: ' . $currentRouteName);
            if (array_key_exists($currentPath, $rolUser)) {      
                if($rolUser[$currentPath] == false){
                    //abort(403, 'Unauthorized');
                    return redirect()->to('dash');

                }       
            }

        }
       // die();
        /*if (!Auth::check() || !$rolUser) {
            abort(403, 'Unauthorized');
        }*/

        return $next($request);
    }
}
