<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocenteMateria extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tdocentemateria';
    protected $primaryKey = 'coddocentemateria';

    
    public static function DocenteMateria($codperiodoseccionparalelo, $codmateria){
        return DocenteMateria::join('tpersona', 'tpersona.codpersona', '=', 'tdocentemateria.codpersona')
        ->where('tdocentemateria.codperiodoseccionparalelo','=',$codperiodoseccionparalelo)
        ->where('tdocentemateria.codmateria','=',$codmateria)
        ->get();
    }
    public static function DocenteDeMateria($codperiodo, $codseccion, $codparalelo , $codmateria){
        return  DB::table('tpersona')
                ->join('tdocentemateria', 'tpersona.codpersona', '=', 'tdocentemateria.codpersona')
                ->join('tperiodoseccionparalelo', 'tdocentemateria.codperiodoseccionparalelo', '=', 'tperiodoseccionparalelo.codperiodoseccionparalelo')
                ->join('tperiodoseccion', 'tperiodoseccionparalelo.codperiodoseccion', '=', 'tperiodoseccion.codperiodoseccion')
                ->select('tpersona.codpersona','tpersona.apepersona','tpersona.nompersona','tpersona.tippersona')
                ->where('tperiodoseccion.codperiodo', '=', $codperiodo)
                ->where('tperiodoseccion.codseccion', '=', $codseccion)
                ->where('tperiodoseccionparalelo.codparalelo', '=', $codparalelo)
                ->get();

        
    }
    public static function DocenteHorario($codperiodoseccionparalelo, $codmateria){
        return DocenteMateria::select('tpersona.apepersona','tpersona.nompersona','tpersona.codpersona','tpersona.tippersona')
        ->join('tpersona', 'tpersona.codpersona', '=', 'tdocentemateria.codpersona')
        ->join('tfase', 'tfase.codperiodoseccionparalelo', '=', 'tdocentemateria.codperiodoseccionparalelo')
        ->join('tmateria', 'tmateria.codmateria', '=', 'tdocentemateria.codmateria')
        ->where('tfase.codperiodoseccionparalelo','=',$codperiodoseccionparalelo)
        ->where('tmateria.codmateria','=',$codmateria)
        ->first();
    }
}
