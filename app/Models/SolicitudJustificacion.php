<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudJustificacion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tSolicitudJustificacion';
    protected $primaryKey = 'codSolicitudJustificacion';

    
    public static function CodigoSolicitudJustificacion($numSolicitud){
        return SolicitudJustificacion::where('numSolicitud','=',$numSolicitud)->first();
    }
}
