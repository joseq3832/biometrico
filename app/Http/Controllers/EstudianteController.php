<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\Estudiante;
use App\Models\PeriodoSeccionParalelo;
use App\Models\Horario;
use App\Models\DocenteMateria;
use App\Models\Docente;
use App\Models\Administrador;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('soloestudiante',['only'=>'index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('estudiante/estudiantePrincipal');
    }
    public function EstudianteInformacion()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $informacionPersonal = Persona::join('testudiante', 'testudiante.codpersona', '=', 'tpersona.codpersona')
                ->join('tperiodo', 'testudiante.codperiodo', '=', 'tperiodo.codperiodo')
                ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
                ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->where('testudiante.codpersona', $id)->distinct('testudiante.codpersona')->get();

            if (!is_null($informacionPersonal)) {
                return view('estudiante/estudianteInformacion', compact('informacionPersonal'));
            } else {
                return response('Estudiante no encontrado', 404);
            }
        } else {
            return view('auth/login');
        }
    }
    public function UpdateEstudiante(Request $request, $codpersona){
        if (Auth::check()) {
            $id = Auth::id();
            $v = Validator::make($request->all(), [
                'convencional' => 'required|string|max:10',
                'celular' => 'required|string|max:10',
                'email' => 'required|email', Rule::unique('tpersona')->ignore($id),
                'direstudiante' => 'required|string|max:50',
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
                    $extension = $request->file('foto')->getClientOriginalExtension();
                    $file_name = bcrypt($id) . '.' . $extension;
                    $request->file('foto')->move('storage', $file_name);
                    $informacionPersonal->huella = $file_name;
                }
                $informacionPersonal->save();

                $informacionPersonal = Estudiante::where('testudiante.codpersona', $id)->first();
                $informacionPersonal->direstudiante = $request->direstudiante;
                $informacionPersonal->save();
                $message = 'Información Modificada';

                return redirect()->route('EstudianteInformacion')->with('message', $message);
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
    public function AsistenciaFechaDeterminadaEstudiante(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            
            $datosEstudiante = DB::table('testudianteparalelo')
            ->select('testudianteparalelo.codperiodo','testudianteparalelo.codseccion','testudianteparalelo.codparalelo')              
            ->where('testudianteparalelo.codestudiante', '=', $codPersona)
            ->where('testudianteparalelo.codperiodo', '=', $ultimoPeriodo)
            ->distinct('testudianteparalelo.codestudiante')
            ->get();
            $codperiodo= $datosEstudiante[0]->codperiodo;
            $codseccion= $datosEstudiante[0]->codseccion;
            $codparalelo= $datosEstudiante[0]->codparalelo;

           /* $Materias = DB::table('tmateria')
                ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->select('tdocentemateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria')              
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();*/

                $fechaActual=DB::select("select current_date as fecha");
            $fecha= $fechaActual[0]->fecha;
     
            //$fecha='2023-01-10';
            $fechasPeriodo = DB::table('tperiodo')
            ->select('tperiodo.codperiodo','tperiodo.fecinicioclases','tperiodo.fecfinclases')
            ->where('tperiodo.codperiodo', '=', $codperiodo)
            ->get();

            
                if($fecha > $fechasPeriodo[0]->fecfinclases){
                    
                        $fechaMin = $fechasPeriodo[0]->fecinicioclases;
                        $fechaMax = $fechasPeriodo[0]->fecfinclases;
                    
                }else{
                    $fechaMin = $fechasPeriodo[0]->fecinicioclases;
                    $fechaMax = $fecha;
                    
                }
                

                    if (!is_null($fechasPeriodo)) {
                        
                        return view('estudiante/asistenciaFechaDeterminadaEstudiante', compact('codperiodo','codseccion','codparalelo','fechaMin','fechaMax'));
                    } else {
                        return response('El estudiante no pertenece al periodo actual', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }
    public function getAsistenciaPorFechas(Request $request, $codperiodo, $codseccion, $fecha)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();
                $EstudiantesLista = DB::table('tmateria')
                ->join('tasistencia', 'tmateria.codmateria', '=', 'tasistencia.codmateria')
                ->join('thora', 'tasistencia.codhora', '=', 'thora.codhora')
                ->join('thorahorario', 'thora.codhorahorario', '=', 'thorahorario.codhorahorario')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->select('tmateria.codmateria','tmateria.nommateria','tasistencia.estasistencia','tasistencia.fecha','thorahorario.nomhorahorario')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tasistencia.codpersona', '=', $codPersona)
                ->where('tasistencia.fecha', '=', $fecha)
                ->distinct()->orderByRaw('thorahorario.nomhorahorario ASC')
                ->get();

                if (!is_null($EstudiantesLista)) {
                    return response()->json($EstudiantesLista);
                } else {
                    return response('No se encontro asistencias a esa fecha', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////////////////////////////////Asistencia entre fechas////////////////////////////////////////////////////////////////////
    public function AsistenciaEntreFechasEstudiante(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            
    
            $datosEstudiante = DB::table('testudianteparalelo')
            ->select('testudianteparalelo.codperiodo','testudianteparalelo.codseccion','testudianteparalelo.codparalelo')              
            ->where('testudianteparalelo.codestudiante', '=', $codPersona)
            ->where('testudianteparalelo.codperiodo', '=', $ultimoPeriodo)
            ->distinct('testudianteparalelo.codestudiante')
            ->get();
            $codperiodo= $datosEstudiante[0]->codperiodo;
            $codseccion= $datosEstudiante[0]->codseccion;
            $codparalelo= $datosEstudiante[0]->codparalelo;

            $codperiodoseccionparalelo = DB::table('tperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
            ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->first()
            ->codperiodoseccionparalelo;
            $listaFases = DB::table('tfase')
            ->select('tfase.codfase','tfase.nomfase','tfase.feciniciofase','tfase.fecfinfase')
            ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
            ->distinct()->orderByRaw('tfase.nomfase ASC')
            ->get();


                    if (!is_null($listaFases)) {
                        
                        return view('estudiante/asistenciaEntreFechasEstudiante', compact('datosEstudiante','listaFases','codperiodoseccionparalelo'));
                    } else {
                        return response('El estudiante no pertenece al periodo actual', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }
    public function getFechasFase(Request $request, $codperiodoseccionparalelo, $codfase)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 

                $fechasFase = DB::table('tfase')
                ->select('tfase.codfase','tfase.nomfase','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
                ->where('tfase.codfase', '=', $codfase)
                ->distinct('tfase.codfase')
                ->get();
                return response()->json($fechasFase);
            }
        } else {
            return view('auth/login');
        }
    }

    public function getAsistenciaEstudianteEntreFecha(Request $request,  $codfase, $fechainicio, $fechafin)
    {
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();
                $codperiodo = Periodo::UltimoPeriodoPrueba();

                $datosEstudiante = DB::table('testudianteparalelo')
                ->select('testudianteparalelo.codperiodo','testudianteparalelo.codseccion','testudianteparalelo.codparalelo')              
                ->where('testudianteparalelo.codestudiante', '=', $codPersona)
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->distinct('testudianteparalelo.codestudiante')
                ->get();
                $codperiodo= $datosEstudiante[0]->codperiodo;
                $codseccion= $datosEstudiante[0]->codseccion;
                $codparalelo= $datosEstudiante[0]->codparalelo;
                $Fechas = DB::table('tfasemateria')
                ->join('tasistencia', 'tfasemateria.codmateria', '=', 'tasistencia.codmateria')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->select('tasistencia.fecha','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('tfasemateria.codfase', '=', $codfase)
                ->where('tasistencia.fecha', '>=', $fechainicio)
                ->where('tasistencia.fecha', '<=', $fechafin)
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->distinct('tasistencia.fecha')
                ->get();

                $Horas = DB::table('tmateria')
                    ->join('tasistencia', 'tmateria.codmateria', '=', 'tasistencia.codmateria')
                    ->join('thora', 'tasistencia.codhora', '=', 'thora.codhora')
                    ->join('thorahorario', 'thora.codhorahorario', '=', 'thorahorario.codhorahorario')
                    ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                    ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                    ->select('tmateria.codmateria','tmateria.nommateria','thorahorario.nomhorahorario')
                    ->where('testudiante.estestudiante', '=', 'ACTIVO')
                    ->where('tasistencia.codpersona', '=', $codPersona)
                    ->where('tasistencia.fecha', '=', $Fechas[0]->fecha)
                    ->distinct()->orderByRaw('thorahorario.nomhorahorario ASC')
                    ->get();
                //$Asistencias=[];

                $Asistencias = DB::table('tfasemateria')
                ->join('tasistencia', 'tfasemateria.codmateria', '=', 'tasistencia.codmateria')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('thora', 'tasistencia.codhora', '=', 'thora.codhora')
                ->join('thorahorario', 'thora.codhorahorario', '=', 'thorahorario.codhorahorario')
                ->join('tmateria', 'tfasemateria.codmateria', '=', 'tmateria.codmateria')
                ->select('tasistencia.codasistencia','tmateria.codmateria','tmateria.nommateria','tasistencia.estasistencia','tasistencia.fecha','thorahorario.nomhorahorario')
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('tasistencia.codpersona', '=', $codPersona)
                ->where('tfasemateria.codfase', '=', $codfase)
                ->where('tasistencia.fecha', '>=', $fechainicio)
                ->where('tasistencia.fecha', '<=', $fechafin)
                ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderByRaw('tasistencia.codasistencia ASC')
                ->get();
 

                /*foreach ($Fechas as $listafecha) {

                
                    $EstudiantesLista = DB::table('tmateria')
                    ->join('tasistencia', 'tmateria.codmateria', '=', 'tasistencia.codmateria')
                    ->join('thora', 'tasistencia.codhora', '=', 'thora.codhora')
                    ->join('thorahorario', 'thora.codhorahorario', '=', 'thorahorario.codhorahorario')
                    ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                    ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                    ->select('tmateria.codmateria','tmateria.nommateria','tasistencia.estasistencia','tasistencia.fecha','thorahorario.nomhorahorario')
                    ->where('testudiante.estestudiante', '=', 'ACTIVO')
                    ->where('tasistencia.codpersona', '=', $codPersona)
                    ->where('tasistencia.fecha', '=', $listafecha->fecha)
                    ->distinct()->orderByRaw('thorahorario.nomhorahorario ASC')
                    ->get();

                    $array = array(
                        "codmateria" => $EstudiantesLista[0]->codmateria,
                        "nommateria" => $EstudiantesLista[0]->nommateria,
                        "estasistencia" => $EstudiantesLista[0]->estasistencia,
                        "nomhorahorario" => $EstudiantesLista[0]->nomhorahorario,
                        "fecha" => $EstudiantesLista[0]->fecha,
                    );
                
                
             

                    $Asistencias[]=$EstudiantesLista;
                }*/


                $data =[
                    'Asistencias'=>$Asistencias,
                    'Fechas' => $Fechas,
                    'Horas' =>$Horas,
                ];
                //return response()->json($EstudiantesLista);
                return response()->json($data,200,[]);

                
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////////////////////////////////porcentaje de asistencia////////////////////////////////////////////////////////////////////
    public function MostrarPorcentajeAsistenciaEstudiante(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();

            $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion','testudiante.codperiodo')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tpersona.codpersona', '=', $codPersona)
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

                $Porcentajes=[];
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
                    
                    
                    $Porcentajes[]=$array;


                }


                if (!is_null($Porcentajes)) {
                    return view('estudiante/porcentajesAsistencia', compact('Porcentajes'));
                } else {
                    return response('No se encontro los porcentajes', 404);
                }
                      
            
        } else {
            return view('auth/login');
        }
    }

    ///////////////////////////////////////////////////////////////lista de horarios////////////////////////////////////////////////////////////////////
    public function ListaHorarioEstudiante(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion','testudiante.codperiodo')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tpersona.codpersona', '=', $codPersona)
                ->get();
                $codseccion =  $Persona[0]->codseccion;
                $codparalelo =  $Persona[0]->codparalelo;
                $codperiodo =  $Persona[0]->codperiodo;

            $codperiodoseccionparalelo = DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;


            $Horarios = PeriodoSeccionParalelo::ParalelosHorariosEstudiante($codperiodoseccionparalelo);
            
                    if (!is_null($Horarios)) {
                        
                        return view('estudiante/horariosEstudiante', compact('Horarios','codPersona'));
                    } else {
                        return response('El estudiante no pertenece al periodo actual', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }
    ////////////////////////////////////////////////////////
    public function horarioclases(Request $request)
    {
        if ($request->ajax()) {
            $lunes = "";
            $martes = "";
            $miercoles = "";
            $jueves = "";
            $viernes = "";
            $sabado = "";
            $domingo = "";
            $apesabado = "";
            $nomsabado = "";
            $apedomingo = "";
            $nomdomingo = "";            
            $Resumen = [];
            $horarios = Horario::ReporteHorarioClases2($request->codfase);
            if (empty($horarios->first()->nomseccion)) {
                $Resultado[] = $Resumen;
            } else {
                if ($horarios->first()->nomseccion == 'FIN DE SEMANA') {
                    foreach ($horarios as $hora) {
                        $abresabado = "";
                        $sabado = Horario::HorarioClases2($request->codfase, 'SABADO', $hora->codhorahorario);
                        $docsabado = DocenteMateria::DocenteHorario($sabado->codperiodoseccionparalelo, $sabado->codmateria);
                        if (empty($docsabado->codpersona)) {
                            $abresabado = "Profesor no asignado";
                            $apesabado = "";
                            $nomsabado = "";
                        } else {
                            $abre = "";
                            if ($docsabado->tippersona === "Administrativo") {
                                $abreviatura = Persona::AbreviaturaAdministrativo($docsabado->codpersona);
                                $abresabado = $abreviatura->abre;
                            } else {
                                $abreviatura = Persona::AbreviaturaDocente($docsabado->codpersona);
                                $abresabado = $abreviatura->abre;
                            }
                            $apesabado = $docsabado->apepersona;
                            $nomsabado = $docsabado->nompersona;
                        }

                        $domingo = Horario::HorarioClases2($request->codfase, 'DOMINGO', $hora->codhorahorario);
                        $docdomingo = DocenteMateria::DocenteHorario($domingo->codperiodoseccionparalelo, $domingo->codmateria);
                        if (empty($docdomingo->codpersona)) {
                            $abredomingo = "Profesor no asignado";
                            $apedomingo = "";
                            $nomdomingo = "";
                        } else {
                            $abre = "";
                            if ($docdomingo->tippersona === "Administrativo") {
                                $abreviatura = Persona::AbreviaturaAdministrativo($docdomingo->codpersona);
                                $abredomingo = $abreviatura->abre;
                            } else {
                                $abreviatura = Persona::AbreviaturaDocente($docdomingo->codpersona);
                                $abredomingo = $abreviatura->abre;
                            }
                            $apedomingo = $docdomingo->apepersona;
                            $nomdomingo = $docdomingo->nompersona;
                        }

                        $Resumen = array(
                            "hora" => $hora->nomhorahorario,
                            "sabado" => $sabado->nommateria . ' - ' . $abresabado . ' ' . $apesabado . ' ' . $nomsabado,
                            "domingo" => $domingo->nommateria . ' - ' .  $abredomingo . ' ' . $apedomingo . ' ' . $nomdomingo,
                            "periodo" => $horarios->first()->nomperiodo,
                            "seccion" => $horarios->first()->nomseccion,
                            "paralelo" => $horarios->first()->codparalelo,
                            "fase" => $horarios->first()->nomfase,
                            "inicio" => $horarios->first()->feciniciofase,
                            "fin" => $horarios->first()->fecfinfase,
                        );
                        $Resultado[] = $Resumen;
                    }
                } else {
                    foreach ($horarios as $hora) {
                        $lunes = Horario::HorarioClases2($request->codfase, 'LUNES', $hora->codhorahorario);
                        $doclunes = DocenteMateria::DocenteHorario($lunes->codperiodoseccionparalelo, $lunes->codmateria);
                        $abrelunes = EstudianteController::abreviatura($doclunes->codpersona, $doclunes->tippersona);

                        $martes = Horario::HorarioClases2($request->codfase, 'MARTES', $hora->codhorahorario);
                        $docmartes = DocenteMateria::DocenteHorario($martes->codperiodoseccionparalelo, $martes->codmateria);
                        $abremartes = EstudianteController::abreviatura($docmartes->codpersona, $docmartes->tippersona);

                        $miercoles = Horario::HorarioClases2($request->codfase, 'MIERCOLES', $hora->codhorahorario);
                        $docmiercoles = DocenteMateria::DocenteHorario($miercoles->codperiodoseccionparalelo, $miercoles->codmateria);
                        $abremiercoles = EstudianteController::abreviatura($docmiercoles->codpersona, $docmiercoles->tippersona);

                        $jueves = Horario::HorarioClases2($request->codfase, 'JUEVES', $hora->codhorahorario);
                        $docjueves = DocenteMateria::DocenteHorario($jueves->codperiodoseccionparalelo, $jueves->codmateria);
                        $abrejueves = EstudianteController::abreviatura($docjueves->codpersona, $docjueves->tippersona);

                        $viernes = Horario::HorarioClases2($request->codfase, 'VIERNES', $hora->codhorahorario);
                        $docviernes = DocenteMateria::DocenteHorario($viernes->codperiodoseccionparalelo, $viernes->codmateria);
                        $abreviernes = EstudianteController::abreviatura($docviernes->codpersona, $docviernes->tippersona);

                        $Resumen = array(
                            "hora" => $hora->nomhorahorario,
                            "lunes" => $lunes->nommateria . ' - ' .  $abrelunes . ' ' . $doclunes->apepersona . ' ' . $doclunes->nompersona,
                            "martes" => $martes->nommateria . ' - ' .  $abremartes . ' ' . $docmartes->apepersona . ' ' . $docmartes->nompersona,
                            "miercoles" => $miercoles->nommateria . ' - ' .  $abremiercoles . ' ' . $docmiercoles->apepersona . ' ' . $docmiercoles->nompersona,
                            "jueves" => $jueves->nommateria . ' - ' .  $abrejueves . ' ' . $docjueves->apepersona . ' ' . $docjueves->nompersona,
                            "viernes" => $viernes->nommateria . ' - ' .  $abreviernes . ' ' . $docviernes->apepersona . ' ' . $docviernes->nompersona,
                            "periodo" => $horarios->first()->nomperiodo,
                            "seccion" => $horarios->first()->nomseccion,
                            "paralelo" => $horarios->first()->codparalelo,
                            "fase" => $horarios->first()->nomfase,
                            "inicio" => $horarios->first()->feciniciofase,
                            "fin" => $horarios->first()->fecfinfase
                        );
                        $Resultado[] = $Resumen;
                    }
                }
            }
            return response()->json($Resultado);
        }
    }
    public function abreviatura($codpersona, $tippersona)
    {
        $abre = "";
        if ($tippersona === "Administrativo") {
            $abreviatura = Administrador::abreviatura($codpersona);
            $abre = $abreviatura->abreviaturaadm;
        } else {
            $abreviatura = Docente::abreviatura($codpersona);
            $abre = $abreviatura->abreviatura;
        }
        return $abre;
    }
}
