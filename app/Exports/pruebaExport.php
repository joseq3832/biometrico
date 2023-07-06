<?php

namespace App\Exports;

use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class pruebaExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $codperiodo, $codseccion,$codparalelo,$codperiodoseccionparalelo;

    public function __construct(int $codperiodo,int $codseccion,String $codparalelo,int $codperiodoseccionparalelo) 
    {
        //$this->id = $id; 
        $this->codperiodo=$codperiodo; 
        $this->codseccion=$codseccion; 
        $this->codparalelo=$codparalelo; 
        $this->codperiodoseccionparalelo=$codperiodoseccionparalelo;
    }


    public function view(): View
    {
        $codperiodo=$this->codperiodo;
        $codseccion=$this->codseccion;
        $codparalelo=$this->codseccion;
        $codperiodoseccionparalelo=$this->codperiodoseccionparalelo;
/*

                $Secciones = DB::table('tseccion')
                ->join('tperiodoseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
                ->select('tseccion.codseccion','tseccion.nomseccion','tperiodoseccion.codperiodoseccion')
                ->where('tperiodoseccion.codperiodo', '=',$this->codperiodo)
                ->orderby('tseccion.nomseccion', 'ASC')
                ->get();*/
                $EstudiantesLista = DB::table('tpersona')
                    ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                    ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                    ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudiante.estestudiante')
                    ->where('testudianteparalelo.codperiodo', '=', $this->codperiodo)
                    ->where('testudianteparalelo.codseccion', '=', $this->codseccion)
                    ->where('testudianteparalelo.codparalelo', '=', $this->codparalelo)
                    ->distinct()->orderByRaw('tpersona.apepersona ASC , tpersona.nompersona ASC')
                    ->get();


                $Materias = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.codfase','tfasemateria.codfasemateria','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $this->codperiodoseccionparalelo)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();
                $numeroHoramateria1=0;
                $numeroHoramateria3=0;
                $numeroHoramateria4=0;
                $numeroHoramateria5=0;
                $numeroHoramateria6=0;
                $numeroHoramateria7=0;
                $numeroHoramateria8=0;
                $numeroHoramateria9=0;
                $numeroHoramateria10=0;
                $numeroHoramateria11=0;
                $numeroHoramateria37=0;
                $numeroHoramateria38=0;
                $numeroHoramateria58=0;
                foreach($Materias as $materia){
                    
                    if($materia->codmateria == 1){ 
                        //$numeroHoramateria1=0;
                        $numeroHoramateria1=$materia->numhorasmateria;
                    }else{
                        if($materia->codmateria == 3){ 
                            $numeroHoramateria3=$materia->numhorasmateria;
                        }else{
                            if($materia->codmateria == 4){ 
                                $numeroHoramateria4=$materia->numhorasmateria;
                            }else{
                                if($materia->codmateria == 5){ 
                                    $numeroHoramateria5=$materia->numhorasmateria;
                                }else{
                                    if($materia->codmateria == 6){ 
                                        $numeroHoramateria6=$materia->numhorasmateria;
                                    }else{
                                        if($materia->codmateria==7){ 
                                            $numeroHoramateria7=$materia->numhorasmateria;
                                        }else{
                                            if($materia->codmateria==8){ 
                                                $numeroHoramateria8=$materia->numhorasmateria;
                                            }else{
                                                if($materia->codmateria==9){ 
                                                    $numeroHoramateria9=$materia->numhorasmateria;
                                                }else{
                                                    if($materia->codmateria==10){ 
                                                        $numeroHoramateria10=$materia->numhorasmateria;
                                                    }else{
                                                        if($materia->codmateria==11){ 
                                                            $numeroHoramateria11=$materia->numhorasmateria;
                                                        }else{
                                                            if($materia->codmateria==37){ 
                                                                $numeroHoramateria37=$materia->numhorasmateria;
                                                            }else{
                                                                if($materia->codmateria==38){ 
                                                                    $numeroHoramateria38=$materia->numhorasmateria;
                                                                }else{
                                                                    if($materia->codmateria==58){ 
                                                                        $numeroHoramateria58=$materia->numhorasmateria;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                


                }
            

                $ListaTodo=[];
                foreach ($EstudiantesLista as $estudiante) {

                    
                        ///////////////////////1 ingles//////////////////////////////
                        $contadorAusente1=0;
                        $cantidadAusente1 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 1)
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
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 1)
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
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 1)
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
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 1)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo1[0]->user_count != 0){
                            $contadorAtrazo1=$cantidadAtrazo1[0]->user_count;
                        }else{
                            $contadorAtrazo1=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////3 computacion//////////////////////////////
                        $contadorAusente3=0;
                        $cantidadAusente3 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 3)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente3[0]->user_count != 0){
                            $contadorAusente3=$cantidadAusente3[0]->user_count;
                        }else{
                            $contadorAusente3=0;
                        }
                        

                        $contadorPresente3=0;
                        $cantidadPresente3 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 3)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente3[0]->user_count != 0){
                            $contadorPresente3=$cantidadPresente3[0]->user_count;
                        }else{
                            $contadorPresente3=0;
                        }

                        $contadorJustificado3=0;
                        $cantidadJustificado3 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 3)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado3[0]->user_count != 0){
                            $contadorJustificado3=$cantidadJustificado3[0]->user_count;
                        }else{
                            $contadorJustificado3=0;
                        }
                        

                        $contadorAtrazo3=0;
                        $cantidadAtrazo3 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 3)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo3[0]->user_count != 0){
                            $contadorAtrazo3=$cantidadAtrazo3[0]->user_count;
                        }else{
                            $contadorAtrazo3=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////4 primeros auxilios//////////////////////////////
                        $contadorAusente4=0;
                        $cantidadAusente4 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 4)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente4[0]->user_count != 0){
                            $contadorAusente4=$cantidadAusente4[0]->user_count;
                        }else{
                            $contadorAusente4=0;
                        }
                        

                        $contadorPresente4=0;
                        $cantidadPresente4 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 4)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente4[0]->user_count != 0){
                            $contadorPresente4=$cantidadPresente4[0]->user_count;
                        }else{
                            $contadorPresente4=0;
                        }

                        $contadorJustificado4=0;
                        $cantidadJustificado4 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 4)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado4[0]->user_count != 0){
                            $contadorJustificado4=$cantidadJustificado4[0]->user_count;
                        }else{
                            $contadorJustificado4=0;
                        }
                        

                        $contadorAtrazo4=0;
                        $cantidadAtrazo4 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 4)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo4[0]->user_count != 0){
                            $contadorAtrazo4=$cantidadAtrazo4[0]->user_count;
                        }else{
                            $contadorAtrazo4=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////5 educacion vial//////////////////////////////
                        $contadorAusente5=0;
                        $cantidadAusente5 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 5)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente5[0]->user_count != 0){
                            $contadorAusente5=$cantidadAusente5[0]->user_count;
                        }else{
                            $contadorAusente5=0;
                        }
                        

                        $contadorPresente5=0;
                        $cantidadPresente5 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 5)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente5[0]->user_count != 0){
                            $contadorPresente5=$cantidadPresente5[0]->user_count;
                        }else{
                            $contadorPresente5=0;
                        }

                        $contadorJustificado5=0;
                        $cantidadJustificado5 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 5)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado5[0]->user_count != 0){
                            $contadorJustificado5=$cantidadJustificado5[0]->user_count;
                        }else{
                            $contadorJustificado5=0;
                        }
                        

                        $contadorAtrazo5=0;
                        $cantidadAtrazo5 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 5)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo5[0]->user_count != 0){
                            $contadorAtrazo5=$cantidadAtrazo5[0]->user_count;
                        }else{
                            $contadorAtrazo5=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////6 leyes y reglamentos de tra//////////////////////////////
                        $contadorAusente6=0;
                        $cantidadAusente6 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 6)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente6[0]->user_count != 0){
                            $contadorAusente6=$cantidadAusente6[0]->user_count;
                        }else{
                            $contadorAusente6=0;
                        }
                        

                        $contadorPresente6=0;
                        $cantidadPresente6 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 6)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente6[0]->user_count != 0){
                            $contadorPresente6=$cantidadPresente6[0]->user_count;
                        }else{
                            $contadorPresente6=0;
                        }

                        $contadorJustificado6=0;
                        $cantidadJustificado6 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 6)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado6[0]->user_count != 0){
                            $contadorJustificado6=$cantidadJustificado6[0]->user_count;
                        }else{
                            $contadorJustificado6=0;
                        }
                        

                        $contadorAtrazo6=0;
                        $cantidadAtrazo6 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 6)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo6[0]->user_count != 0){
                            $contadorAtrazo6=$cantidadAtrazo6[0]->user_count;
                        }else{
                            $contadorAtrazo6=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////7 relaciones humans//////////////////////////////
                        $contadorAusente7=0;
                        $cantidadAusente7 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 7)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente7[0]->user_count != 0){
                            $contadorAusente7=$cantidadAusente7[0]->user_count;
                        }else{
                            $contadorAusente7=0;
                        }
                        

                        $contadorPresente7=0;
                        $cantidadPresente7 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 7)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente7[0]->user_count != 0){
                            $contadorPresente7=$cantidadPresente7[0]->user_count;
                        }else{
                            $contadorPresente7=0;
                        }

                        $contadorJustificado7=0;
                        $cantidadJustificado7 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 7)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado7[0]->user_count != 0){
                            $contadorJustificado7=$cantidadJustificado7[0]->user_count;
                        }else{
                            $contadorJustificado7=0;
                        }
                        

                        $contadorAtrazo7=0;
                        $cantidadAtrazo7 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 7)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo7[0]->user_count != 0){
                            $contadorAtrazo7=$cantidadAtrazo7[0]->user_count;
                        }else{
                            $contadorAtrazo7=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////8 geografia del ecudor//////////////////////////////
                        $contadorAusente8=0;
                        $cantidadAusente8 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 8)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente8[0]->user_count != 0){
                            $contadorAusente8=$cantidadAusente8[0]->user_count;
                        }else{
                            $contadorAusente8=0;
                        }
                        

                        $contadorPresente8=0;
                        $cantidadPresente8 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 8)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente8[0]->user_count != 0){
                            $contadorPresente8=$cantidadPresente8[0]->user_count;
                        }else{
                            $contadorPresente8=0;
                        }

                        $contadorJustificado8=0;
                        $cantidadJustificado8 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 8)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado8[0]->user_count != 0){
                            $contadorJustificado8=$cantidadJustificado8[0]->user_count;
                        }else{
                            $contadorJustificado8=0;
                        }
                        

                        $contadorAtrazo8=0;
                        $cantidadAtrazo8 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 8)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo8[0]->user_count != 0){
                            $contadorAtrazo8=$cantidadAtrazo8[0]->user_count;
                        }else{
                            $contadorAtrazo8=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////9 educacion ambiental//////////////////////////////
                        $contadorAusente9=0;
                        $cantidadAusente9 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 9)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente9[0]->user_count != 0){
                            $contadorAusente9=$cantidadAusente9[0]->user_count;
                        }else{
                            $contadorAusente9=0;
                        }
                        

                        $contadorPresente9=0;
                        $cantidadPresente9 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 9)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente9[0]->user_count != 0){
                            $contadorPresente9=$cantidadPresente9[0]->user_count;
                        }else{
                            $contadorPresente9=0;
                        }

                        $contadorJustificado9=0;
                        $cantidadJustificado9 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 9)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado9[0]->user_count != 0){
                            $contadorJustificado9=$cantidadJustificado9[0]->user_count;
                        }else{
                            $contadorJustificado9=0;
                        }
                        

                        $contadorAtrazo9=0;
                        $cantidadAtrazo9 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 9)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo9[0]->user_count != 0){
                            $contadorAtrazo9=$cantidadAtrazo9[0]->user_count;
                        }else{
                            $contadorAtrazo9=0;
                        }

                        //////////////////////////////////////////////////////////
                        ///////////////////////10 teoria de la conduccion//////////////////////////////
                        $contadorAusente10=0;
                        $cantidadAusente10 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 10)
                        ->where('tasistencia.estasistencia', '=', 1)
                        ->get();
                        if($cantidadAusente10[0]->user_count != 0){
                            $contadorAusente10=$cantidadAusente10[0]->user_count;
                        }else{
                            $contadorAusente10=0;
                        }
                        

                        $contadorPresente10=0;
                        $cantidadPresente10 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 10)
                        ->where('tasistencia.estasistencia', '=', 2)
                        ->get();
                        if($cantidadPresente10[0]->user_count != 0){
                            $contadorPresente10=$cantidadPresente10[0]->user_count;
                        }else{
                            $contadorPresente10=0;
                        }

                        $contadorJustificado10=0;
                        $cantidadJustificado10 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 10)
                        ->where('tasistencia.estasistencia', '=', 3)
                        ->get();
                        if($cantidadJustificado10[0]->user_count != 0){
                            $contadorJustificado10=$cantidadJustificado10[0]->user_count;
                        }else{
                            $contadorJustificado10=0;
                        }
                        

                        $contadorAtrazo10=0;
                        $cantidadAtrazo10 = DB::table('tasistencia')
                        ->select(DB::raw('count(*) as user_count'))
                        ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                        ->where('tasistencia.codmateria', '=', 10)
                        ->where('tasistencia.estasistencia', '=', 4)
                        ->get();
                        if($cantidadAtrazo10[0]->user_count != 0){
                            $contadorAtrazo10=$cantidadAtrazo10[0]->user_count;
                        }else{
                            $contadorAtrazo10=0;
                        }

                        //////////////////////////////////////////////////////////
                         ///////////////////////11 mecanica basica//////////////////////////////
                         $contadorAusente11=0;
                         $cantidadAusente11 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 11)
                         ->where('tasistencia.estasistencia', '=', 1)
                         ->get();
                         if($cantidadAusente11[0]->user_count != 0){
                             $contadorAusente11=$cantidadAusente11[0]->user_count;
                         }else{
                             $contadorAusente11=0;
                         }
                         
 
                         $contadorPresente11=0;
                         $cantidadPresente11 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 11)
                         ->where('tasistencia.estasistencia', '=', 2)
                         ->get();
                         if($cantidadPresente11[0]->user_count != 0){
                             $contadorPresente11=$cantidadPresente11[0]->user_count;
                         }else{
                             $contadorPresente11=0;
                         }
 
                         $contadorJustificado11=0;
                         $cantidadJustificado11 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 11)
                         ->where('tasistencia.estasistencia', '=', 3)
                         ->get();
                         if($cantidadJustificado11[0]->user_count != 0){
                             $contadorJustificado11=$cantidadJustificado11[0]->user_count;
                         }else{
                             $contadorJustificado11=0;
                         }
                         
 
                         $contadorAtrazo11=0;
                         $cantidadAtrazo11 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 11)
                         ->where('tasistencia.estasistencia', '=', 4)
                         ->get();
                         if($cantidadAtrazo11[0]->user_count != 0){
                             $contadorAtrazo11=$cantidadAtrazo11[0]->user_count;
                         }else{
                             $contadorAtrazo11=0;
                         }
 
                         //////////////////////////////////////////////////////////
                         ///////////////////////37 psicologia aplicada//////////////////////////////
                         $contadorAusente37=0;
                         $cantidadAusente37 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 37)
                         ->where('tasistencia.estasistencia', '=', 1)
                         ->get();
                         if($cantidadAusente37[0]->user_count != 0){
                             $contadorAusente37=$cantidadAusente37[0]->user_count;
                         }else{
                             $contadorAusente37=0;
                         }
                         
 
                         $contadorPresente37=0;
                         $cantidadPresente37 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 37)
                         ->where('tasistencia.estasistencia', '=', 2)
                         ->get();
                         if($cantidadPresente37[0]->user_count != 0){
                             $contadorPresente37=$cantidadPresente37[0]->user_count;
                         }else{
                             $contadorPresente37=0;
                         }
 
                         $contadorJustificado37=0;
                         $cantidadJustificado37 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 37)
                         ->where('tasistencia.estasistencia', '=', 3)
                         ->get();
                         if($cantidadJustificado37[0]->user_count != 0){
                             $contadorJustificado37=$cantidadJustificado37[0]->user_count;
                         }else{
                             $contadorJustificado37=0;
                         }
                         
 
                         $contadorAtrazo37=0;
                         $cantidadAtrazo37 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 37)
                         ->where('tasistencia.estasistencia', '=', 4)
                         ->get();
                         if($cantidadAtrazo37[0]->user_count != 0){
                             $contadorAtrazo37=$cantidadAtrazo37[0]->user_count;
                         }else{
                             $contadorAtrazo37=0;
                         }
 
                         //////////////////////////////////////////////////////////
                         ///////////////////////38 atencion al cliente//////////////////////////////
                         $contadorAusente38=0;
                         $cantidadAusente38 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 38)
                         ->where('tasistencia.estasistencia', '=', 1)
                         ->get();
                         if($cantidadAusente38[0]->user_count != 0){
                             $contadorAusente38=$cantidadAusente38[0]->user_count;
                         }else{
                             $contadorAusente38=0;
                         }
                         
 
                         $contadorPresente38=0;
                         $cantidadPresente38 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 38)
                         ->where('tasistencia.estasistencia', '=', 2)
                         ->get();
                         if($cantidadPresente38[0]->user_count != 0){
                             $contadorPresente38=$cantidadPresente38[0]->user_count;
                         }else{
                             $contadorPresente38=0;
                         }
 
                         $contadorJustificado38=0;
                         $cantidadJustificado38 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 38)
                         ->where('tasistencia.estasistencia', '=', 3)
                         ->get();
                         if($cantidadJustificado38[0]->user_count != 0){
                             $contadorJustificado38=$cantidadJustificado38[0]->user_count;
                         }else{
                             $contadorJustificado38=0;
                         }
                         
 
                         $contadorAtrazo38=0;
                         $cantidadAtrazo38 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 38)
                         ->where('tasistencia.estasistencia', '=', 4)
                         ->get();
                         if($cantidadAtrazo38[0]->user_count != 0){
                             $contadorAtrazo38=$cantidadAtrazo38[0]->user_count;
                         }else{
                             $contadorAtrazo38=0;
                         }
 
                         //////////////////////////////////////////////////////////
                         ///////////////////////58 practicas de conduccion//////////////////////////////
                         $contadorAusente58=0;
                         $cantidadAusente58 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 58)
                         ->where('tasistencia.estasistencia', '=', 1)
                         ->get();
                         if($cantidadAusente58[0]->user_count != 0){
                             $contadorAusente58=$cantidadAusente58[0]->user_count;
                         }else{
                             $contadorAusente58=0;
                         }
                         
 
                         $contadorPresente58=0;
                         $cantidadPresente58 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 58)
                         ->where('tasistencia.estasistencia', '=', 2)
                         ->get();
                         if($cantidadPresente58[0]->user_count != 0){
                             $contadorPresente58=$cantidadPresente58[0]->user_count;
                         }else{
                             $contadorPresente58=0;
                         }
 
                         $contadorJustificado58=0;
                         $cantidadJustificado58 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 58)
                         ->where('tasistencia.estasistencia', '=', 3)
                         ->get();
                         if($cantidadJustificado58[0]->user_count != 0){
                             $contadorJustificado58=$cantidadJustificado58[0]->user_count;
                         }else{
                             $contadorJustificado58=0;
                         }
                         
 
                         $contadorAtrazo58=0;
                         $cantidadAtrazo58 = DB::table('tasistencia')
                         ->select(DB::raw('count(*) as user_count'))
                         ->where('tasistencia.codpersona', '=', $estudiante->codpersona)
                         ->where('tasistencia.codmateria', '=', 58)
                         ->where('tasistencia.estasistencia', '=', 4)
                         ->get();
                         if($cantidadAtrazo58[0]->user_count != 0){
                             $contadorAtrazo58=$cantidadAtrazo58[0]->user_count;
                         }else{
                             $contadorAtrazo58=0;
                         }
           

                         

                         $contadorAusente5=$numeroHoramateria5-$contadorPresente5-$contadorJustificado5-$contadorAtrazo5;
                  
                         $contadorAusente6=$numeroHoramateria6-$contadorPresente6-$contadorJustificado6-$contadorAtrazo6;
                      
                         $contadorAusente11=$numeroHoramateria11-$contadorPresente11-$contadorJustificado11-$contadorAtrazo11;
                        
                         $contadorAusente37=$numeroHoramateria37-$contadorPresente37-$contadorJustificado37-$contadorAtrazo37;
                   
                         $contadorAusente3=$numeroHoramateria3-$contadorPresente3-$contadorJustificado3-$contadorAtrazo3;
                
                         $contadorAusente4=$numeroHoramateria4-$contadorPresente4-$contadorJustificado4-$contadorAtrazo4;

                         $contadorAusente9=$numeroHoramateria9-$contadorPresente9-$contadorJustificado9-$contadorAtrazo9;
                   
                         $contadorAusente10=$numeroHoramateria10-$contadorPresente10-$contadorJustificado10-$contadorAtrazo10;
                      
                         $contadorAusente58=$numeroHoramateria58-$contadorPresente58-$contadorJustificado58-$contadorAtrazo58;
           
                         $contadorAusente7=$numeroHoramateria7-$contadorPresente7-$contadorJustificado7-$contadorAtrazo7;
                   
                         $contadorAusente38=$numeroHoramateria38-$contadorPresente38-$contadorJustificado38-$contadorAtrazo38;
          
                         $contadorAusente1=$numeroHoramateria1-$contadorPresente1-$contadorJustificado1-$contadorAtrazo1;
                   
                         $contadorAusente8=$numeroHoramateria8-$contadorPresente8-$contadorJustificado8-$contadorAtrazo8;
                         
                         /*
                         $contadorAusente5=$numeroHoramateria5-$contadorPresente5-$contadorJustificado5-$contadorAtrazo5;
                         $porcentajeInacistencia5=($contadorAusente5*100)/$numeroHoramateria5;
                         $contadorAusente6=$numeroHoramateria6-$contadorPresente6-$contadorJustificado6-$contadorAtrazo6;
                         $porcentajeInacistencia6=($contadorAusente6*100)/$numeroHoramateria6;
                         $contadorAusente11=$numeroHoramateria11-$contadorPresente11-$contadorJustificado11-$contadorAtrazo11;
                         $porcentajeInacistencia11=($contadorAusente11*100)/$numeroHoramateria11;
                         $contadorAusente37=$numeroHoramateria37-$contadorPresente37-$contadorJustificado37-$contadorAtrazo37;
                         $porcentajeInacistencia37=($contadorAusente37*100)/$numeroHoramateria37;
                         $contadorAusente3=$numeroHoramateria3-$contadorPresente3-$contadorJustificado3-$contadorAtrazo3;
                         $porcentajeInacistencia3=($contadorAusente3*100)/$numeroHoramateria3;
                         $contadorAusente4=$numeroHoramateria4-$contadorPresente4-$contadorJustificado4-$contadorAtrazo4;
                         $porcentajeInacistencia4=($contadorAusente4*100)/$numeroHoramateria4;
                         $contadorAusente9=$numeroHoramateria9-$contadorPresente9-$contadorJustificado9-$contadorAtrazo9;
                         $porcentajeInacistencia9=($contadorAusente9*100)/$numeroHoramateria9;
                         $contadorAusente10=$numeroHoramateria10-$contadorPresente10-$contadorJustificado10-$contadorAtrazo10;
                         $porcentajeInacistencia10=($contadorAusente10*100)/$numeroHoramateria10;
                         $contadorAusente58=$numeroHoramateria58-$contadorPresente58-$contadorJustificado58-$contadorAtrazo58;
                         $porcentajeInacistencia58=($contadorAusente58*100)/$numeroHoramateria58;
                         $contadorAusente7=$numeroHoramateria7-$contadorPresente7-$contadorJustificado7-$contadorAtrazo7;
                         $porcentajeInacistencia7=($contadorAusente7*100)/$numeroHoramateria7;
                         $contadorAusente38=$numeroHoramateria38-$contadorPresente38-$contadorJustificado38-$contadorAtrazo38;
                         $porcentajeInacistencia38=($contadorAusente38*100)/$numeroHoramateria38;
                         $contadorAusente1=$numeroHoramateria1-$contadorPresente1-$contadorJustificado1-$contadorAtrazo1;
                         $porcentajeInacistencia1=($contadorAusente1*100)/$numeroHoramateria1;
                         $contadorAusente8=$numeroHoramateria8-$contadorPresente8-$contadorJustificado8-$contadorAtrazo8;
                         $porcentajeInacistencia8=($contadorAusente8*100)/$numeroHoramateria8;
*/

                        
                         //////////////////////////////////////////////////////////
                         
                         $array = array(
                            "codpersona" => $estudiante->codpersona,
                            "apepersona" => $estudiante->apepersona,
                            "nompersona" => $estudiante->nompersona,
                            "estado" => $estudiante->estestudiante,
                            "contadorAusente1"=>$contadorAusente1,
                            "contadorPresente1"=>$contadorPresente1,
                            "contadorJustificado1"=>$contadorJustificado1,
                            "contadorAtrazo1"=>$contadorAtrazo1,
                            "numeroHoramateria1"=>$numeroHoramateria1,
                            "contadorAusente3"=>$contadorAusente3,
                            "contadorPresente3"=>$contadorPresente3,
                            "contadorJustificado3"=>$contadorJustificado3,
                            "contadorAtrazo3"=>$contadorAtrazo3,
                            "numeroHoramateria3"=>$numeroHoramateria3,
                            "contadorAusente4"=>$contadorAusente4,
                            "contadorPresente4"=>$contadorPresente4,
                            "contadorJustificado4"=>$contadorJustificado4,
                            "contadorAtrazo4"=>$contadorAtrazo4,
                            "numeroHoramateria4"=>$numeroHoramateria4,
                            "contadorAusente5"=>$contadorAusente5,
                            "contadorPresente5"=>$contadorPresente5,
                            "contadorJustificado5"=>$contadorJustificado5,
                            "contadorAtrazo5"=>$contadorAtrazo5,
                            "numeroHoramateria5"=>$numeroHoramateria5,
                            "contadorAusente6"=>$contadorAusente6,
                            "contadorPresente6"=>$contadorPresente6,
                            "contadorJustificado6"=>$contadorJustificado6,
                            "contadorAtrazo6"=>$contadorAtrazo6,
                            "numeroHoramateria6"=>$numeroHoramateria6,
                            "contadorAusente7"=>$contadorAusente7,
                            "contadorPresente7"=>$contadorPresente7,
                            "contadorJustificado7"=>$contadorJustificado7,
                            "contadorAtrazo7"=>$contadorAtrazo7,
                            "numeroHoramateria7"=>$numeroHoramateria7,
                            "contadorAusente8"=>$contadorAusente8,
                            "contadorPresente8"=>$contadorPresente8,
                            "contadorJustificado8"=>$contadorJustificado8,
                            "contadorAtrazo8"=>$contadorAtrazo8,
                            "numeroHoramateria8"=>$numeroHoramateria8,
                            "contadorAusente9"=>$contadorAusente9,
                            "contadorPresente9"=>$contadorPresente9,
                            "contadorJustificado9"=>$contadorJustificado9,
                            "contadorAtrazo9"=>$contadorAtrazo9,
                            "numeroHoramateria9"=>$numeroHoramateria9,
                            "contadorAusente10"=>$contadorAusente10,
                            "contadorPresente10"=>$contadorPresente10,
                            "contadorJustificado10"=>$contadorJustificado10,
                            "contadorAtrazo10"=>$contadorAtrazo10,
                            "numeroHoramateria10"=>$numeroHoramateria10,
                            "contadorAusente11"=>$contadorAusente11,
                            "contadorPresente11"=>$contadorPresente11,
                            "contadorJustificado11"=>$contadorJustificado11,
                            "contadorAtrazo11"=>$contadorAtrazo11,
                            "numeroHoramateria11"=>$numeroHoramateria11,
                            "contadorAusente37"=>$contadorAusente37,
                            "contadorPresente37"=>$contadorPresente37,
                            "contadorJustificado37"=>$contadorJustificado37,
                            "contadorAtrazo37"=>$contadorAtrazo37,
                            "numeroHoramateria37"=>$numeroHoramateria37,
                            "contadorAusente38"=>$contadorAusente38,
                            "contadorPresente38"=>$contadorPresente38,
                            "contadorJustificado38"=>$contadorJustificado38,
                            "contadorAtrazo38"=>$contadorAtrazo38,
                            "numeroHoramateria38"=>$numeroHoramateria38,
                            "contadorAusente58"=>$contadorAusente58,
                            "contadorPresente58"=>$contadorPresente58,
                            "contadorJustificado58"=>$contadorJustificado58,
                            "contadorAtrazo58"=>$contadorAtrazo58,
                            "numeroHoramateria58"=>$numeroHoramateria58,
                         );
                    
                    $ListaTodo[]=$array;
                    

                }
                return view('reportesExcel.cuadrogeneral', ['ListaTodo' => $ListaTodo]);
       // return view('reportesExcel.cuadrogeneral', ['invoices' => Periodo::all()]);
    }
    public function collection()
    {
        return Periodo::all();
    }
}