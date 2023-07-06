<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SeccionesDePeriodo;
use Illuminate\Support\Facades\DB;

class PeriodoSeccion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tperiodoseccion';
    protected $primaryKey = 'codperiodoseccion';

    protected $fillable =['codperiodoseccion','codseccion','numfases','numparalelos','codperiodo'];

    public static function TodasLasSeccinesDelPeriodo($codperiodo){

        /*return SeccionesDePeriodo::select('codperiodoseccion, codseccion, nomseccion')
        ->join('tperiodoseccion','tseccion.codperiodoseccion','=','tperiodoseccion.codperiodoseccion')
        ->where('tperiodoseccion.codperiodo', '=', $codperiodo)->first();*/
        return PeriodoSeccion::where('codperiodo','=',$codperiodo)->get();
    }


    
    public static function Paralelos($codseccion,$codperiodo){
        return PeriodoSeccion::where('codseccion','=',$codseccion)
        ->where('codperiodo','=',$codperiodo)
        ->get();
    }

    public static function PeriodoSeccion($codseccion){
        return PeriodoSeccion::where('codseccion','=',$codseccion)->get();
    }

    public static function CodPeriodoSeccion($codseccion, $codperiodo){
        return PeriodoSeccion::where('codseccion','=',$codseccion)
        ->where('codperiodo','=',$codperiodo)->first();
    }

    public static function CodPeriodoSeccionParalelo(){
        return DB::table('tperiodoseccionparalelo')
        ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
        ->select('tperiodoseccionparalelo.codperiodoseccionparalelo')
        ->where('tperiodoseccionparalelo.codparalelo', '=', 'A')
        ->where('tperiodoseccion.codperiodo', '=', 56)
        ->where('tperiodoseccion.codseccion', '=', 3)
        ->codperiodoseccionparalelo;
    }

}
