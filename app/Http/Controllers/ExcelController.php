<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\PeriodoSeccion;
use App\Models\PeriodoSeccionParalelo;
use App\Models\SeccionesDePeriodo;
use App\Models\SolicitudJustificacion;
use App\Exports\pruebaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    //

    /////////////////////////////////////////////////////
    public function cuadroGeneralExcel($codperiodo, $codseccion,$codparalelo,$codperiodoseccionparalelo)
    {
        if (Auth::check()) {

            $EstudiantesLista = DB::table('tpersona')
                            ->join('testudiante', 'tpersona.codpersona', '=', 'testudiante.codpersona')
                            ->join('testudianteparalelo', 'testudiante.codpersona', '=', 'testudianteparalelo.codestudiante')
                            ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','testudiante.estestudiante')
                            ->where('testudianteparalelo.codperiodo', '=', $codperiodo)
                            ->where('testudianteparalelo.codseccion', '=', $codseccion)
                            ->where('testudianteparalelo.codparalelo', '=', $codparalelo)
                            ->distinct()->orderByRaw('tpersona.apepersona ASC , tpersona.nompersona ASC')
                            ->get();

// ->orderby('tpersona.apepersona', 'ASC')

                $Materias = DB::table('tmateria')
                ->join('tfasemateria', 'tmateria.codmateria', 'tfasemateria.codmateria')
                ->join('tfase', 'tfasemateria.codfase', '=', 'tfase.codfase')
                ->select('tmateria.codmateria','tmateria.nommateria','tmateria.numhorasmateria','tfase.codfase','tfasemateria.codfasemateria','tfase.feciniciofase','tfase.fecfinfase')
                ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
                ->distinct()->orderby('tmateria.nommateria', 'ASC')
                ->get();

                $ListaTodo=[];
                
 
                         //////////////////////////////////////////////////////////
                         /*
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
                    
*/
                




                
            ///////////////////////////////////////////////////////
            //return Excel::download();
            return Excel::download(new pruebaExport($codperiodo, $codseccion,$codparalelo,$codperiodoseccionparalelo), 'products.xlsx');
           
        } else {
            return view('auth/login');
        }
    }
    /////////////////////////////////////////////////////
}
