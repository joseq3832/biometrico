@extends('estudianteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="active treeview">
  @endsection

  @section('activeAsistenciaPorFecha')
<li class="" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="" )>
  @endsection

  @section('activeAsistenciasPorModulo')
<li class="active" )>
  @endsection

  @section('activeMenuHorarios')
<li class="treeview">
  @endsection

  @section('activeListaHorario')
<li class="" )>
  @endsection

  @section('activeMenuReportes')
<li class="treeview">
    @endsection

    @section('activeActaAsistencia')
<li class="" )>
    @endsection

    @section('content')
    <h2>Porcentajes de asistencia</h2>

    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-8">

            <form id="form_edit_asistencia">
                <table id="tabledata" class="table table-hover table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class='text-center'>MATERIA</th>
                        <th class='text-center'>HORAS MÓDULO</th>
                        <th class='text-center'>FALTAS JUST.</th>
                        <th class='text-center'>FALTAS INJ.</th>
                        <th class='text-center'>ATRASOS</th>
                        <th class='text-center'>HORAS ASISTIDAS</th>
                        <th class='text-center'>% ASISTENCIA</th>
                    </tr>
                </thead>
                <tbody>
                    
                @foreach ($Porcentajes as $porcentaje)
                @php
                                
                                $porciento=number_format($porcentaje['porcentaje'],2,',','.');
                                    
                    @endphp
                    <tr>
                        <th >{{$porcentaje['nommateria']}}</th>
                        <th class='text-center'>{{$porcentaje['numhorasmateria']}}</th>
                        <th class='text-center'>{{$porcentaje['contadorJustificado1']}}</th>
                        <th class='text-center'>{{$porcentaje['contadorAusente1']}}</th>
                        <th class='text-center'>{{$porcentaje['contadorAtrazo1']}}</th>
                        <th class='text-center'>{{$porcentaje['contadorPresente1']}}</th>
                        <th class='text-center'>{{$porciento}}%</th>
                        
                    </tr>
 
                @endforeach
                </tbody>
                    
                </table>



            </form>
            </div>

        </div>


        

    </div>

    


<script type="text/javascript">
    var fecha;
    var codpersona = 0;
    var codmateria = 0;
    let cedpersona = 0;
    var estadoAsistencia = 0;
    var codasistencia = 0;
    



    $("#cedpersona").bind('keypress', function(event) {
        let cedpersona = document.getElementById("cedpersona").value;
            if (event.keyCode == 13) {
                $.get('/porcentajeDeAsistenciaIndividual/'+cedpersona, function(data) {
                    var nombre = data[0].apepersona + " " + data[0].nompersona;
                    var seccion = data[0].nomseccion;
                    var paralelo = data[0].codparalelo;                    
                    codpersona = data[0].codpersona;
                    $('#nombres').empty();
                    $("#nombres").val(nombre);
                    $('#seccion').empty();
                    $("#seccion").val(seccion);
                    $('#paralelo').empty();
                    $("#paralelo").val(paralelo);
                    $('#tabledata').empty();
                    $('#tabledata').append("<thead><tr><th class='text-center'>MATERIA</th><th class='text-center'>HORAS MÓDULO</th><th class='text-center'>FALTAS JUST.</th><th class='text-center'>FALTAS INJ.</th><th class='text-center'>ATRASOS</th><th class='text-center'>HORAS ASISTIDAS</th><th class='text-center'>% ASISTENCIA</th></thead>");
                    for (var i = 0; i < data.length; i++) {
                        var pocentaje = (data[i].porcentaje).toFixed(2);
                        $('#tabledata').append("<tbody><tr><th>" + data[i].nommateria + "</th><th >" + data[i].numhorasmateria + " </th><th >" + data[i].contadorJustificado1 + " </th><th >" + data[i].contadorAusente1 + " </th><th >" + data[i].contadorAtrazo1 + " </th><th >" + data[i].contadorPresente1 + " </th><th class='text-center'>" + pocentaje + " %</th></tbody>");                        
                    }

                    $('#box-footer').empty();
                    $('#box-footer').append("<br><a href='asistenciaPorcentajeIndividualpdf/"+codpersona+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");
                
                    
                });
            }
    

    });


    
function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }
   
</script>


@endsection