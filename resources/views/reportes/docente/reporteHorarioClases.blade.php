@extends('reporteMasterVertical')

@section('contenidoReporte')
<div class="text-center" style="font-size:0.8em;">
    <strong> HORARIO DE CLASES </strong>
</div>
<br>

<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> Periodo: </strong> {{$horarios->first()->nomperiodo}}
    </div>

    <div class="col-xs-4">
        <strong> Sección: </strong> {{$horarios->first()->nomseccion}}
    </div>

    <div class="col-xs-4">
        <strong> Paralelo: </strong> {{$horarios->first()->codparalelo}}
    </div>
</div>

<br>
<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> Fase: </strong> {{$horarios->first()->nomfase}}
    </div>
</div>
<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> Inicio de fase: </strong> {{$horarios->first()->feciniciofase}}
    </div>
</div>
<div class="box-body inline-block" style="font-size:0.78em;">
    <div class="col-xs-4">
        <strong> Fin de fase: </strong> {{$horarios->first()->fecfinfase}}
    </div>
</div>
<br><br>
<center>
    <div class="box-body text-center">
        <table class="table table-condensed table-hover table-responsive table-bordered" style="font-size:0.70em; width:100%;">
            @if ($horarios->first()->nomseccion == 'FIN DE SEMANA')
            <tr>
                <th class="text-center">HORA</th>
                <th class="text-center">SABADO</th>
                <th class="text-center">DOMINGO</th>
            </tr>

            @foreach($Resultado as $horario) 
            <tr>
                <td class="text-center">{{$horario['hora']}}</td>
                <td class="text-center">{{$horario['sabado']}}</td>
                <td class="text-center">{{$horario['domingo']}}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <th class="text-center">HORA</th>
                <th class="text-center">LUNES</th>
                <th class="text-center">MARTES</th>
                <th class="text-center">MIERCOLES</th>
                <th class="text-center">JUEVES</th>
                <th class="text-center">VIERNES</th>
            </tr>

            @foreach($Resultado as $horario)
            <tr>
                <td class="text-center">{{$horario['hora']}}</td>
                <td class="text-center">{{$horario['lunes']}}</td>
                <td class="text-center">{{$horario['martes']}}</td>
                <td class="text-center">{{$horario['miercoles']}}</td>
                <td class="text-center">{{$horario['jueves']}}</td>
                <td class="text-center">{{$horario['viernes']}}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
</center>

<br><br><br><br><br><br>

@endsection