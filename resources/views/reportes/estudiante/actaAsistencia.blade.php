@extends('reporteMasterVertical')

@section('contenidoEncabesado')
        <center>
        <img src="assets/img/logoEncabezado.png" style="width: 30%; height: 5%;">
        </center>
@endsection

@section('contenidoReporte')
<div class="text-center" style="font-size:0.8em;">
    <strong>ACTA DE ASISTENCIA</strong>
</div>
<br>

<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> Periodo: </strong> {{$DatosEncavesado->first()->nomperiodo}}
    </div>
    <div class="col-xs-4">
        <strong> Sección: </strong> {{$DatosEncavesado->first()->nomseccion}}
    </div>
    <div class="col-xs-4">
        <strong> Paralelo: </strong> {{$DatosEncavesado->first()->codparalelo}}
    </div>   
</div>

<br>
<div class="box-body inline-block" style="font-size:0.78em;">    
    <div class="col-xs-4">
        <strong> Nombre: </strong> {!!$Persona->first()->apepersona,' ',$Persona->first()->nompersona!!}
    </div>
    <div class="col-xs-4">
        <strong> Cédula: </strong> {{$Persona->first()->cedpersona}}
    </div> 

</div>


<br><br>
<center>
<div class="box-body text-center">
        <table class="table table-condensed table-hover table-responsive table-bordered" style="font-size:0.52em; width:100%;">
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">MATERIA</th>
                <th class='text-center'>HORAS MÓDULO</th>
                <th class='text-center'>FALTAS JUST.</th>
                <th class='text-center'>FALTAS INJ.</th>
                <th class='text-center'>ATRASOS</th>
                <th class='text-center'>HORAS ASISTIDAS</th>
                <th class='text-center'>% ASISTENCIA</th>
            </tr>

            @foreach($Porcentajes as $porcentaje)
            <tr>
                <td class="text-center">{{$loop->iteration}}</td>
                <td >{{$porcentaje['nommateria']}}</td>
                <td class="text-center">{{$porcentaje['numhorasmateria']}}</td>
                <td class="text-center">{{$porcentaje['contadorJustificado1']}}</td>
                <td class="text-center">{{$porcentaje['contadorAusente1']}}</td>
                <td class="text-center">{{$porcentaje['contadorAtrazo1']}}</td>
                <td class="text-center">{{$porcentaje['contadorPresente1']}}</td>
                @php
                                
                            $porciento=number_format($porcentaje['porcentaje'],2,',','.');
                                
                @endphp

                <td class="text-center">{{$porciento}}%</td>                  
                
                
                
               
            </tr>
            @endforeach
        </table>
    </div>
    
</center>
<br><br>
<footer>
<center>
    <div class="box-body inline-block" style="font-size:0.63em; text-align: center;">
        <div >
            __________________________________
            <br> {{$ConcejoAcademico->nominspector}}
            <br><strong>INSPECTOR GENERAL </strong>
        </div>


    </div>
</center>
</footer>
@endsection