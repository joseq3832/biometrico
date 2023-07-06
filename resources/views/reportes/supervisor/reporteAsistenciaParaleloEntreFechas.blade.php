@extends('reporteMasterHorizontal')

@section('contenidoEncabesado')
<table>
    <tr>
        <th style="width: 300px; height: 100px; ">
            <center>
            <img src="assets/img/cLogo.png" style="width: 100px; height: 100px; ">
            </center>
        </th>
        <th style="width: 700px; height: 100px; ">
            <center>
                <h4>ESCUELA DE CONDUCCION</h4>
                <h5>ESPOCH “CONDUESPOCH” EP</h5>
                <h5>CONTROL DE ASISTENCIA DE LOS ALUMNOS</h5>
            </center>     
        </th>
    </tr>
</table>   
        
       
@endsection

@section('contenidoReporte')


<div class="container" style="font-size:0.78em;">
                    <div class="col-xs-3" >
                        <strong> Periodo: </strong> {{$DatosEncavesado->first()->nomperiodo}}
                    </div>
                    <div class="col-xs-3">
                        <strong> Sección: </strong> {{$DatosEncavesado->first()->nomseccion}}
                    </div>
                    <div class="col-xs-3">
                        <strong> Paralelo: </strong> {{$DatosEncavesado->first()->codparalelo}}
                    </div>
</div>


<div class="container" style="font-size:0.78em;">
    <div class="col-xs-3">
        <strong> Materia: </strong> {{$Materia[0]->nommateria}}
    </div>
    <div class="col-xs-5">
    <strong> Docente: </strong> {!!$abreviatura,' ',$DocenteMateria->first()->apepersona,' ',$DocenteMateria->first()->nompersona!!}    
    </div>
</div>

<div class="container" style="font-size:0.78em;">    
    <div class="col-xs-6">
        <p><strong>Abreviatura: </strong> A Atraso F Falta J Justificado P Presente</p>
    </div> 
</div>

<br>
<center>
<div class="box-body text-center">




        <table  class="table table-hover table-condensed table-bordered" style="font-size:0.52em; width:100%;">
            <tr >
                <th class="text-center" style="vertical-align:middle;">No.</th>
                <th class="text-center" style="vertical-align:middle;">NOMINA DE ESTUDIANTES</th>
                
                    @foreach($Fechas as $fecha)
                    <th class="girar"><div class="divFechasFinal ">{{$fecha->fecha}}</div></th>
                    @endforeach
                
                
                
            </tr>

            @foreach($EstudiantesLista as $lista)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td>&nbsp;{!!$lista->apepersona,' ',$lista->nompersona!!}</td>              
                @foreach($Estudiantes as $estudiante)
                    @if ($lista->codpersona == $estudiante->codpersona)
                        @if ($estudiante->estasistencia == 1)
                            <td class="text-center" >F</td>
                        @else
                            @if ($estudiante->estasistencia == 2)
                                <td class="text-center" >P</td>
                            @else
                                @if ($estudiante->estasistencia == 3)
                                    <td class="text-center" >J</td>
                                @else
                                    @if ($estudiante->estasistencia == 4)
                                        <td class="text-center" >A</td>
                                    @endif
                                @endif
                            @endif
                        @endif
                        
                        
                    @endif
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
    
</center>

@endsection