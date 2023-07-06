<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tperiodo';
    protected $primaryKey = 'codperiodo';

    protected $fillable =['codperiodo','nomperiodo'];

    public static function UltimoPeriodo(){
        return Periodo::orderby('codperiodo','ASC');
    }
    public static function UltimoPeriodo2(){
        return Periodo::orderby('codperiodo','ASC');
    }

    public static function UltimoPeriodoPrueba(){
        $Periodos=Periodo::orderby('codperiodo','ASC')->max('codperiodo');;
        return $Periodos;
    }

    public static function listarPeriodos(){
        return Periodo::orderby('codperiodo', 'ASC')->get();    
    }
}
