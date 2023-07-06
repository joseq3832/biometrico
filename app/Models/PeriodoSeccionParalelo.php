<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoSeccionParalelo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tperiodoseccionparalelo';
    protected $primaryKey = 'codperiodoseccionparalelo';

    protected $fillable =['codperiodoseccionparalelo','codperiodoseccion','codparalelo'];

    public static function PeriodoSeccionParalelo($codperiodoseccion){
        return PeriodoSeccionParalelo::where('codperiodoseccion','=',$codperiodoseccion)->get();
    }

    public static function PeriodoSeccionParalelo2($codperiodoseccion, $codparalelo){
        return PeriodoSeccionParalelo::where('codperiodoseccion','=',$codperiodoseccion)
        ->where('codparalelo','=',$codparalelo)
        ->first();
    }

    public static function Paralelos($codperiodo)
    {
        return PeriodoSeccionParalelo::select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tmateria.nommateria','tfasemateria.codfasemateria','tperiodoseccionparalelo.codperiodoseccionparalelo','tseccion.codseccion','tfase.codfase','tperiodoseccion.codperiodo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tfase', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
            ->join('tfasemateria', 'tfasemateria.codfase', '=', 'tfase.codfase')
            ->join('tmateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->orderby('tperiodoseccionparalelo.codparalelo','ASC','tseccion.nomseccion','DESC')
            ->get();
    }

    public static function ParalelosHorarios($codperiodo)
    {
        return PeriodoSeccionParalelo::select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tperiodoseccionparalelo.codperiodoseccionparalelo','tseccion.codseccion','tfase.codfase')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tfase', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->distinct()->orderby('tseccion.nomseccion', 'ASC')
            
            ->get();
            //->orderby('tperiodoseccionparalelo.codparalelo','tseccion.nomseccion','DESC')
    }

    public static function ParalelosHorariosEstudiante($codperiodoseccionparalelo)
    {
        return PeriodoSeccionParalelo::select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tperiodoseccionparalelo.codperiodoseccionparalelo','tseccion.codseccion','tfase.codfase')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tfase', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
            ->where('tfase.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
            ->distinct()->orderby('tseccion.nomseccion', 'ASC')
            
            ->get();
            //->orderby('tperiodoseccionparalelo.codparalelo','tseccion.nomseccion','DESC')
    }

    public static function ParalelosGrado($codperiodo)
    {
        return PeriodoSeccionParalelo::select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tmateria.nommateria','tperiodoseccionparalelo.codperiodoseccionparalelo','tseccion.codseccion','tmateria.codmateria')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tfase', 'tfase.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
            ->join('tfasemateria', 'tfasemateria.codfase', '=', 'tfase.codfase')
            ->join('tmateria', 'tmateria.codmateria', '=', 'tfasemateria.codmateria')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->where('tmateria.gramateria', '=', 'SI')
            ->orderby('tperiodoseccionparalelo.codparalelo','tseccion.nomseccion','DESC')
            ->get();
    }

    public static function ParalelosGradoTodos($codperiodo)
    {
        return PeriodoSeccionParalelo::select('tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tperiodoseccionparalelo.codperiodoseccionparalelo','tseccion.codseccion','tperiodo.codperiodo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodo','tperiodo.codperiodo','=','tperiodoseccion.codperiodo')
            ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
            ->orderby('tperiodoseccionparalelo.codparalelo','ASC','tseccion.nomseccion','DESC')
            ->get();
    }


    public static function CodPeriodoSeccionParalelo($codperiodoseccion, $codparalelo){
        return PeriodoSeccionParalelo::where('codperiodoseccion','=',$codperiodoseccion)
        ->where('codparalelo','=',$codparalelo)->first();
    }

    public static function Paralelo($codperiodoseccionparalelo){
        return PeriodoSeccionParalelo::select('codparalelo')
        ->where('codperiodoseccionparalelo','=',$codperiodoseccionparalelo)->first();
    }

    public static function Seccion($codperiodoseccionparalelo){
        return PeriodoSeccionParalelo::select('nomseccion')
        ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
        ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
        ->where('tperiodoseccionparalelo.codperiodoseccionparalelo','=',$codperiodoseccionparalelo)->first();
    }
}
