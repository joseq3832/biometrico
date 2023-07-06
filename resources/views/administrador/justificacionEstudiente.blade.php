@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class="treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class=" active" )>
  @endsection

  @section('activeMenuAsistenciaParaleloRango')
<li class=" " )>
  @endsection

  @section('activeMenuAsistenciaProcentajes')
<li class="" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class="active treeview">
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
    <h2>Registro de justificación individual</h2>

    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">

                <form id="form_edit_asistencia">
                    <div class="form-group">
                        <br>
                        <div class="form-group">
                            <label>Cédula</label>
                            <input type="text" class="form-control" value="" name="cedpersona" id="cedpersona" placeholder="Cédula del alumno ">
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" value="" name="nombres" id="nombres" disabled>
                        </div>
                        <div class="form-group">
                            <label>Fecha</label>
                            <input type="date" style="width: 100%; line-height: 30px;" name="fecha" id="fecha" value="" disabled>
                        </div>
                        <div class="form-group">
                            <label>Materia</label>
                            <select class="form-control select2" style="width: 100%;" name="materias" id="materias" disabled>
                                <option value="">Escoja la materia</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Asistencia del estudiante</label>
                            <select class="form-control select2" style="width: 100%;" name="estadoAsistencia" id="estadoAsistencia" disabled>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Número de solicitud</label>
                            <input type="text" class="form-control" value="" name="numeroSolicitud" id="numeroSolicitud" placeholder='Número de solicitud ' disabled>
                        </div>
                    </div>
                    
            </div>

            </form>

        </div>

        <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">
            </table>
        </div>
        <div class="box-footer" id="box-footer">

        </div>
    </div>

    


<script type="text/javascript">
    var fecha;
    var codpersona = 0;
    var codmateria = 0;
    let cedpersona ;
    var estadoAsistencia = 0;
    var codasistencia = 0;
    let numeroSolicitud;

    $("#cedpersona").bind('keypress', function(event) {
        cedpersona = document.getElementById("cedpersona").value;
            if (event.keyCode == 13) {
                $.get('/datosDePersona/' + {{$ultimoPeriodo}} + '/' + cedpersona, function(data) {
                    var nombre = data[0].apepersona + " " + data[0].nompersona;
                    codpersona = data[0].codpersona;
                    $('#nombres').empty();
                    $("#nombres").val(nombre);


                });
                $.get('/fechas_de_control/'+{{$ultimoPeriodo}}+'/'+codpersona, function(data2) {
                document.getElementById("fecha").min = data2[0].fechaMin;
                document.getElementById("fecha").max = data2[0].fechaMax;
                document.getElementById("fecha").value = data2[0].fechaMax;
                $('#fecha').prop('disabled', false);
                $('#estadoAsistencia').prop('disabled', true);
                
            }); 
            }
    });


    $('#fecha').on('change', function(e) {
        //cedpersona = document.getElementById("cedpersona").value;
        fecha = e.target.value;
        $.get('/materiasJustificacion/' + {{$ultimoPeriodo}} + '/' + cedpersona + '/' + fecha, function(data) {
            $('#materias').empty();
            $('#materias').append("<option value=''>Escoja la materia</option>");
            for (var i = 0; i < data.length; i++) {
                $('#materias').append("<option value='" + data[i].codmateria + "'>" + data[i].nommateria + "</option>");
            }
        });
        $('#box-footer').empty();
        $('#materias').prop('disabled', false);
        $('#estadoAsistencia').prop('disabled', true);
        $('#estadoAsistencia').append("<option value=''></option>"); 
        
        $('#numeroSolicitud').prop('disabled', true);
    });

    $('#materias').on('change', function(e) {

        codmateria = e.target.value;
        $.get('/estadoDeAsistenciaAMateria/' + codpersona + '/' + codmateria + '/' + fecha, function(data) {
            codasistencia = data[0].codasistencia;
            var estado = data[0].estasistencia;
            if (estado == 1) {
                $('#estadoAsistencia').empty();
                $('#estadoAsistencia').append("<option value='1' selected='true'>Ausente</option>");
                $('#estadoAsistencia').append("<option value='2' disabled>Presente</option>");
                $('#estadoAsistencia').append("<option value='3'>Justificado</option>");
                $('#estadoAsistencia').prop('disabled', false);
            } else {
                if (estado == 2) {
                    $('#estadoAsistencia').empty();
                    $('#estadoAsistencia').append("<option value='1' disabled>Ausente</option>");
                    $('#estadoAsistencia').append("<option value='2' selected='true'>Presente</option>");
                    $('#estadoAsistencia').append("<option value='3' disabled>Justificado</option>");
                } else {
                    if (estado == 3) {
                        $('#estadoAsistencia').empty();
                        $('#estadoAsistencia').append("<option value='1' disabled>Ausente</option>");
                        $('#estadoAsistencia').append("<option value='2' disabled>Presente</option>");
                        $('#estadoAsistencia').append("<option value='3' selected='true'>Justificado</option>");
                        //$('#numeroSolicitud').empty();
                        //+data[0].numSolicitudJustificacion+"</input>")
                        $('#numeroSolicitud').val(data[0].solicitud);
                        
                    }
                }
            }
        });
    });

    ///////////////update asistencia///////////////

    $('#estadoAsistencia').on('change', function(e) {
        estadoAsistencia = e.target.value;
        $('#numeroSolicitud').prop('disabled', false);
        

    });


    $("#numeroSolicitud").bind('keypress', function(event) {
        numeroSolicitud = document.getElementById("numeroSolicitud").value;
            if (event.keyCode == 13) {
                $.get('/buscarNumeroSolicitud/'+{{$ultimoPeriodo}}+'/'+cedpersona+'/'+numeroSolicitud, function(data) {
                    
                    if(data.length > 0){
                        
                        $('#box-footer').empty();
                        $('#box-footer').append("<button type='button' class='btn btn-primary' onclick='test()'>Guardar cambios</button>");
                    }
                });
            }
    });
    function test(){
        var _token2=$("input[name=_token]").val(); 
        //var numSolicitud  = document.getElementById("numeroSolicitud").value;
    $.ajax({
    url: "{{route('postAsistenciaIndividualActualizar')}}",
    type: "POST",
    data: {
        codpersona: codpersona,
        codmateria: codmateria,
        fecha: fecha,
        estadoAsistencia: estadoAsistencia,
        numSolicitud: numeroSolicitud,
        codasistencia: codasistencia,
        _token:_token2

    },
    beforeSend: function() {
        $('#btnActualizar').text('Actualizando..');
        $('#girar2').show();
    },
    success: function(response) {
        if(response){
            /*
            $('#mesaje').empty();
            $('#mesaje').css('display', 'block');
            $('#mesaje').append("<p>Escoja la materia</p>");*/
            $('#alert').show();
                    $('#alert').html("La justificacion se registro de manera exitos");
                    setTimeout(function() {
                        $('#alert').hide(450);
                    }, 7000);   
        }
    },
    error: function(response) {
        toastr.warning('Ocurrio un error intente nuevamente', '¡FALLIDA!', {
            timeOut: 3000
        });
    }
});
} 

   
</script>


@endsection