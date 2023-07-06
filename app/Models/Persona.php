<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tpersona';

    protected $primaryKey = 'codpersona';
    protected $fillable = ['codpersona', 'tippersona'];


    public function docente()
    {
        return $this->hasOne('App\Docente', 'codpersona');
    }

    public function estudiante()
    {
        return $this->hasOne('App\Estudiante', 'codpersona');
    }

    public static function AdministrativosDocentes()
    {
        return Persona::where('tippersona', '=', 'Administrativo')
            ->orwhere('tippersona', '=', 'Docente')
            ->orderby('apepersona', 'asc')->get();
    }

    public static function DatosPersona($codpersona) 
    {
        return Persona::where('codpersona', '=', $codpersona)->first();
    }

    public static function Abreviatura($codpersona)
    {
        return Persona::where('codpersona', '=', $codpersona)->first();
    }

    public static function AbreviaturaAdministrativo($codpersona)
    {
        return Persona::select('abreviaturaadm AS abre')
        ->join('tadministrativo','tadministrativo.codpersona','=','tpersona.codpersona')
        ->where('tadministrativo.codpersona', '=', $codpersona)->first();
    }

    public static function AbreviaturaDocente($codpersona)
    {
        return Persona::select('abreviatura AS abre')
        ->join('tdocente','tdocente.codpersona','=','tpersona.codpersona')
        ->where('tdocente.codpersona', '=', $codpersona)->first();
    }
}
