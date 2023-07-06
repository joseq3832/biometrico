<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SoloDocente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        switch(auth::user()->tippersona){
            case ('Administrativo'):
                return redirect('administrador');//si es administrador continua al HOME
            break;
			case('Estudiante'):
                return redirect('estudiante');// si es un usuario normal redirige a la ruta USER
			break;	
            case ('Docente'):
                return $next($request);//si es administrador redirige al moderador
            break;
            case('Supervisor'):
                return redirect('supervisor');/// si es un director redirige a la ruta director
			break;
        }
    }
}
