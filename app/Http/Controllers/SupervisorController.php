<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Docente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupervisorController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('solosupervisor',['only'=>'index']);
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('supervisor/supervisorPrincipal');
    }
    public function SupervisorInformacion()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $informacionPersonal = Persona::where('tpersona.codpersona', $id)->get();

            if (!is_null($informacionPersonal)) {
                return view('supervisor/supervisorInformacion', compact('informacionPersonal'));
            } else {
                return response('Supervisor no encontrado', 404);
            }
        } else {
            return view('auth/login');
        }
    }

    ///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
    public function AsistenciaFechaDeterminadaSupervisor(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('supervisor/asistenciaPorDia', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }
    public function getMateriasSupervisor(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 

                $codgo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

                $materias = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tfase.codfase','tmateria.codmateria','tmateria.nommateria','tfase.codfase','tfase.feciniciofase','tfase.fecfinfase','tfase.codperiodoseccionparalelo')
                ->where('tfase.codperiodoseccionparalelo', '=', $codgo)
                ->distinct('tmateria.codmateria')
                ->get();
                return response()->json($materias);
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////////////////////////////////Asistencia entre fechas////////////////////////////////////////////////////////////////////
    public function AsistenciaEntreFechasSupervisor(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('supervisor/asistenciaEntreFechas', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }



}
