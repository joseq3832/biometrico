<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'thorariofase';
    protected $primaryKey = 'codhorariofase';

    protected $fillable =['codhorariofase'];

    public static function HorarioClases($codfase)
    {
        return Horario::select('thorahorario.codhorahorario','thorahorario.nomhorahorario','tmateria.nommateria','tseccion.nomseccion','thorariodia.coddia','thorariofase.codfase')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')
            ->join('thorahorario', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
            ->join('tmateria', 'tmateria.codmateria', '=', 'thora.codmateria')
            ->join('tperiodoseccionparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->where('thorariofase.codfase', '=', $codfase)
            ->orderby('thorahorario.codhorahorario','ASC')
            ->get();
    }

    public static function HorarioClases2($codfase,$coddia,$codhorahorario)
    {
        return Horario::select('thorahorario.codhorahorario','thorahorario.nomhorahorario','tmateria.nommateria','tseccion.nomseccion','thorariodia.coddia','thorariofase.codfase','thorariodia.coddia','thorahorario.codhorahorario','tfase.codperiodoseccionparalelo','thora.codmateria')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')
            ->join('thorahorario', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
            ->join('tmateria', 'tmateria.codmateria', '=', 'thora.codmateria')
            ->join('tperiodoseccionparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->where('thorariofase.codfase', '=', $codfase)
            ->where('thorariodia.coddia', '=', $coddia)
            ->where('thorahorario.codhorahorario', '=', $codhorahorario)
            ->orderby('thorahorario.codhorahorario','ASC')
            ->first(); 
    }

    public static function ReporteHorarioClases($codfase)
    {
        return Horario::select('thorahorario.codhorahorario','thorahorario.nomhorahorario','tmateria.nommateria','tseccion.nomseccion','thorariodia.coddia','thorariofase.codfase','tperiodoseccionparalelo.codparalelo','tperiodo.nomperiodo','tseccion.nomseccion','tfase.nomfase')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')
            ->join('thorahorario', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
            ->join('tmateria', 'tmateria.codmateria', '=', 'thora.codmateria')
            ->join('tperiodoseccionparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodo', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->where('thorariofase.codfase', '=', $codfase)
            ->get();
    }

    public static function ReporteHorarioClases2($codfase)
    {
        return Horario::select('thorahorario.nomhorahorario','thorahorario.codhorahorario','tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tfase.feciniciofase','tfase.fecfinfase')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')
            ->join('thorahorario', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')
            ->join('tmateria', 'tmateria.codmateria', '=', 'thora.codmateria')
            ->join('tperiodoseccionparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tfase.codperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->join('tperiodo', 'tperiodo.codperiodo', '=', 'tperiodoseccion.codperiodo')
            ->where('thorariofase.codfase', '=', $codfase)
            ->groupby('thorahorario.nomhorahorario','thorahorario.codhorahorario','tperiodo.nomperiodo','tseccion.nomseccion','tperiodoseccionparalelo.codparalelo','tfase.nomfase','tfase.feciniciofase','tfase.fecfinfase')
            ->distinct('thorahorario.nomhorahorario')
            ->get();
    }

    public static function Horas($codfase)
    {
        return count(Horario::select('thorahorario.nomhorahorario')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')
            ->join('thorahorario', 'thorahorario.codhorahorario', '=', 'thora.codhorahorario')            
            ->where('thorariofase.codfase', '=', $codfase)
            ->distinct()
            ->get());
    }

    public static function Dias($codfase)
    { 
        return Horario::select('thorariodia.coddia','thora.codhorariodia')
            ->join('thorariodia', 'thorariodia.codhorariofase', '=', 'thorariofase.codhorariofase')
            ->join('thora', 'thora.codhorariodia', '=', 'thorariodia.codhorariodia')
            ->join('tfase', 'tfase.codfase', '=', 'thorariofase.codfase')          
            ->where('thorariofase.codfase', '=', $codfase)
            ->distinct()
            ->orderby('thora.codhorariodia','ASC','thorariodia.coddia')
            ->get();
    }
}
