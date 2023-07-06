<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tadministrativo';
    protected $primaryKey = 'codpersona';  

    public static function abreviatura($codpersona)
    {
        return Administrador::where('codpersona', '=', $codpersona)->first();
    }
}
