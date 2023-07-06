<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Administrador;
use App\Models\PeriodoSeccion;
use App\Models\PeriodoSeccionParalelo;
use App\Models\SeccionesDePeriodo;
use App\Models\SolicitudJustificacion;
use App\Exports\pruebaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AdministradorController extends Controller
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
        return view('administrador');
    }

    public function AdministradorPrincipal()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $notificaraviso = DocenteMateria::CorreosAviso($ultimoPeriodo->codperiodo);
            return view('administrador/administradorPrincipal', compact('notificaraviso'));
        } else {
            return view('auth/login');
        }
    }
    
    public function InformacionAdministrador()
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

    public function UpdateAdministrador(Request $request, $codpersona){
        if (Auth::check()) {
            $id = Auth::id();
            $v = Validator::make($request->all(), [
                'convencional' => 'required|string|max:10',
                'celular' => 'required|string|max:10',
                'estado_civil' => 'required',
                'email' => 'required|email', Rule::unique('tpersona')->ignore($id),
                'foto' => 'mimes:jpg,jpeg,png'
            ]);

            if ($v->fails()) {
                $message = 'Datos mal ingresados, intentelo nuevamente';
                return redirect()->back()->withInput()->withErrors($v->errors())->with('messagedanger', $message);
            } else {
                $id = Auth::id();
                $informacionPersonal = Persona::where('tpersona.codpersona', $id)->first();
                $informacionPersonal->telconvencionalpersona = $request->convencional;
                $informacionPersonal->telcelularpersona = $request->celular;
                $informacionPersonal->corpersona = $request->email;
                $foto = $request->foto;
                if (!is_null($foto)) {
                    /*$extension = $request->file('foto')->getClientOriginalExtension();
                    $file_name = bcrypt($id) . '.' . $extension;
                    $request->file('foto')->storeAs('public', $file_name);
                    $informacionPersonal->huella = $file_name;*/
                    $extension = $request->file('foto')->getClientOriginalExtension();
                    $file_name = bcrypt($id) . '.' . $extension;
                    //$request->file('foto')->storeAs('public', $file_name);
                    //$request->file('foto')->move(public_path('foto',$file_name));
                    $request->file('foto')->move('storage', $file_name);
                    $informacionPersonal->huella = $file_name;
                }
                $informacionPersonal->save();

                $informacionPersonal = Administrador::where('tadministrativo.codpersona', $id)->first();
                $informacionPersonal->estciviladministrativo = $request->estado_civil;
                $informacionPersonal->save();
                $message = 'Información Modificada';
                return redirect()->route('InformacionAdministrador')->with('message', $message);
            }
        } else {
            return view('auth/login');
        }
    }

    public function AsistenciasParaleloAdministrador()
    {
        if (Auth::check()) {
            $periodos = Periodo::listarPeriodos();

            return view('administrador/asistenciasParalelo', compact('periodos'));
        } else {
            return view('auth/login');
        }
    }
    public function getCalificaciones(Request $request, $codperiodo)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $EstudiantesConCalificaciones = PeriodoSeccionParalelo::Paralelos($codperiodo);
                return response()->json($EstudiantesConCalificaciones);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getSecciones(Request $request, $codperiodo)
    {
        if (Auth::check()) {
            if ($request->ajax()) {               
                $Secciones = DB::table('tseccion')
                ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
                ->select('tseccion.codseccion','tseccion.nomseccion','tperiodoseccion.codperiodoseccion')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->orderby('tseccion.nomseccion', 'ASC')
                ->get();
                return response()->json($Secciones);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getParalelos(Request $request, $codperiodo, $codseccion)
    {
        if (Auth::check()) {
            if ($request->ajax()) {               
                $Paralelos = DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codparalelo')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.numestudiantesmatriculados', '>', 0)
                ->orderby('tperiodoseccionparalelo.codparalelo', 'ASC')
                ->get();
                return response()->json($Paralelos);
            }
        } else {
            return view('auth/login');
        }
    }
 
    public function getMateriasAlaFecha(Request $request, $codperiodo, $codseccion, $codparalelo, $fecha)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $dias = array('LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO','DOMINGO');
 
                $dia = $dias[(date('N', strtotime($fecha))) - 1];

                $codgo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

                $Materia = DB::table('tmateria')
                ->join('thora', 'tmateria.codmateria', '=', 'thora.codmateria')
                ->join('thorariodia', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
                ->join('thorariofase', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
                ->join('tfase', 'thorariofase.codfase', '=', 'tfase.codfase')
                ->select('tmateria.codmateria','tmateria.nommateria','tfase.codperiodoseccionparalelo','thorariodia.codhorariodia','tfase.codfase','thorariofase.codhorariofase')
                ->where('thorariodia.coddia', '=', $dia)
                ->where('tfase.feciniciofase', '<=', $fecha)
                ->where('tfase.fecfinfase', '>=', $fecha)
                ->where('tfase.codperiodoseccionparalelo', '=', $codgo)
                ->distinct('tmateria.codmateria')
                ->get();
                return response()->json($Materia);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getEstudiantesPresentes(Request $request, $codmateria, $fecha)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $Estudiantes = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->select('tasistencia.codasistencia','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $fecha)
                ->distinct('tpersona.codpersona')
                ->get();
                return response()->json($Estudiantes);
            }
        } else {
            return view('auth/login');
        }
    }
    public function getEstudiantesDelParalelo(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $Estudiantes = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->orderby('tpersona.apepersona', 'ASC')
                ->get();
                return response()->json($Estudiantes);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getAsistenciaEstudianteFecha(Request $request,  $codperiodo, $codseccion, $codparalelo, $codmateria, $fecha)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 

                $Estudiantes = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $fecha)
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();
                return response()->json($Estudiantes);

                
            }
        } else {
            return view('auth/login');
        }
    }
    public function getListaEstudiantesActivos(Request $request,  $codperiodo, $codseccion, $codparalelo)
    {
        //->where('testudiante.estestudiante', '=', 'ACTIVO')
        if (Auth::check()) {
            if ($request->ajax()) { 

                $Estudiantes = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();
                return response()->json($Estudiantes);

                
            }
        } else {
            return view('auth/login');
        }
    }


    ///////////////////////////////////horario de clases////////////////////////////////////////////

    public function AdministradorHorarioClasesEstudiantes()
    {
        if (Auth::check()) {
            $periodos = Periodo::listarPeriodos();
            return view('administrador/administradorHorarioClases', compact('periodos'));
        } else {
            return view('auth/login');
        }
    }
    public function getHorarios(Request $request, $codperiodo)
    {
        if ($request->ajax()) {
            $Horarios = PeriodoSeccionParalelo::ParalelosHorarios($codperiodo);
            return response()->json($Horarios);
        }
    }
   
    /*

    public function getAsistenciaEstudianteFecha(Request $request, $codpersona, $codmateria, $fecha)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 

                $Estudiantes = DB::table('tasistencia')
                ->select('tasistencia.estasistencia')
                ->where('tasistencia.codpersona', '=', $codpersona)
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $fecha)
                ->first();
                return response()->json($Estudiantes);

                
            }
        } else {
            return view('auth/login');
        }
    }*/
    

    

    
    /*
    public function getEstudiantesDelParalelo(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $Estudiantes = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->orderby('tpersona.apepersona', 'ASC')
                ->get();
                return response()->json($Estudiantes);
            }
        } else {
            return view('auth/login');
        }
    }*/

    

    public function getEstudiantesDeParalelo(Request $request, $codperiodo, $codseccion ,$codparalelo,$fecha)
    {
        if (Auth::check()) {
            if ($request->ajax()) {      
                $codperiodoseccionparalelo = DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->get();

                $codfase = DB::table('tfase')
                ->select('tfase.codfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
                ->where('tfase.feciniciofase', '<=', $fecha)
                ->where('tfase.fecfinfase', '>=', $fecha)
                ->get();

                $Materia = DB::table('tmateria')
                ->join('thora', 'tmateria.codmateria', '=', 'thora.codmateria')
                ->join('thorariodia', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
                ->join('thorariofase', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
                ->join('tfase', 'thorariofase.codfase', '=', 'tfase.codfase')
                ->select('tmateria.codmateria','tmateria.nommateria')
                ->where('thorariodia.coddia', '=', 'LUNES')
                ->where('tfase.feciniciofase', '<=', $fecha)
                ->where('tfase.fecfinfase', '>=', $fecha)
                ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
                ->get();

                return response()->json($Materia);
            }
        } else {
            return view('auth/login');
        }
    }

    ///////////////////////asistencia paralelorango//////////////////
    public function AsistenciasParaleloRangoAdministrador()
    {
        if (Auth::check()) {
            $periodos = Periodo::listarPeriodos();

            return view('administrador/asistenciaParaleloMateria', compact('periodos'));
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////asistencia porcentaje//////////////////
    public function ProcentajesDeAsistenciaEstudiante()
    {
        if (Auth::check()) {
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();

            return view('administrador/porcentajesAsistencia', compact('ultimoPeriodo'));
        } else {
            return view('auth/login');
        }
    }

    public function getAsistenciaIndividualPorcentaje(Request $request, $cedpersona)
    {
        
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion','testudiante.codperiodo')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tpersona.cedpersona', '=', $cedpersona)
                ->get();
                
                $codpersona =  $Persona[0]->codpersona;
                $codseccion =  $Persona[0]->codseccion;
                $codparalelo =  $Persona[0]->codparalelo;
                $codperiodo =  $Persona[0]->codperiodo;
                $materias = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.fecfinfase')
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderby('tmateria.codmateria', 'ASC')
                ->get();

                /*$materias = DB::table('tmateria')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria')
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();*/


                $porcentajes=[];
                foreach ($materias as $materia) {


                    $contadorAusente1=0;
                        $cantidadAusente1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $codpersona)
                        ->where('tasistencia.codmateria', '=',  $materia->codmateria)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente1[0]->user_count != 0){
                            $contadorAusente1=$cantidadAusente1[0]->user_count;
                        }else{
                            $contadorAusente1=0;
                        }
                        

                        $contadorPresente1=0;
                        $cantidadPresente1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $codpersona)
                        ->where('tasistencia.codmateria', '=',  $materia->codmateria)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente1[0]->user_count != 0){
                            $contadorPresente1=$cantidadPresente1[0]->user_count;
                        }else{
                            $contadorPresente1=0;
                        }

                        $contadorPresente1real=$materia->numhorasmateria - $contadorAusente1;

                        $contadorJustificado1=0;
                        $cantidadJustificado1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $codpersona)
                        ->where('tasistencia.codmateria', '=',  $materia->codmateria)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado1[0]->user_count != 0){
                            $contadorJustificado1=$cantidadJustificado1[0]->user_count;
                        }else{
                            $contadorJustificado1=0;
                        }
                        

                        $contadorAtrazo1=0;
                        $cantidadAtrazo1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $codpersona)
                        ->where('tasistencia.codmateria', '=', $materia->codmateria)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo1[0]->user_count != 0){
                            $contadorAtrazo1=$cantidadAtrazo1[0]->user_count;
                        }else{
                            $contadorAtrazo1=0;
                        }
                    
                        $por=(($contadorPresente1real)*100)/$materia->numhorasmateria;
                    
                    $array = array(
                            "codpersona" => $Persona[0]->codpersona,
                            "apepersona" => $Persona[0]->apepersona,
                            "nompersona" => $Persona[0]->nompersona,
                            "codparalelo" => $Persona[0]->codparalelo,
                            "codseccion" => $Persona[0]->codseccion,
                            "nomseccion" => $Persona[0]->nomseccion,
                            "nommateria" => $materia->nommateria,
                            "numhorasmateria" => $materia->numhorasmateria,
                            "contadorAusente1" => $contadorAusente1,
                            "contadorPresente1" => $contadorPresente1real,
                            "contadorJustificado1" => $contadorJustificado1,
                            "contadorAtrazo1" => $contadorAtrazo1,
                            "porcentaje" => $por,
                            
                    );
                    
                    
                    $porcentajes[]=$array;


                }


                if (!is_null($porcentajes)) {
                    return response()->json($porcentajes);
                } else {
                    return response('No se encontro al estudiante', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////

    public function getMaterias(Request $request, $codperiodo, $codseccion, $codparalelo)
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

    
    public function getFechas_Materia(Request $request, $codperiodoseccionparalelo, $codmateria)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $fechasmateria = DB::table('tfasemateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tfase.codfase','tfasemateria.codfasemateria','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
                ->where('tfasemateria.codmateria', '=', $codmateria)
                ->distinct('tfasemateria.codfasemateria')
                ->get();
                return response()->json($fechasmateria);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getAsistenciaEstudianteRangoFecha(Request $request,  $codperiodo, $codseccion, $codparalelo, $codmateria, $fechainicio, $fechafin)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 

                $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.fecfinfase')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tmateria.codmateria', '=', $codmateria)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();
                $Fechas = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tasistencia.fecha','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '>=', $fechainicio)
                ->where('tasistencia.fecha', '<=', $fechafin)
                ->distinct('tasistencia.fecha')->orderByRaw('tasistencia.fecha ASC , tpersona.apepersona ASC')
                ->get();


                $EstudiantesLista = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia','tasistencia.fecha')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $Fechas[0]->fecha)
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();


//https://codea.app/blog/generar-json

               

                $Estudiantes = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tasistencia.fecha','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '>=', $fechainicio)
                ->where('tasistencia.fecha', '<=', $fechafin)
                ->distinct()->orderByRaw('tasistencia.fecha ASC , tpersona.apepersona ASC')
                ->get();
//////////////////////////////////////////////
                $Porcentajes=[];
                foreach ($EstudiantesLista as $estudiante) {

                    $contador=0;
                    $porcentaje = DB::table('tasistencia')
                    ->select(DB::raw('count(*) as user_count'))
                    ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                    ->where('tasistencia.codmateria', '=', $codmateria)
                    ->where('tasistencia.estasistencia', '<>', 1)
                    ->get();
                    $contador=$porcentaje[0]->user_count;
                    
                    $array = array(
                            "codpersona" => $estudiante->codpersona,
                            "apepersona" => $estudiante->apepersona,
                            "nompersona" => $estudiante->nompersona,
                            "numhorasmateria" => $Materia[0]->numhorasmateria,
                            "numeroasistencias" => $contador,
                    );
                    
                    
                    $Porcentajes[]=$array;


                }







////////////////////////////////////////////////////////
                $data =[
                    'EstudiantesLista'=>$EstudiantesLista,
                    'Estudiantes'=>$Estudiantes,
                    'Fechas' => $Fechas,
                    'Porcentajes' => $Porcentajes
                ];
                //return response()->json($EstudiantesLista);
                return response()->json($data,200,[]);

                
            }
        } else {
            return view('auth/login');
        }
    }
    //////////////////////////justificaciones///////////////////////////////////////
    public function RegistroDeJustificacionDeEstudianteAdministrador()
    {
        if (Auth::check()) {
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();

            return view('administrador/justificacionEstudiente', compact('ultimoPeriodo'));
        } else {
            return view('auth/login');
        }
    }

    
    public function getDatosPersona(Request $request, $ultimoPeriodo ,$cedpersona)
    {
        if (Auth::check()) {
            if ($request->ajax()) {   
                $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('testudiante.codperiodo', '=', $ultimoPeriodo)
                ->where('tpersona.cedpersona', '=', $cedpersona)
                ->get();
                if (!is_null($Persona)) {
                    return response()->json($Persona);
                } else {
                    return response('Estudiante no encontrado', 404);
                }
                
            }
        } else {
            return view('auth/login');
        }
    }

    public function getMateriaParaJustificacion(Request $request, $codperiodo, $cedpersona ,$fecha)
    {
        if (Auth::check()) {
            if ($request->ajax()) {      
                $codpersona = DB::table('tpersona')
                ->select('tpersona.codpersona')
                ->where('tpersona.cedpersona', '=', $cedpersona)
                ->first()
                ->codpersona;

                $Materia = DB::table('tasistencia')
                ->join('tmateria', 'tasistencia.codmateria', '=', 'tmateria.codmateria')
                ->select('tmateria.codmateria','tmateria.nommateria','tasistencia.codpersona')
                ->where('tasistencia.fecha', '=', $fecha)
                ->where('tasistencia.codpersona', '=', $codpersona)
                ->distinct('tmateria.codmateria')
                ->get();

                return response()->json($Materia);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getSolicitudJustificacion(Request $request, $codperiodo, $cedpersona ,$numeroSolicitud)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 

                $codpersona=DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->select('tpersona.codpersona')
                ->where('tpersona.cedpersona', '=', $cedpersona)
                ->where('testudiante.codperiodo', '=', $codperiodo)
                ->first()
                ->codpersona;
                $cedpersonaPrueba='0605575091';
                $numeroSolicitudPrueba='2021-SJ-04';
                if (!is_null($codpersona)) {
                    $Venta = DB::table('tventa')
                    ->join('tventausuario', 'tventa.codventa', '=', 'tventausuario.codventa')
                    ->select('tventa.codventa','tventa.codigo','tventa.descripcion','tventausuario.estado')
                    ->where('tventa.tipo', '=', 'Justificacion')
                    ->where('tventa.cedula', '=', $cedpersonaPrueba)
                    ->where('tventa.codigo', '=', $numeroSolicitudPrueba)
                    ->distinct('tventa.codventa')
                    ->get();
                    if(!is_null($Venta)){
                        return response()->json($Venta);
                    }else{
                        return response('No se encontro el numero de solicitud', 404);
                    }
                    
                } else {
                    return response('No se encontro estudiante con esa cedula', 404);
                }



                
            }
        } else {
            return view('auth/login');
        }
    }
    //////////////////////justificacion curso
    public function RegistroDeJustificacionDeCursoAdministrador()
    {
        if (Auth::check()) {
            
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $secciones = DB::table('tseccion')
        ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
        ->select('tseccion.codseccion','tseccion.nomseccion','tperiodoseccion.codperiodoseccion')
        ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
        ->orderby('tseccion.nomseccion', 'ASC')
        ->get();

            return view('administrador/justificacionCurso', compact('ultimoPeriodo','secciones'));
        } else {
            return view('auth/login');
        }
    }
    
    public function getEstadoDeAsistenciaAMateria(Request $request, $codpersona ,$codmateria, $fecha)
    {
        if (Auth::check()) {
            if ($request->ajax()) {   
                $Asistencia = DB::table('tasistencia')
                ->select('tasistencia.codasistencia','tasistencia.estasistencia','tasistencia.solicitud')
                ->where('tasistencia.codpersona', '=', $codpersona)
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $fecha)
                ->get();
                if (!is_null($Asistencia)) {
                    return response()->json($Asistencia);
                } else {
                    return response('No se encontro el estado de la asistencia', 404);
                }
                
            }
        } else {
            return view('auth/login');
        }
    }

    public function postAsistenciaCursoActualizar(Request $request){
        if (Auth::check())
        {
            $codseccion = $request->input('codseccion');
            $codmateria = $request->input('codmateria');
            $codperiodo = $request->input('codperiodo');
            $codparalelo = $request->input('codparalelo');
            $fecha = $request->input('fecha');
            $observacion = $request->input('observacion');
            $estasistencia = 3;

        $estudiantes = DB::table('tasistencia')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tpersona.codasistencia','tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.fecha', '=', $fecha)
                ->get();
                foreach ($estudiantes as $estudiante) {
                    $actualizarAsistencia = DB::update('update tasistencia set estasistencia = ? , observacion = ? where codasistencia = ?  and codpersona = ? and fecha = ?',[$estasistencia,$observacion,$codasistencia,$codpersona,$fecha]);
                }
            return back();
      

            


        }
        else{
            return redirect("/home");
        }
    }


    ///////////////////////////////////////////////listar justificaciones///////////////////

    public function ListaJustificacionesAdministrador(){
        if (Auth::check()) {
            $Periodos = Periodo::listarPeriodos();

            return view('administrador/listarJustificaciones', compact('Periodos'));
        } else {
            return view('auth/login');
        }
    }

    public function getListaJustificacionesAdministrador(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();
                $Justificaciones = DB::table('tmateria')
                ->join('tasistencia', 'tmateria.codmateria', '=', 'tasistencia.codmateria')
                ->join('thora', 'tasistencia.codhora', '=', 'thora.codhora')
                ->join('thorahorario', 'thora.codhorahorario', '=', 'thorahorario.codhorahorario')
                ->join('thorarioperiodoseccion', 'thorahorario.codhorarioperiodoseccion', '=', 'thorarioperiodoseccion.codhorarioperiodoseccion')
                ->join('tperiodoseccion', 'thorarioperiodoseccion.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->select('tmateria.codmateria','tmateria.nommateria','tasistencia.fecha','tasistencia.solicitud','tpersona.cedpersona','tpersona.nompersona','tpersona.apepersona')
                ->where('tasistencia.estasistencia', '=', 3) 
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderByRaw('tasistencia.fecha ASC , tpersona.apepersona ASC')
                ->get();
                if (!is_null($Justificaciones)) {
                    return response()->json($Justificaciones);
                } else {
                    return response('No se encontro materias designadas en el periodo actual', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }

















    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////



    
    public function postAsistenciaIndividualActualizar(Request $request){
        if (Auth::check())
        {
            $codpersona = $request->input('codpersona');
            $codmateria = $request->input('codmateria');
            $numSolicitud = $request->input('numSolicitud');
            $codasistencia = $request->input('codasistencia');
            $fecha = $request->input('fecha');
            $estasistencia = $request->input('estadoAsistencia');
            $actualizarAsistencia = DB::update('update tasistencia set estasistencia = ? , solicitud = ? where codpersona = ?  and codmateria = ? and fecha = ?',[$estasistencia,$numSolicitud ,$codpersona,$codmateria,$fecha]);
            return back();
        }
        else{
            return redirect("/home");
        }
    }

    //////////////////////////Reporte///////////////////////////////////////
    public function FormatoAsistencia()
    {
        
        if (Auth::check()) {
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $secciones = DB::table('tseccion')
            ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->select('tseccion.codseccion','tseccion.nomseccion','tperiodoseccion.codperiodoseccion')
            ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
            ->orderby('tseccion.nomseccion', 'ASC')
            ->get();

            return view('administrador/formatoAsistencia', compact('secciones','ultimoPeriodo'));
        } else {
            return view('auth/login');
        }
    }

    /////////////////////////////reporte asistencia mater


    
    public function AdministradorReporteDeAsistenciaMateria()
    {
        if (Auth::check()) {
            $periodos = Periodo::listarPeriodos();
            return view('administrador/reporteDeAsistenciaMateria', compact('periodos'));
        } else {
            return view('auth/login');
        }
    }

    public function getMateriasReporteAsistencia(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        if ($request->ajax()) {
            $Materias = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.fecfinfase')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();
            return response()->json($Materias);
        }
    }

    //////////////////////////cuadro general ///////////////////////////////////
    public function AdministradorReporteExcelDeAsistenciaMateria()
    {
        if (Auth::check()) {
            $periodos = Periodo::listarPeriodos();
            return view('administrador/cuadroGeneralAsistencia', compact('periodos'));
        } else {
            return view('auth/login');
        }
    }
    public function getParalelosCuadroGeneral(Request $request, $codperiodo)
    {
        if ($request->ajax()) {
            $Paralelos = DB::table('tseccion')
                ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
                ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo','tperiodoseccion.codperiodoseccion','tseccion.codseccion','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccionparalelo.numestudiantesmatriculados', '>', 0)
                ->distinct()->orderByRaw('tseccion.codseccion ASC , tperiodoseccionparalelo.codparalelo ASC')
                ->get();
                /// ->orderby('tseccion.codseccion', 'ASC')
           // $Horarios = PeriodoSeccionParalelo::ParalelosHorarios($codperiodo);
            return response()->json($Paralelos);
        }
    }

    public function getFechasLimiteInicioActual(Request $request, $codperiodo, $codseccion)
    {
        if ($request->ajax()) {

            $fechaActual=DB::select("select current_date as fecha");
            $fecha= $fechaActual[0]->fecha;
            //$fecha='2023-01-10';
            $fechasPeriodo = DB::table('tperiodo')
            ->select('tperiodo.codperiodo','tperiodo.fecinicioclases','tperiodo.fecfinclases')
            ->where('tperiodo.codperiodo', '=', $codperiodo)
            ->get();

            $Fechas=[];
                if($fecha > $fechasPeriodo[0]->fecfinclases){
                    $array = array(
                        "fechaMin" => $fechasPeriodo[0]->fecinicioclases,
                        "fechaMax" => $fechasPeriodo[0]->fecfinclases,
                    );
                }else{
                    $array = array(
                        "fechaMin" => $fechasPeriodo[0]->fecinicioclases,
                        "fechaMax" => $fecha,
                    );
                }
                $Fechas[]=$array;

            
            return response()->json($Fechas);
        }
    }

    public function getFechasLimiteInicioActualJustificacion(Request $request, $codperiodo, $codpersona)
    {
        if ($request->ajax()) {

            $fechaActual=DB::select("select current_date as fecha");
            $fecha= $fechaActual[0]->fecha;
            //$fecha='2023-01-10';
            $fechasPeriodo = DB::table('tperiodo')
            ->select('tperiodo.codperiodo','tperiodo.fecinicioclases','tperiodo.fecfinclases')
            ->where('tperiodo.codperiodo', '=', $codperiodo)
            ->get();

            $Fechas=[];
                if($fecha > $fechasPeriodo[0]->fecfinclases){
                    $array = array(
                        "fechaMin" => $fechasPeriodo[0]->fecinicioclases,
                        "fechaMax" => $fechasPeriodo[0]->fecfinclases,
                    );
                }else{
                    $array = array(
                        "fechaMin" => $fechasPeriodo[0]->fecinicioclases,
                        "fechaMax" => $fecha,
                    );
                }
                $Fechas[]=$array;

            
            return response()->json($Fechas);
        }
    }

    







    /*
    public function AdministradorReporteExcelDeAsistenciaMateria()
    {
        if (Auth::check()) {
            
            return Excel::download(new pruebaExport, 'products.xlsx');
        } else {
            return view('auth/login');
        }
    }*/
    /////////////////////////////////////////////////////////////////////////////
    

    
}
