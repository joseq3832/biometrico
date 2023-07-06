<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcejoAcademico extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tconcejodirectivo';
    protected $primaryKey = 'codconcejo';

    protected $fillable =['codconcejo'];

    public static function ConcejoAcademico(){
        $ConcejoAcademico=ConcejoAcademico::all();
        return $ConcejoAcademico->last();
    }
}
