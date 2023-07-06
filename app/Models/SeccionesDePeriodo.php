<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeccionesDePeriodo extends Model
{
    use HasFactory;
    public $timestamps = false;
    //protected $table = 'tseccion';
    protected $primaryKey = 'codseccion';

    protected $fillable =['codperiodoseccion','codseccion','nomseccion'];

}
