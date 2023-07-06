<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tdocente';
    protected $primaryKey = 'codpersona';

    public static function abreviatura($codpersona)
    {
        return Docente::where('codpersona', '=', $codpersona)->first();
    }

    public static function Materias($id, $codfase, $codperiodoseccionparalelo)
    {
        return Docente::select('tseccion.nomseccion', 'tseccion.codseccion', 'tperiodoseccionparalelo.codparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', 'tfasemateria.codfasemateria', 'tfase.codfase', 'tfase.nomfase','tmateria.nommateria')
            ->join('tpersona', 'tpersona.codpersona', '=', 'tdocente.codpersona')
            ->join('tdocentemateria', 'tdocentemateria.codpersona', '=', 'tpersona.codpersona')
            ->join('tfasemateria', 'tfasemateria.codmateria', '=', 'tdocentemateria.codmateria')
            ->join('tfase', 'tfase.codfase', '=', 'tfasemateria.codfase')
            ->join('tmateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
            ->join('tperiodoseccionparalelo', 'tperiodoseccionparalelo.codperiodoseccionparalelo', '=', 'tdocentemateria.codperiodoseccionparalelo')
            ->join('tperiodoseccion', 'tperiodoseccion.codperiodoseccion', '=', 'tperiodoseccionparalelo.codperiodoseccion')
            ->join('tseccion', 'tseccion.codseccion', '=', 'tperiodoseccion.codseccion')
            ->where('tdocente.codpersona', '=', $id)
            ->where('tfasemateria.codfase', '=', $codfase)
            ->where('tdocentemateria.codperiodoseccionparalelo', '=', $codperiodoseccionparalelo)
            ->first();
    }
}
