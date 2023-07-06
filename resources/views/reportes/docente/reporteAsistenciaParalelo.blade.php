@extends('reporteMasterVertical')

@section('contenidoEncabesado')
        <center>
        <img src="assets/img/logoEncabezado.png" style="width: 30%; height: 5%;">
        </center>
@endsection

@section('contenidoReporte')
<div class="text-center" style="font-size:0.8em;">
    <strong>LISTA DE ASISTENCIA</strong>
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
    <div class="col-xs-2">
        <strong> Fecha: </strong> {{$EstudiantesConAsistencia->first()->fecha}}
    </div>

    
</div>

<br>
<div class="box-body inline-block" style="font-size:0.78em;">    
    <div class="col-xs-3">
        <strong> Materia: </strong> {{$Materia->first()->nommateria}}
    </div>
    <div class="col-xs-6">
        @if (count($DocenteMateria) == 0)
        <strong> Docente: </strong>
        @else
        <strong> Docente: </strong> {!!$abreviatura,' ',$DocenteMateria->first()->apepersona,' ',$DocenteMateria->first()->nompersona!!}
        @endif
    </div>  
</div>
<br>
<div class="box-body inline-block" style="font-size:0.78em;">    
    <div class="col-xs-6">
        <p><strong>Abreviatura: </strong> A Atraso F Falta J Justificado P Presente</p>
    </div> 
</div>
<br><br>
<center>
<div class="box-body text-center">
        <table class="table table-condensed table-hover table-responsive table-bordered" style="font-size:0.52em; width:100%;">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Estudiante</th>
                <th class="text-center">Cédula</th>
                <th class="text-center">Asistencia</th>
            </tr>

            @foreach($EstudiantesConAsistencia as $estudiante)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>&nbsp;{!!$estudiante->apepersona,' ',$estudiante->nompersona!!}</td>
                <td class="text-center">{{$estudiante->cedpersona}}</td>
                @if ($estudiante->estasistencia == 1)
                    <td class="text-center text-danger" style="padding: 0.5px; "><strong>F</strong></td>
                @elseif ($estudiante->estasistencia == 2)
                    <td class="text-center text-success " style="padding: 0.5px;"><strong>P</strong></td>
                @elseif ($estudiante->estasistencia == 3)
                    <td class="text-center text-primary" style="padding: 0.5px; "><strong>J</strong></td>
                @elseif ($estudiante->estasistencia == 4 )
                    <td class="text-center text-success " style="padding: 0.5px;"><strong>A</strong></td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>
    
</center>
<br><br>
<footer>
<center>
    <div class="box-body inline-block" style="font-size:0.63em;">
        <div class="col-xs-5">
            __________________________________
            <br> {{$ConcejoAcademico->nominspector}}
            <br><strong>INSPECTOR GENERAL </strong>
        </div>

        <div class="col-xs-5">
            __________________________________
            <br> {!!$abreviatura,' ',$DocenteMateria->first()->apepersona,' ',$DocenteMateria->first()->nompersona!!}
            <br><strong>DOCENTE</strong>
        </div>
    </div>
</center>
</footer>
@endsection