<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ConcejoAcademico;
use App\Models\DocenteMateria;
use App\Models\Administrador;
use App\Models\Docente;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;

class PdfController extends Controller
{
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
    public function asistenciaspdfadministrador($codperiodo, $codseccion,$codparalelo,$codmateria,$codperiodoseccionparalelo,$fecha)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();
            $Materia=DB::table('tmateria')
            ->select('tmateria.nommateria')
            ->where('tmateria.codmateria', '=', $codmateria)
            ->get();
            $EstudiantesConAsistencia = DB::table('tasistencia')
            ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
            ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia','tasistencia.fecha')
            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
            ->where('testudianteparalelo.codseccion', '=', $codseccion)
            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
            ->where('tasistencia.codmateria', '=', $codmateria)
            ->where('tasistencia.fecha', '=', $fecha)
            ->distinct()->orderby('tpersona.apepersona', 'ASC')
            ->get();
            if (sizeof($EstudiantesConAsistencia) > 0) {
                //$codmateria = $EstudiantesConAsistencia->first()->codmateria;
                $DocenteMateria = DocenteMateria::DocenteMateria($codperiodoseccionparalelo, $codmateria);
                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.administrador.reporteAsistenciaParalelo', compact('EstudiantesConAsistencia', 'ConcejoAcademico', 'DocenteMateria','abreviatura','DatosEncavesado','Materia'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    ////////////////////////////////////////////////////////
    public function asistenciasEntreFechasPdfAdmininstrador($codperiodo, $codseccion,$codparalelo,$codmateria,$fechainicio,$fechafin)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            //$codmateria = $EstudiantesConAsistencia->first()->codmateria;

            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();
           /* $Materia=DB::table('tmateria')
            ->select('tmateria.nommateria')
            ->where('tmateria.codmateria', '=', $codmateria)
            ->get();*/
            
            ////////////////////////////////////////////////////////////////////////

            $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.feciniciofase','tfase.fecfinfase')
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

                
                $FechasEncabezado=[
                    'fecha1' => $fechainicio,
                    'fecha2' => $fechafin
                ];

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
            ///////////////////////////////////////////////////////


            //$datosT = ['DatosEncavesado' => $DatosEncavesado,'Fechas' => $Fechas,'Estudiantes' => $Estudiantes,'Estudiantes' => $Estudiantes,'Porcentajes' => $Porcentajes,'ConcejoAcademico' => $ConcejoAcademico,'DocenteMateria' => $DocenteMateria,'abreviatura' => $abreviatura,'Materia' => $Materia,'FechasEncabezado' => $FechasEncabezado];
            //Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia','FechasEncabezado'
            if (sizeof($Porcentajes) > 0) {
                

                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.administrador.reporteAsistenciaParaleloEntreFechas', compact('DatosEncavesado','Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia','FechasEncabezado'));
                //$pdf=PDF::loadView('reportes.administrador.reporteAsistenciaParaleloEntreFechas',$datosT)->setPaper('a4', 'landscape');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function asistenciasPorcentajeIndividualPdfAdmininstrador($codpersona){
        if (Auth::check()) {
            $id = Auth::id();
            $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.cedpersona','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion','testudiante.codperiodo')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tpersona.codpersona', '=', $codpersona)
                ->get();
                $codpersona =  $Persona[0]->codpersona;
                $codseccion =  $Persona[0]->codseccion;
                $codparalelo =  $Persona[0]->codparalelo;
                $codperiodo =  $Persona[0]->codperiodo;

            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $Materias = DB::table('tmateria')
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
                foreach ($Materias as $materia) {
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
                    /*

                    $contador=0;
                    $porcentaje = DB::table('tasistencia')
                    ->select(DB::raw('count(*) as user_count'))
                    ->where('tasistencia.codpersona', '=', $codpersona)
                    ->where('tasistencia.codmateria', '=', $materia->codmateria)
                    ->where('tasistencia.estasistencia', '<>', 1)
                    ->get();
                    $contador=$porcentaje[0]->user_count;
                    
                    $array = array(
                            "codpersona" => $Persona[0]->codpersona,
                            "nommateria" => $materia->nommateria,
                            "numhorasmateria" => $materia->numhorasmateria,
                            "numeroasistencias" => $contador,
                    );
                    
                    
                    $Porcentajes[]=$array;*/


                }
            if (sizeof($Porcentajes) > 0) {
                
                
                $pdf = PDF::loadView('reportes.administrador.repoertePorcentajeAsistencia', compact('DatosEncavesado', 'ConcejoAcademico','DatosEncavesado','Porcentajes','Persona'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Reporte porcentajes.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Reporte porcentajes.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function formatoDeAsistenciaspdfadministrador($codperiodo, $codseccion,$codparalelo)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $EstudiantesConAsistencia = DB::table('tpersona')
            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
            ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona')
            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
            ->where('testudianteparalelo.codseccion', '=', $codseccion)
            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
            ->where('testudiante.estestudiante', '=', 'ACTIVO')
            ->distinct()->orderby('tpersona.apepersona', 'ASC')
            ->get();
            if (sizeof($EstudiantesConAsistencia) > 0) {
    
                $pdf = PDF::loadView('reportes.administrador.reporteFormatoAsistencia', compact('EstudiantesConAsistencia','DatosEncavesado'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Formato de asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Formato de asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    /////////////////////////////horario clase///////////////////////////
    public function horarioclasesAdministradorpdf($codfase){
        if (Auth::check()) {
            $lunes = "";
            $martes = "";
            $miercoles = "";
            $jueves = "";
            $viernes = "";
            $sabado = "";
            $domingo = "";
            $Resumen = [];
            $horarios = Horario::ReporteHorarioClases2($codfase);
            if (sizeof($horarios) > 0) {
                if ($horarios->first()->nomseccion == 'FIN DE SEMANA') {
                    foreach ($horarios as $hora) {
                        $sabado = Horario::HorarioClases2($codfase, 'SABADO', $hora->codhorahorario);
                        $docsabado = DocenteMateria::DocenteHorario($sabado->codperiodoseccionparalelo, $sabado->codmateria);
                        $abresabado = PdfController::abreviatura($docsabado->codpersona, $docsabado->tippersona);

                        $domingo = Horario::HorarioClases2($codfase, 'DOMINGO', $hora->codhorahorario);
                        $docdomingo = DocenteMateria::DocenteHorario($domingo->codperiodoseccionparalelo, $domingo->codmateria);
                        $abredomingo = PdfController::abreviatura($docdomingo->codpersona, $docdomingo->tippersona);

                        $Resumen = array(
                            "hora" => $hora->nomhorahorario,
                            "sabado" => $sabado->nommateria . ' - ' . $abresabado . ' ' . $docsabado->apepersona . ' ' . $docsabado->nompersona,
                            "domingo" => $domingo->nommateria . ' - ' .  $abredomingo . ' ' . $docdomingo->apepersona . ' ' . $docdomingo->nompersona,
                            "periodo" => $horarios->first()->nomperiodo,
                            "seccion" => $horarios->first()->nomseccion,
                            "paralelo" => $horarios->first()->codparalelo,
                            "fase" => $horarios->first()->nomfase,
                            "inicio" => $horarios->first()->feciniciofase,
                            "fin" => $horarios->first()->fecfinfase,
                        );
                        $Resultado[] = $Resumen;
                    }
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.administrador.reporteHorarioClases', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                } else {
                    foreach ($horarios as $hora) {
                        $lunes = Horario::HorarioClases2($codfase, 'LUNES', $hora->codhorahorario);
                        $doclunes = DocenteMateria::DocenteHorario($lunes->codperiodoseccionparalelo, $lunes->codmateria);
                        $abrelunes = PdfController::abreviatura($doclunes->codpersona, $doclunes->tippersona);

                        $martes = Horario::HorarioClases2($codfase, 'MARTES', $hora->codhorahorario);
                        $docmartes = DocenteMateria::DocenteHorario($martes->codperiodoseccionparalelo, $martes->codmateria);
                        $abremartes = PdfController::abreviatura($docmartes->codpersona, $docmartes->tippersona);

                        $miercoles = Horario::HorarioClases2($codfase, 'MIERCOLES', $hora->codhorahorario);
                        $docmiercoles = DocenteMateria::DocenteHorario($miercoles->codperiodoseccionparalelo, $miercoles->codmateria);
                        $abremiercoles = PdfController::abreviatura($docmiercoles->codpersona, $docmiercoles->tippersona);

                        $jueves = Horario::HorarioClases2($codfase, 'JUEVES', $hora->codhorahorario);
                        $docjueves = DocenteMateria::DocenteHorario($jueves->codperiodoseccionparalelo, $jueves->codmateria);
                        $abrejueves = PdfController::abreviatura($docjueves->codpersona, $docjueves->tippersona);

                        $viernes = Horario::HorarioClases2($codfase, 'VIERNES', $hora->codhorahorario);
                        $docviernes = DocenteMateria::DocenteHorario($viernes->codperiodoseccionparalelo, $viernes->codmateria);
                        $abreviernes = PdfController::abreviatura($docviernes->codpersona, $docviernes->tippersona);

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
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.administrador.reporteHorarioClases', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                }
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
    ////////////////////////////////////////////////////////
    public function asistenciasMateriaTotalPdfAdmininstrador($codperiodo, $codseccion,$codparalelo,$codmateria)
    {
        if (Auth::check()) {

            $codigo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

                $DatosMateria = DB::table('tfasemateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tfase.codfase','tfasemateria.codfasemateria','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codigo)
                ->where('tfasemateria.codmateria', '=', $codmateria)
                ->distinct('tfasemateria.codfasemateria')
                ->get();
                ///$Materia[0]->numhorasmateria
                $fechainicio = $DatosMateria[0]->feciniciofase;
                $fechafin = $DatosMateria[0]->fecfinfase;
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            //$codmateria = $EstudiantesConAsistencia->first()->codmateria;

            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();
           /* $Materia=DB::table('tmateria')
            ->select('tmateria.nommateria')
            ->where('tmateria.codmateria', '=', $codmateria)
            ->get();*/
            
            ////////////////////////////////////////////////////////////////////////

            $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.feciniciofase','tfase.fecfinfase')
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
            ///////////////////////////////////////////////////////




            if (sizeof($EstudiantesLista) > 0) {
                

                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.administrador.reporteAsistenciaParaleloPorMateria', compact('DatosEncavesado','Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Lista de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Lista de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    /////////////////////////porcentaje asistencia del curso/////////////////////
    

    public function porcentajeAsistenciaCursoPdf($codperiodo, $codseccion,$codparalelo,$codmateria)
    {
        if (Auth::check()) {

            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            //$codmateria = $EstudiantesConAsistencia->first()->codmateria;

            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();


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


    

           




            if (sizeof($Personas) > 0) {
                

                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.docente.reportePorcentajeAsistenciaCurso', compact('ConcejoAcademico','DocenteMateria','DatosEncavesado','Porcentajes','abreviatura','Materia'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de porcentaje de asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de porcentaje de asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    //////////////////////////////////////////reportes docentes//////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////reporte por dia//////////////////////////////////////////////////////////////////////////////////
    public function asistenciaspdfDocente($codperiodo, $codseccion,$codparalelo,$codmateria,$fecha)
    {
        if (Auth::check()) {
            $id = Auth::id();

            $codigo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();
            $Materia=DB::table('tmateria')
            ->select('tmateria.nommateria')
            ->where('tmateria.codmateria', '=', $codmateria)
            ->get();
            $EstudiantesConAsistencia = DB::table('tasistencia')
            ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
            ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia','tasistencia.fecha')
            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
            ->where('testudianteparalelo.codseccion', '=', $codseccion)
            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
            ->where('tasistencia.codmateria', '=', $codmateria)
            ->where('tasistencia.fecha', '=', $fecha)
            ->distinct()->orderby('tpersona.apepersona', 'ASC')
            ->get();
            if (sizeof($EstudiantesConAsistencia) > 0) {
                //$codmateria = $EstudiantesConAsistencia->first()->codmateria;
                $DocenteMateria = DocenteMateria::DocenteMateria($codigo, $codmateria);
                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.docente.reporteAsistenciaParalelo', compact('EstudiantesConAsistencia', 'ConcejoAcademico', 'DocenteMateria','abreviatura','DatosEncavesado','Materia'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    ////////////////////////////////////////////////////////
    public function asistenciasEntreFechasPdfDocente($codperiodo, $codseccion,$codparalelo,$codmateria,$fechainicio,$fechafin)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.feciniciofase','tfase.fecfinfase')
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

                $FechasEncabezado=[
                    'fecha1' => $fechainicio,
                    'fecha2' => $fechafin
                ];

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

            if (sizeof($Porcentajes) > 0) {
                

                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.docente.reporteAsistenciaParaleloEntreFechas', compact('DatosEncavesado','Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia','FechasEncabezado'));

                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
    public function listaParaleloDocentePDF($codperiodo, $codseccion,$codparalelo)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $EstudiantesConAsistencia = DB::table('tpersona')
            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
            ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona')
            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
            ->where('testudianteparalelo.codseccion', '=', $codseccion)
            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
            ->where('testudiante.estestudiante', '=', 'ACTIVO')
            ->distinct()->orderby('tpersona.apepersona', 'ASC')
            ->get();
            if (sizeof($EstudiantesConAsistencia) > 0) {
    
                $pdf = PDF::loadView('reportes.docente.listaParalelo', compact('EstudiantesConAsistencia','DatosEncavesado'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Listado de alumnos.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Listado de alumnos.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    /////////////////////////////horario clase///////////////////////////
    public function horarioclasesDocentepdf($codfase){
        if (Auth::check()) {
            $lunes = "";
            $martes = "";
            $miercoles = "";
            $jueves = "";
            $viernes = "";
            $sabado = "";
            $domingo = "";
            $Resumen = [];
            $horarios = Horario::ReporteHorarioClases2($codfase);
            if (sizeof($horarios) > 0) {
                if ($horarios->first()->nomseccion == 'FIN DE SEMANA') {
                    foreach ($horarios as $hora) {
                        $sabado = Horario::HorarioClases2($codfase, 'SABADO', $hora->codhorahorario);
                        $docsabado = DocenteMateria::DocenteHorario($sabado->codperiodoseccionparalelo, $sabado->codmateria);
                        $abresabado = PdfController::abreviatura($docsabado->codpersona, $docsabado->tippersona);

                        $domingo = Horario::HorarioClases2($codfase, 'DOMINGO', $hora->codhorahorario);
                        $docdomingo = DocenteMateria::DocenteHorario($domingo->codperiodoseccionparalelo, $domingo->codmateria);
                        $abredomingo = PdfController::abreviatura($docdomingo->codpersona, $docdomingo->tippersona);

                        $Resumen = array(
                            "hora" => $hora->nomhorahorario,
                            "sabado" => $sabado->nommateria . ' - ' . $abresabado . ' ' . $docsabado->apepersona . ' ' . $docsabado->nompersona,
                            "domingo" => $domingo->nommateria . ' - ' .  $abredomingo . ' ' . $docdomingo->apepersona . ' ' . $docdomingo->nompersona,
                            "periodo" => $horarios->first()->nomperiodo,
                            "seccion" => $horarios->first()->nomseccion,
                            "paralelo" => $horarios->first()->codparalelo,
                            "fase" => $horarios->first()->nomfase,
                            "inicio" => $horarios->first()->feciniciofase,
                            "fin" => $horarios->first()->fecfinfase,
                        );
                        $Resultado[] = $Resumen;
                    }
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.docente.reporteHorarioClases', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                } else {
                    foreach ($horarios as $hora) {
                        $lunes = Horario::HorarioClases2($codfase, 'LUNES', $hora->codhorahorario);
                        $doclunes = DocenteMateria::DocenteHorario($lunes->codperiodoseccionparalelo, $lunes->codmateria);
                        $abrelunes = PdfController::abreviatura($doclunes->codpersona, $doclunes->tippersona);

                        $martes = Horario::HorarioClases2($codfase, 'MARTES', $hora->codhorahorario);
                        $docmartes = DocenteMateria::DocenteHorario($martes->codperiodoseccionparalelo, $martes->codmateria);
                        $abremartes = PdfController::abreviatura($docmartes->codpersona, $docmartes->tippersona);

                        $miercoles = Horario::HorarioClases2($codfase, 'MIERCOLES', $hora->codhorahorario);
                        $docmiercoles = DocenteMateria::DocenteHorario($miercoles->codperiodoseccionparalelo, $miercoles->codmateria);
                        $abremiercoles = PdfController::abreviatura($docmiercoles->codpersona, $docmiercoles->tippersona);

                        $jueves = Horario::HorarioClases2($codfase, 'JUEVES', $hora->codhorahorario);
                        $docjueves = DocenteMateria::DocenteHorario($jueves->codperiodoseccionparalelo, $jueves->codmateria);
                        $abrejueves = PdfController::abreviatura($docjueves->codpersona, $docjueves->tippersona);

                        $viernes = Horario::HorarioClases2($codfase, 'VIERNES', $hora->codhorahorario);
                        $docviernes = DocenteMateria::DocenteHorario($viernes->codperiodoseccionparalelo, $viernes->codmateria);
                        $abreviernes = PdfController::abreviatura($docviernes->codpersona, $docviernes->tippersona);

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
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.docente.reporteHorarioClases', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                }
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
    ////////////////////////////////////////////////////////
    public function asistenciasMateriaTotalPdfDocente($codperiodo, $codseccion,$codparalelo,$codmateria)
    {
        if (Auth::check()) {

            $codigo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

                $DatosMateria = DB::table('tfasemateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tfase.codfase','tfasemateria.codfasemateria','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codigo)
                ->where('tfasemateria.codmateria', '=', $codmateria)
                ->distinct('tfasemateria.codfasemateria')
                ->get();
                $fechainicio = $DatosMateria[0]->feciniciofase;
                $fechafin = $DatosMateria[0]->fecfinfase;
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();

            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.feciniciofase','tfase.fecfinfase')
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

            if (sizeof($EstudiantesLista) > 0) {
                

                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.docente.reporteAsistenciaParaleloPorMateria', compact('DatosEncavesado','Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Lista de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Lista de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
     /////////////////////////////horario clase///////////////////////////
     public function horarioclasesEstudiantepdf($codfase){
        if (Auth::check()) {
            $lunes = "";
            $martes = "";
            $miercoles = "";
            $jueves = "";
            $viernes = "";
            $sabado = "";
            $domingo = "";
            $Resumen = [];
            $horarios = Horario::ReporteHorarioClases2($codfase);
            if (sizeof($horarios) > 0) {
                if ($horarios->first()->nomseccion == 'FIN DE SEMANA') {
                    foreach ($horarios as $hora) {
                        $sabado = Horario::HorarioClases2($codfase, 'SABADO', $hora->codhorahorario);
                        $docsabado = DocenteMateria::DocenteHorario($sabado->codperiodoseccionparalelo, $sabado->codmateria);
                        $abresabado = PdfController::abreviatura($docsabado->codpersona, $docsabado->tippersona);

                        $domingo = Horario::HorarioClases2($codfase, 'DOMINGO', $hora->codhorahorario);
                        $docdomingo = DocenteMateria::DocenteHorario($domingo->codperiodoseccionparalelo, $domingo->codmateria);
                        $abredomingo = PdfController::abreviatura($docdomingo->codpersona, $docdomingo->tippersona);

                        $Resumen = array(
                            "hora" => $hora->nomhorahorario,
                            "sabado" => $sabado->nommateria . ' - ' . $abresabado . ' ' . $docsabado->apepersona . ' ' . $docsabado->nompersona,
                            "domingo" => $domingo->nommateria . ' - ' .  $abredomingo . ' ' . $docdomingo->apepersona . ' ' . $docdomingo->nompersona,
                            "periodo" => $horarios->first()->nomperiodo,
                            "seccion" => $horarios->first()->nomseccion,
                            "paralelo" => $horarios->first()->codparalelo,
                            "fase" => $horarios->first()->nomfase,
                            "inicio" => $horarios->first()->feciniciofase,
                            "fin" => $horarios->first()->fecfinfase,
                        );
                        $Resultado[] = $Resumen;
                    }
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.estudiante.reporteHorarioClasesEstudiante', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                } else {
                    foreach ($horarios as $hora) {
                        $lunes = Horario::HorarioClases2($codfase, 'LUNES', $hora->codhorahorario);
                        $doclunes = DocenteMateria::DocenteHorario($lunes->codperiodoseccionparalelo, $lunes->codmateria);
                        $abrelunes = PdfController::abreviatura($doclunes->codpersona, $doclunes->tippersona);

                        $martes = Horario::HorarioClases2($codfase, 'MARTES', $hora->codhorahorario);
                        $docmartes = DocenteMateria::DocenteHorario($martes->codperiodoseccionparalelo, $martes->codmateria);
                        $abremartes = PdfController::abreviatura($docmartes->codpersona, $docmartes->tippersona);

                        $miercoles = Horario::HorarioClases2($codfase, 'MIERCOLES', $hora->codhorahorario);
                        $docmiercoles = DocenteMateria::DocenteHorario($miercoles->codperiodoseccionparalelo, $miercoles->codmateria);
                        $abremiercoles = PdfController::abreviatura($docmiercoles->codpersona, $docmiercoles->tippersona);

                        $jueves = Horario::HorarioClases2($codfase, 'JUEVES', $hora->codhorahorario);
                        $docjueves = DocenteMateria::DocenteHorario($jueves->codperiodoseccionparalelo, $jueves->codmateria);
                        $abrejueves = PdfController::abreviatura($docjueves->codpersona, $docjueves->tippersona);

                        $viernes = Horario::HorarioClases2($codfase, 'VIERNES', $hora->codhorahorario);
                        $docviernes = DocenteMateria::DocenteHorario($viernes->codperiodoseccionparalelo, $viernes->codmateria);
                        $abreviernes = PdfController::abreviatura($docviernes->codpersona, $docviernes->tippersona);

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
                    $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
                    $pdf = PDF::loadView('reportes.estudiante.reporteHorarioClasesEstudiante', compact('horarios', 'Resultado', 'ConcejoAcademico'));
                }
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Horario de Clases.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    ////////////////////////////////////////////Acta asistencia estudiante/////////////////////////////////////////////////////////////////////////////////////
    public function ActaAsistenciaEstudiante(){
        if (Auth::check()) {
            $codpersona = Auth::id();
            $Persona = DB::table('tpersona')
                ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                ->join('tseccion', 'testudianteparalelo.codseccion', '=', 'tseccion.codseccion')
                ->select('tpersona.cedpersona','tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudianteparalelo.codparalelo','tseccion.codseccion','tseccion.nomseccion','testudiante.codperiodo')
                ->where('testudiante.estestudiante', '=', 'ACTIVO')
                ->where('tpersona.codpersona', '=', $codpersona)
                ->get();
                $codpersona =  $Persona[0]->codpersona;
                $codseccion =  $Persona[0]->codseccion;
                $codparalelo =  $Persona[0]->codparalelo;
                $codperiodo =  $Persona[0]->codperiodo;

            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $Materias = DB::table('tmateria')
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
                foreach ($Materias as $materia) {
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
            if (sizeof($Porcentajes) > 0) {
                
                
                $pdf = PDF::loadView('reportes.estudiante.actaAsistencia', compact('DatosEncavesado', 'ConcejoAcademico','DatosEncavesado','Porcentajes','Persona'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Acta de asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Acta de asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
    ///////////////////////////////////reporte por dia//////////////////////////////////////////////////////////////////////////////////
    public function asistenciaPorDiaSupervisorpdf($codperiodo, $codseccion,$codparalelo,$codmateria,$fecha)
    {
        if (Auth::check()) {
            $id = Auth::id();

            $codigo =  DB::table('tperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->first()
                ->codperiodoseccionparalelo;

            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();
            $Materia=DB::table('tmateria')
            ->select('tmateria.nommateria')
            ->where('tmateria.codmateria', '=', $codmateria)
            ->get();
            $EstudiantesConAsistencia = DB::table('tasistencia')
            ->join('tpersona', 'tasistencia.codpersona', '=', 'tpersona.codpersona')
            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
            ->select('tpersona.codpersona','tpersona.cedpersona','tpersona.apepersona','tpersona.nompersona','tasistencia.estasistencia','tasistencia.fecha')
            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
            ->where('testudianteparalelo.codseccion', '=', $codseccion)
            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
            ->where('tasistencia.codmateria', '=', $codmateria)
            ->where('tasistencia.fecha', '=', $fecha)
            ->distinct()->orderby('tpersona.apepersona', 'ASC')
            ->get();
            if (sizeof($EstudiantesConAsistencia) > 0) {
                //$codmateria = $EstudiantesConAsistencia->first()->codmateria;
                $DocenteMateria = DocenteMateria::DocenteMateria($codigo, $codmateria);
                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.supervisor.reporteAsistenciaPorDia', compact('EstudiantesConAsistencia', 'ConcejoAcademico', 'DocenteMateria','abreviatura','DatosEncavesado','Materia'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'portrait');
                return $pdf->download('Lista de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }

    ////////////////////////////////////////////////////////
    public function asistenciasEntreFechasPdfSupervisor($codperiodo, $codseccion,$codparalelo,$codmateria,$fechainicio,$fechafin)
    {
        if (Auth::check()) {
            $id = Auth::id();
            $EstudiantesConAsistencia = "";
            $ConcejoAcademico = ConcejoAcademico::ConcejoAcademico();
            $DocenteMateria = DocenteMateria::DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria);

            $DatosEncavesado = DB::table('tperiodo')
            ->join('tperiodoseccion', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->join('tseccion', 'tperiodoseccion.codseccion', '=', 'tseccion.codseccion')
            ->join('tperiodoseccionparalelo', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->select('tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tperiodoseccion.codseccion', '=', $codseccion)
            ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
            ->get();

            $Materia = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->join('tperiodoseccionparalelo', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.feciniciofase','tfase.fecfinfase')
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
                
                $FechasEncabezado=[
                    'fecha1' => $fechainicio,
                    'fecha2' => $fechafin
                ];

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

            if (sizeof($Porcentajes) > 0) {
                $abreviatura = strtoupper(PdfController::abreviatura($DocenteMateria->first()->codpersona, $DocenteMateria->first()->tippersona));
                $pdf = PDF::loadView('reportes.supervisor.reporteAsistenciaParaleloEntreFechas', compact('DatosEncavesado','Fechas','EstudiantesLista','Estudiantes', 'Porcentajes','ConcejoAcademico', 'DocenteMateria','abreviatura','Materia','FechasEncabezado'));
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            } else {
                $pdf = PDF::loadView('reportes.reporteVacio');
                $pdf->setPaper('a4', 'landscape');
                return $pdf->download('Reporte de Asistencia.pdf');
            }
        } else {
            return view('auth/login');
        }
    }
}
