@extends('reporteMasterVertical')

@section('contenidoEncabesado')
        <center>
        <img src="assets/img/logoEncabezado.png" style="width: 30%; height: 5%;">
        </center>
@endsection

@section('contenidoReporte')
<div class="text-center" style="font-size:0.8em;">
    <strong>LISTA DE ALUMNOS</strong>
</div>
<br>

<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-3">
        <strong> Periodo: </strong> {{$DatosEncavesado->first()->nomperiodo}}
    </div>
    <div class="col-xs-3">
        <strong> Sección: </strong> {{$DatosEncavesado->first()->nomseccion}}
    </div>
    <div class="col-xs-2">
        <strong> Paralelo: </strong> {{$DatosEncavesado->first()->codparalelo}}
    </div>
    

    
</div>

<br>

<br><br>
<center>
<div class="box-body text-center">
        <table class="table table-condensed table-hover table-responsive table-bordered" style="font-size:0.52em; width:100%;">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Estudiante</th>
                <th class="text-center">Cédula</th>
                
            </tr>

            @foreach($EstudiantesConAsistencia as $estudiante)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>&nbsp;{!!$estudiante->apepersona,' ',$estudiante->nompersona!!}</td>
                <td class="text-center">{{$estudiante->cedpersona}}</td>
                
                
                
            </tr>
            @endforeach
        </table>
    </div>
    
</center>
<br><br>

@endsection