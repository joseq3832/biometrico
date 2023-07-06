<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periodo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('soloadministrador',['only'=>'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        switch(auth::user()->tippersona){
            case ('Administrativo'):
                return view('administrador/administradorPrincipal');//si es administrador continua al HOME
            break;
			case('Estudiante'):
                return redirect('estudiante/estudiantePrincipal');// si es un usuario normal redirige a la ruta USER
			break;	
            case ('Docente'):
                return redirect('docente/docentePrincipal');//si es administrador redirige al moderador
            break;
            case('Supervisor'):
                return redirect('supervisor');/// si es un director redirige a la ruta director
			break;	
        }
        /*
        if ($User->tippersona === "Administrativo") {
            $Rol = Rol::RolesAdministrador($User->codpersona);
            if (empty($Rol)) {
                return view('construccion');
            } else {
                return view('administrador/administradorPrincipal');
            }
        } else if ($User->tippersona === "Docente") {
            return view('docente/docentePrincipal');
        } else if ($User->tippersona === "Estudiante") {
            return view('estudiante/estudiantePrincipal');
        }else{
            return view('construccion');
        }**/
    }
    
    public function AdministradorInformacion()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $informacionPersonal = Persona::join('tadministrativo', 'tadministrativo.codpersona', '=', 'tpersona.codpersona')
                ->where('tadministrativo.codpersona', $id)->get();

            if (!is_null($informacionPersonal)) {
                return view('administrador/administradorInformacion', compact('informacionPersonal'));
            } else {
                return response('Administrador no encontrado', 404);
            }
        } else {
            return view('auth/login');
        }
    }


}
