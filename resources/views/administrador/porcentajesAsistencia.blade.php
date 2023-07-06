@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class=" active treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class="" )>
  @endsection

  @section('activeMenuAsistenciaParaleloRango')
<li class=" " )>
  @endsection

  @section('activeMenuAsistenciaProcentajes')
<li class="active" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class="treeview">
  @endsection

  @section('activeMenuJustificacionesIndividual')
<li class="" )>
  @endsection

  @section('activeListaJustificaciones')
<li class="" )>
  @endsection

  @section('activeMenuReportes')
<li class="treeview">
    @endsection

    @section('activeMenuReportesListadoEstudiantes')
<li class="" )>
    @endsection

    @section('activeMenuReportesHorarioClases')
<li class="" )>
    @endsection

    @section('activeMenuReportesDeAsistenciaMateria')
<li class="" )>
    @endsection

    @section('activeMenuReportesGeneralDeAsistencia')
<li class="" )>
    @endsection

    @section('content')
    <h2>Porcentajes de asistencia individual</h2>

    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">

                <form id="form_edit_asistencia">
                    <div class="form-group">
                        <br>
                        <div class="form-group">
                            <label>Cédula</label>
                            <input type="text" class="form-control" value="0604780494" name="cedpersona" id="cedpersona">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" value="" name="nombres" id="nombres" disabled>
                        </div>
                        <div class="form-group">
                            <label>Sección</label>
                            <input type="text" class="form-control" value="" name="seccion" id="seccion" disabled>
                        </div>
                        <div class="form-group">
                            <label>Paralelo</label>
                            <input type="text" class="form-control" value="" name="paralelo" id="paralelo" disabled>
                        </div>
                        
                        
            </div>
            <div class="box-footer" id="box-footer">
        </div>

            </form>

        </div>


        <div class="box-body col-md-6 offset-md-3">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table>

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

/*

        let cedpersona = document.getElementById("cedpersona").value;
            if (event.keyCode == 13) {
                $.get('/porcentajeDeAsistenciaIndividual/'+{{$ultimoPeriodo}}+'/'+cedpersona, function(data) {
                    $("#cedpersona").bind('keypress', function(event) {
        
                    
                });
            }
let cedpersona = document.getElementById("cedpersona").value;
            if (event.keyCode == 13) {
                $.get('/datosDePersona/' + {{$ultimoPeriodo}} + '/' + cedpersona, function(data) {
                    var nombre = data[0].apepersona + " " + data[0].nompersona;
                    codpersona = data[0].codpersona;
                    $('#nombres').empty();
                    $("#nombres").val(nombre);
                });
            }
    });



$('#tabledata').empty();
                    $('#tabledata').append("<thead><tr><th>MATERIA</th><th>PORCENTAJE DE ASISTENCIA</th></thead>");
                    for (var i = 0; i < data.length; i++) {
                        $('#tabledata').append("<tbody><tr><th>" + data[i].nommateria + "</th><th>" + data[i].nommateria + "</th></tbody>");
                    }
*/
    

    
function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }
   
</script>


@endsection