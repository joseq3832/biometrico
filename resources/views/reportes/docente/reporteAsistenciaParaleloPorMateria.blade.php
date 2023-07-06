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
                        <strong> PERIODO: </strong> {{$DatosEncavesado->first()->nomperiodo}}
                    </div>
                    <div class="col-xs-3">
                        <strong> SECCIÓN: </strong> {{$DatosEncavesado->first()->nomseccion}}
                    </div>
                    <div class="col-xs-3">
                        <strong> PARALELO: </strong> {{$DatosEncavesado->first()->codparalelo}}
                    </div>
</div>


<div class="container" style="font-size:0.78em;">
    <div class="col-xs-3">
        <strong> MATERIA: </strong> {{$Materia[0]->nommateria}}
    </div>
    <div class="col-xs-3">
        <strong> No. HORAS: </strong> {{$Materia[0]->numhorasmateria}}
    </div>
    <div class="col-xs-5">
    <strong> DOCENTE: </strong> {!!$abreviatura,' ',$DocenteMateria->first()->apepersona,' ',$DocenteMateria->first()->nompersona!!}    
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
                    <th class="girar"><div class="divFechasFinal2 ">  % ASISTENCIA</div></th>
                
                
            </tr>

            @foreach($EstudiantesLista as $lista)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td>&nbsp;{!!$lista->apepersona,' ',$lista->nompersona!!}</td>              
                    @foreach($Estudiantes as $estudiante)
                        @if ($lista->codpersona == $estudiante->codpersona)
                            @if ($estudiante->estasistencia == 1)
                                <td class="text-center" >{{$estudiante->estasistencia}}</td>
                            @else
                                @if ($estudiante->estasistencia == 2)
                                    <td class="text-center" >{{$estudiante->estasistencia}}</td>
                                @else
                                    @if ($estudiante->estasistencia == 3)
                                        <td class="text-center" >j</td>
                                    @else
                                        @if ($estudiante->estasistencia == 4)
                                            <td class="text-center" >A</td>
                                        @endif
                                    @endif
                                @endif
                            @endif
                            
                            
                        @endif
                    @endforeach
                        @php
                                
                            $numero=0;
                                
                        @endphp
                        @foreach($Porcentajes as $porcentaje)
                        @if ($lista->codpersona == $porcentaje['codpersona'])
                        @php
                                
                            $numero=number_format((($porcentaje['numeroasistencias']*100)/$porcentaje['numhorasmateria']),2,',', '.');
                                
                        @endphp
                        <td class="text-center" >{{$numero}}%</td>
                        @endif
                                 
                                
                                
                                
                    

                        @endforeach
                    
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
            <br> {{$ConcejoAcademico->nompedagogico}}
            <br><strong>DIRECTOR PEDAGÓGICO</strong>
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