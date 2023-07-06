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

class DocenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('solodocente',['only'=>'index']);
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('docente/docentePrincipal');
    }

    public function DocenteInformacion()
    {
        if (Auth::check()) {
            $id = Auth::id();
            $informacionPersonal = Persona::join('tdocente', 'tdocente.codpersona', '=', 'tpersona.codpersona')
                ->where('tdocente.codpersona', $id)->get();

            if (!is_null($informacionPersonal)) {
                return view('docente/docenteInformacion', compact('informacionPersonal'));
            } else {
                return response('Docente no encontrado', 404);
            }
        } else {
            return view('auth/login');
        }
    }

    public function UpdateDocente(Request $request, $codpersona){
        if (Auth::check()) {
            $id = Auth::id();
            $v = Validator::make($request->all(), [
                'convencional' => 'required|string|max:10',
                'celular' => 'required|string|max:10',
                'email' => 'required|email', Rule::unique('tpersona')->ignore($id),
                'estado_civil' => 'required',
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
                    //$request->file('foto')->storeAs('public', $file_name);
                    //$request->file('foto')->move(public_path('foto',$file_name));
                    $request->file('foto')->move('storage', $file_name);
                    $informacionPersonal->huella = $file_name;
                }
                $informacionPersonal->save();

                $informacionPersonal = Docente::where('tdocente.codpersona', $id)->first();
                $informacionPersonal->estcivildocente = $request->estado_civil;
                $informacionPersonal->save();
                $message = 'Información Modificada';

                return redirect()->route('DocenteInformacion')->with('message', $message);
            }
        } else {
            return view('auth/login');
        }
    }
///////////////////////////////////////tomar lista///////////////////////////////////////////
    public function TomarListaDocente()
    {
        if (Auth::check()) {
            $codPersona = Auth::id();
            $horaActual=DB::select("SELECT to_char(current_timestamp, 'HH12:MI:SS') as fecha");
            $fechaActual=DB::select("select current_date as fecha");
            //$fecha= $fechaActual[0]->fecha;
            $fecha='2023-01-20';
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
           /* $Estudiantes = DB::table('tperiodo')
                ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
                ->join('thorarioperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'thorarioperiodoseccion.codperiodoseccion')
                ->join('thorahorario', 'thorahorario.codhorarioperiodoseccion', '=', 'thorarioperiodoseccion.codhorarioperiodoseccion')
                ->join('thora', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
                ->join('tasistencia', 'tasistencia.codhora', '=', 'thora.codhora')
                ->join('tmateria', 'tasistencia.codmateria', '=', 'tmateria.codmateria')
                ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->select('tpersona.cedpersona', 'tpersona.apepersona', 'tpersona.nompersona', 'tasistencia.estasistencia')
                ->where('tperiodo.codperiodo', '=', $ultimoPeriodo)
                ->where('thorahorario.inihorahorario', '<=', $horaActual)
                ->where('thorahorario.finhorahorario', '>=', $horaActual)
                ->where('tasistencia.fecha', '=', $fechaActual)
                ->where('tdocentemateria.codpersona', '=', 934)
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();*/

                $codMateria = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->select('tdocentemateria.codmateria')              
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->first();

                $Datos = DB::table('tperiodo')
                ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
                ->join('thorarioperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'thorarioperiodoseccion.codperiodoseccion')
                ->join('thorahorario', 'thorahorario.codhorarioperiodoseccion', '=', 'thorarioperiodoseccion.codhorarioperiodoseccion')
                ->join('thora', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
                ->join('tasistencia', 'tasistencia.codhora', '=', 'thora.codhora')
                ->join('tmateria', 'tasistencia.codmateria', '=', 'tmateria.codmateria')
                ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tseccion.nomseccion','tasistencia.fecha')
                ->where('tperiodo.codperiodo', '=', $ultimoPeriodo)
                ->where('thorahorario.inihorahorario', '<=', '15:01:00')
                ->where('thorahorario.finhorahorario', '>=', '15:01:00')
                ->where('tasistencia.fecha', '=', $fecha)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->first();
                if (!is_null($Datos)) {
                    $Estudiantes = DB::table('tperiodo')
                        ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
                        ->join('thorarioperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'thorarioperiodoseccion.codperiodoseccion')
                        ->join('thorahorario', 'thorahorario.codhorarioperiodoseccion', '=', 'thorarioperiodoseccion.codhorarioperiodoseccion')
                        ->join('thora', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
                        ->join('tasistencia', 'tasistencia.codhora', '=', 'thora.codhora')
                        ->join('tmateria', 'tasistencia.codmateria', '=', 'tmateria.codmateria')
                        ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                        ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
                        ->select('tasistencia.codasistencia','tpersona.codpersona','tpersona.cedpersona', 'tpersona.apepersona', 'tpersona.nompersona', 'tasistencia.estasistencia')
                        ->where('tperiodo.codperiodo', '=', $ultimoPeriodo)
                        ->where('thorahorario.inihorahorario', '<=', '15:01:00')
                        ->where('thorahorario.finhorahorario', '>=', '15:01:00')
                        ->where('tasistencia.fecha', '=', $fecha)
                        ->where('tdocentemateria.codpersona', '=', $codPersona)
                        ->distinct()->orderby('tpersona.apepersona', 'ASC')
                        ->get();
                    if (!is_null($Estudiantes)) {
                        
                        return view('docente/tomarLista', compact('Estudiantes','Datos','ultimoPeriodo'));
                    } else {
                        return response('No existe registro de asistencia a la clase', 404);
                    }
                      
                } else {
                   // return response('En este momento no tiene clases', 404);
                   $message = 'Notiene clases';
                   //return redirect()->route('homeDocente')->with('message', $message);
                   return redirect()->back()->withInput()->with('messagedanger', $message);
                }

           // return view('docente/tomarLista', compact('Estudiantes','Datos','ultimoPeriodo'));
        } else {
            return view('auth/login');
        }
    }

    public function postActualizarAsistenciaTomarLista(Request $request){
        if (Auth::check())
        {
            //$horaActual=DB::select("SELECT to_char(current_timestamp, 'HH12:MI:SS') as fecha");
            $horaActual='15:20:00';
            $codasistencia = $request->input('codasistencia');
            $codpersona = $request->input('codpersona');
            $estasistencia = $request->input('estasistencia');
            $fecha = $request->input('fecha');
            $codmateria = $request->input('codmateria');
            if($estasistencia == 1 || $estasistencia == 2){
                $actualizarAsistencia = DB::update('update tasistencia set estasistencia = ?, hora = ?  where codpersona = ?  and codmateria = ? and fecha = ? ',[$estasistencia,$horaActual,$codpersona,$codmateria,$fecha]);
            }else{
                $Horas = DB::table('tasistencia')
                ->select('tasistencia.codasistencia','tasistencia.codpersona','tasistencia.estasistencia')
                ->where('tasistencia.codmateria', '=', $codmateria)
                ->where('tasistencia.codpersona', '=', $codpersona)
                ->where('tasistencia.fecha', '=', $fecha)
                ->distinct('tasistencia.codasistencia')
                ->get();
                foreach($Horas as $horas){
                    if($horas->codasistencia==$codasistencia){
                        $actualizarAsistencia = DB::update('update tasistencia set estasistencia = ?, hora = ?  where codasistencia = ? and codpersona = ?  and codmateria = ? and fecha = ? ',[$estasistencia,$horaActual,$horas->codasistencia,$codpersona,$codmateria,$fecha]);
                    }else{
                        $actualizarAsistencia = DB::update('update tasistencia set estasistencia = ?, hora = ?  where codasistencia = ? and codpersona = ?  and codmateria = ? and fecha = ? ',[2,$horaActual,$horas->codasistencia,$codpersona,$codmateria,$fecha]);
                    }

                }

                


            }
            return back();
        }
        else{
            return redirect("/home");
        }
    }


/////////////////////////////porcentaje/////////////////////////
    public function PorcentajesDeAsistenciaDocente(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('docente/porcentajeAsistencias', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }

    public function getAsistenciaPorcentajeCurso(Request $request, $codperiodo, $codseccion, $codparalelo,$codmateria)
    {
        
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();

                $Materia = DB::table('tmateria')
                ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->select('tdocentemateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria')              
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->where('tdocentemateria.codmateria', '=', $codmateria)
                ->get();
                //->first();

                $Personas = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('testudiante.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();
                
                $codpersona =  $Personas[0]->codpersona;
                $codseccion =  $Personas[0]->codseccion;
                $codparalelo =  $Personas[0]->codparalelo;
                $nomseccion =  $Personas[0]->nomseccion;
                $codMateria = $Materia[0]->codmateria;
                $nomMateria = $Materia[0]->nommateria;
                $numhorasmateria = $Materia[0]->numhorasmateria;

                $x=0;
                $Porcentajes=[];
                foreach ($Personas as $persona) {

                    $contadorAusente1=0;
                        $cantidadAusente1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $Personas[$x]->codpersona)
                        ->where('tasistencia.codmateria', '=', $codMateria)
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
                        ->where('tasistencia.codpersona', '=', $Personas[$x]->codpersona)
                        ->where('tasistencia.codmateria', '=', $codMateria)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente1[0]->user_count != 0){
                            $contadorPresente1=$cantidadPresente1[0]->user_count;
                        }else{
                            $contadorPresente1=0;
                        }

                        $contadorJustificado1=0;
                        $cantidadJustificado1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $Personas[$x]->codpersona)
                        ->where('tasistencia.codmateria', '=', $codMateria)
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
                        ->where('tasistencia.codpersona', '=', $Personas[$x]->codpersona)
                        ->where('tasistencia.codmateria', '=', $codMateria)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo1[0]->user_count != 0){
                            $contadorAtrazo1=$cantidadAtrazo1[0]->user_count;
                        }else{
                            $contadorAtrazo1=0;
                        }
                    
                        $por=(($contadorPresente1+$contadorJustificado1+$contadorAtrazo1)*100)/$numhorasmateria;
                    $array = array(
                            "codpersona" => $Personas[$x]->codpersona,
                            "apepersona" => $Personas[$x]->apepersona,
                            "nompersona" => $Personas[$x]->nompersona,
                            "codparalelo" => $codparalelo,
                            "codseccion" => $codseccion,
                            "nomseccion" => $nomseccion,
                            "nommateria" => $nomMateria,
                            "numhorasmateria" => $numhorasmateria,
                            "contadorAusente1" => $contadorAusente1,
                            "contadorPresente1" => $contadorPresente1,
                            "contadorJustificado1" => $contadorJustificado1,
                            "contadorAtrazo1" => $contadorAtrazo1,
                            "por"=>$por,
                    );
                    $x++;
                    
                    $Porcentajes[]=$array;


                }


                if (!is_null($Porcentajes)) {
                    return response()->json($Porcentajes);
                } else {
                    return response('No se encontro al estudiante', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////////////////////////////////Asistencia fecha deterninada////////////////////////////////////////////////////////////////////
    public function AsistenciaFechaDeterminada(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('docente/asistenciaFechaDeterminada', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }

    public function getMateriasDeDocenteEnElPeriodo(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();
                $Materias = DB::table('tmateria')
                ->join('tdocentemateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
                ->select('tdocentemateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria')              
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();
                if (!is_null($Materias)) {
                    return response()->json($Materias);
                } else {
                    return response('No se encontro materias designadas en el periodo actual', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }

    
    public function getFechasDeMateria(Request $request, $codperiodo, $codseccion, $codparalelo, $codmateria)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();
                $fechaActual=DB::select("select current_date as fecha");
                $hoy= $fechaActual[0]->fecha;

                $codigo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;
                $Fechas = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tfase.codfase','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codigo)
                ->distinct('tfase.codfase')
                ->get();

                $fechaInicioFase=$Fechas[0]->feciniciofase;
                $fechaFinFase=$Fechas[0]->fecfinfase;
                $Fechas=[];
                if($hoy <= $fechaFinFase){
                    $array = array(
                        "fechaMin" => $fechaInicioFase,
                        "fechaMax" => $hoy,
                    );
                }else{
                    $array = array(
                        "fechaMin" => $fechaInicioFase,
                        "fechaMax" => $fechaFinFase,
                    );
                }
                $Fechas[]=$array;

                if (!is_null($Fechas)) {
                    return response()->json($Fechas);
                } else {
                    return response('No se encontro las fechas limites', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }
    
    public function getAsistenciaPorFechas(Request $request, $codperiodo, $codseccion, $codparalelo, $codmateria,$fecha)
    {
        if (Auth::check()) {
            if ($request->ajax()) { 
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
                ->where('tasistencia.fecha', '=', $fecha)
                ->distinct()->orderByRaw('tasistencia.fecha ASC , tpersona.apepersona ASC')
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
    public function AsistenciaEntreFechas(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('docente/asistenciaEntreFechas', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }

    //////////////////////////////////listar justificaciones/////////////////////////////////

    public function ListaJustificacionesDocente(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('docente/listarJustificaciones', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }

    public function getListaJustificacionesDocente(Request $request, $codperiodo, $codseccion, $codparalelo, $codmateria)
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
                ->select('tmateria.codmateria','tasistencia.fecha','tasistencia.solicitud','tpersona.cedpersona','tpersona.nompersona','tpersona.apepersona')
                ->where('tasistencia.estasistencia', '=', 3) 
                ->where('tasistencia.codmateria', '=', $codmateria)
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
    ///////////////////////////////lista de alumnos/////////////////////
    /////////////////////////////porcentaje/////////////////////////
    public function ListaDeAlumnosDocente(){
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $Secciones = DB::table('tdocentemateria')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
                ->select('tseccion.codseccion', 'tseccion.nomseccion')
                ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
                ->where('tdocentemateria.codpersona', '=', $codPersona)
                ->distinct()->orderby('tseccion.nomseccion', 'ASC')
                ->get();

                    if (!is_null($Secciones)) {
                        
                        return view('docente/listaEstudiantes', compact('Secciones','ultimoPeriodo'));
                    } else {
                        return response('El docente no pertenece al actual periodo', 404);
                    }
                      
            
        } else {
            return view('auth/login');
        }
    }

    public function getListaParalelo(Request $request, $codperiodo, $codseccion, $codparalelo)
    {
        
        
        if (Auth::check()) {
            if ($request->ajax()) { 
                $codPersona = Auth::id();



                $Personas = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tpersona.cedpersona')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('testudiante.codperiodo', '=', $codperiodo)
                ->where('testudianteparalelo.codseccion', '=', $codseccion)
                ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                ->distinct()->orderby('tpersona.apepersona', 'ASC')
                ->get();

                if (!is_null($Personas)) {
                    return response()->json($Personas);
                } else {
                    return response('No se encontro al estudiante', 404);
                }
            }
        } else {
            return view('auth/login');
        }
    }

    //////////////////////////////////horarios clase///////////////////////////////////
    public function HorarioClasesEstudiantesDocente()
    {
        if (Auth::check()) {
            $codPersona = Auth::id();
            //$periodos = Periodo::listarPeriodos();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();
            $informacionPersonal = Persona::join('tadministrativo', 'tadministrativo.codpersona', '=', 'tpersona.codpersona')
            ->where('tadministrativo.codpersona', $codPersona)->get();

            $listaFases = DB::table('tseccion')
            ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tfase', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tfasemateria', 'tfase.codfase', '=', 'tfasemateria.codfase')
            ->join('tdocentemateria', 'tfasemateria.codmateria', '=', 'tdocentemateria.codmateria')
            ->join('tmateria', 'tdocentemateria.codmateria', '=', 'tmateria.codmateria')
            ->select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tfase.codfase','tfasemateria.codmateria','tmateria.nommateria')
            ->where('tdocentemateria.codpersona', '=', $codPersona)
            ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
            ->distinct()->orderby('tfase.codfase', 'ASC')
            ->get();

           /* $listaFases = DB::table('tseccion')
            ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tdocentemateria', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tdocentemateria.codperiodoseccionparalelo')
            ->join('tfasemateria', 'tdocentemateria.codmateria', '=', 'tfasemateria.codmateria')
            ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
            ->select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tfase.codfase','tfasemateria.codmateria')
            ->where('tdocentemateria.codpersona', '=', $codPersona)
            ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
            ->distinct()->orderby('tfase.codfase', 'ASC')
            ->get();*/
            if (!is_null($informacionPersonal)) {
                return view('docente/horariosDocente', compact('informacionPersonal','listaFases','ultimoPeriodo'));
            } else {
                return response('No se encontro al estudiante', 404);
            }


            
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

    ///////////////////////////////////reposte final asistencia///////////////////////////////
    public function DocenteReporteDeAsistenciaMateria()
    {
        if (Auth::check()) {
            $codPersona = Auth::id();
            $ultimoPeriodo = Periodo::UltimoPeriodoPrueba();

            $listaFases = DB::table('tseccion')
            ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tfase', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tfasemateria', 'tfase.codfase', '=', 'tfasemateria.codfase')
            ->join('tdocentemateria', 'tfasemateria.codmateria', '=', 'tdocentemateria.codmateria')
            ->join('tmateria', 'tdocentemateria.codmateria', '=', 'tmateria.codmateria')
            ->select('tfase.codfase','tperiodoseccion.codperiodo','tseccion.codseccion','tperiodoseccionparalelo.codparalelo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfasemateria.codmateria','tmateria.nommateria')
            ->where('tdocentemateria.codpersona', '=', $codPersona)
            ->where('tperiodoseccion.codperiodo', '=', $ultimoPeriodo)
            ->distinct()->orderby('tfase.codfase', 'ASC')
            ->get();


            return view('docente/reporteFinalAsistencia', compact('listaFases'));
        } else {
            return view('auth/login');
        }
    }


}
