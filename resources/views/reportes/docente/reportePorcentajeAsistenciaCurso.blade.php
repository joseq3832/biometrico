@extends('reporteMasterVertical')

@section('contenidoEncabesado')
<table>
    <tr>
        <th style="width: 75px; height: 100px; ">
            <center>
            <img src="assets/img/cLogo.png" style="width: 100px; height: 100px; ">
            </center>
        </th>
        <th style="width: 600px; height: 100px; ">
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
                    <div class="col-xs-4" >
                        <strong> PERIODO: </strong> {{$DatosEncavesado->first()->nomperiodo}}
                    </div>
                    <div class="col-xs-5">
                        <strong> SECCIÓN: </strong> {{$DatosEncavesado->first()->nomseccion}}
                    </div>
                    <div class="col-xs-2">
                        <strong> PARALELO: </strong> {{$DatosEncavesado->first()->codparalelo}}
                    </div>
</div>


<div class="container" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> MATERIA: </strong> {{$Materia[0]->nommateria}}
    </div>
    <div class="col-xs-5">
    <strong> DOCENTE: </strong> {!!$abreviatura,' ',$DocenteMateria->first()->apepersona,' ',$DocenteMateria->first()->nompersona!!}    
    </div>
    <div class="col-xs-2">
        <strong> No. HORAS: </strong> {{$Materia[0]->numhorasmateria}}
    </div>
    
</div>

<br>
<center>
<div class="box-body text-center">




        <table  class="table table-hover table-condensed table-bordered" style="font-size:0.52em; width:100%;">
            <tr >
                <th class="text-center" style="vertical-align:middle;">No.</th>
                <th class="text-center" style="vertical-align:middle;">NOMINA DE ESTUDIANTES</th>
                <th class="girar"><div class="divFechasFinal3 ">Faltas Just.</div></th>
                <th class="girar2"><div class="divFechasFinal3 ">Faltas Inj.</div></th>
                <th class="girar2"><div class="divFechasFinal3 ">Atrasos</div></th>
                <th class="girar2"><div class="divFechasFinal3 ">Horas Asistidas</div></th>
                <th class="girar2"><div class="divFechasFinal3 ">  % ASISTENCIA</div></th>
                
                
            </tr>

            @foreach($Porcentajes as $lista)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td>&nbsp;{!!$lista['apepersona'],' ',$lista['nompersona']!!}</td>
                    <td class="text-center" >{{$lista['contadorJustificado1']}}</td>  
                    <td class="text-center" >{{$lista['contadorAusente1']}}</td>  
                    <td class="text-center" >{{$lista['contadorAtrazo1']}}</td>  
                    <td class="text-center" >{{$lista['contadorPresente1']}}</td>  
                    <td class="text-center" >{{$lista['por']}}%</td>              
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