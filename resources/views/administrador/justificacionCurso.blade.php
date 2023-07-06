@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class="treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class=" " )>
  @endsection

  @section('activeMenuAsistenciaProcentajes')
<li class="" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class="active treeview">
  @endsection

  @section('activeMenuJustificacionesGrupal')
<li class="active" )>
  @endsection

  @section('activeMenuJustificacionesIndividual')
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

    @section('activeMenuReportesGeneralDeAsistencia')
<li class="" )>
    @endsection

    
    
    @section('content')
    <h2>Registro de justificación grupal</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

                <div class="col-xs-4">
                <div class="form-group">
                <br>    
                
                <input type="text"  name="periodo" id="periodo" value="{{$ultimoPeriodo}}" style="display:none;">
                    <br>
                    <p>Sección:</p>
                    <select class="form-control select2" style="width: 100%;" name="secciones" id="secciones">                  
                        <option value="">Escoja la sección</option>   
                        @foreach ($secciones as $secciones)
                            
                            <option value="{{ $secciones->codseccion}}">{{$secciones->nomseccion}}</option>
                        @endforeach                  
                    </select>
                    <br>
                    <p>Paralelo:</p>
                    <select class="form-control select2" style="width: 100%;" name="paralelos" id="paralelos">                   
                        <option value="">Escoja el paralelo</option>
                    </select>
                    <br>
                    <p>Fecha:</p>                  
                    <input type="date" style="width: 100%; line-height: 30px;" name="fecha" id="fecha" value="">
                    <br>
                    <br>
                    <p>Materia:</p>                  
                    <select class="form-control select2" style="width: 100%;" name="materias" id="materias">                    
                        <option value="">Escoja la materia</option>                    
                    </select>
                    <br>
                    <p>Observación:</p>                  
                    <input type="text" class="form-control" value="" name="observacion" id="observacion">
                    <br>
                    <br>
                    <a><button type="button" class="btn btn-primary" id="btnActualizar" name="btnActualizar" onclick="test()">Justificar</button></a>
                    
                </div>
            </div>    
        </div>

        <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table> 

        </div>
   
    </div>




    <script type="text/javascript">
   


//document.getElementById("fecha").value = date;
        
        var codperiodo=0;
        var codseccion=0;
        //var codparalelo="";
        var  codparalelo;
        var fecha;
        var codmateria=0;
        var codperiodoseccionparalelo=0;
        var codhorariodia=0;
        var codfase=0;
        var codhorariofase=0;
        var asistencia = 0;
        var obsevacion ;
        
        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            codperiodo = document.getElementById('periodo').value;
            $.get('/paralelos/'+codperiodo+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }
            });            
        });
        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value; 
        });

        $('#fecha').on('change', function(e) { 
            fecha = e.target.value; 
            $.get('/materias/'+codperiodo+'/'+codseccion+'/'+codparalelo+'/'+fecha, function(data) {
                codperiodoseccionparalelo=data[0].codperiodoseccionparalelo;
                codhorariodia=data[0].codhorariodia;
                codfase=data[0].codfase;
                codhorariofase = data[0].codhorariofase;
                $('#materias').empty();
                $('#materias').append("<option value=''>Escoja la materia</option>");
                for(var i=0; i<data.length; i++){
                $('#materias').append("<option value='"+data[i].codmateria+"'>"+data[i].nommateria+"</option>");   
                }
            });           
        });

        $('#materias').on('change', function(e) { 
            codmateria = e.target.value; 
           
            $.get('/asistenciaDeEstudiante/'+codperiodo+'/'+codseccion+'/'+codparalelo+'/'+codmateria+'/'+fecha, function(data) {
                var contPresentes=0;
                var contAusentes=0;
                var contJustificados=0;
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>CÉDULA</th><th>NOMBRES</th><th>ASISTENCIA</th></thead>");
                for(var i=0; i<data.length; i++){
                    var cont=i+1;
                    if (data[i].estasistencia == 1) {
                        $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].cedpersona+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>Ausente</th><tbody>");
                        contAusentes=contAusentes+1;
                    } else {
                        if (data[i].estasistencia == 2 || data[i].estasistencia == 4) {
                            $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].cedpersona+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>Presente</th><tbody>");
                            contPresentes=contPresentes+1;
                        } else {
                            if (data[i].estasistencia == 3) {
                                $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].cedpersona+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>Justificada</th><tbody>");
                                contAusentes=contAusentes+1;

                            }
                        }
                    }


                    //$('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].cedpersona+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>"+data[i].estasistencia+"</th><tbody>");     
                }

                
                
                

            });  

            
      
        });
        $("#observacion").bind('keypress', function(event) {
            obsevacion = document.getElementById("observacion").value;
           
        });
/*
        $("#observacion").bind('keypress', function(event) {
            obsevacion = document.getElementById("observacion").value;
            
            if (event.keyCode == 13) {
                $('#box-footer3').empty();
                $('#box-footer3').append("<br><a><button type='button' class='btn btn-primary' id='btnActualizar' name='btnActualizar' onclick='test()'>Justificar</button></a>");
                $('#box-footer3').append("<div id='girar2' class='lds-dual-ring col-md-12'></div> ");
            }
        });*/


        function test(){
        var _token2=$("input[name=_token]").val(); 
    $.ajax({
    url: "{{route('postAsistenciaCursoActualizar')}}",
    type: "POST",
    data: {
        codseccion: codseccion,
        codmateria: codmateria,
        codperiodo: codperiodo,
        codparalelo: codparalelo,
        fecha: fecha,
        observacion: observacion,
        _token:_token2
    },
    beforeSend: function() {
        $('#btnActualizar').text('Actualizando..');
        //$('#girar2').show();
    },
    success: function(response) {
        if(response){
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