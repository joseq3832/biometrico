@extends('docenteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="active treeview">
  @endsection

  @section('activeTomaLista')
<li class="" )>
  @endsection

  @section('activeAsistenciaPorFecha')
<li class="" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="" )>
  @endsection

  @section('activePorcentajeAsistencia')
<li class="active" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class="treeview">
  @endsection

  @section('activeListaJustificaciones')
<li class="" )>
  @endsection

  @section('activeMenuReportes')
<li class="treeview">
    @endsection

    @section('activeListadoEstudiantes')
<li class="" )>
    @endsection

    @section('activeHorarioClases')
<li class="" )>
    @endsection

    @section('activeReporteFinalAsistencia')
<li class="" )>
    @endsection

    
    @section('content')
    <h2>Porcentajes de asistencia</h2>

    
    
        <div class="box box-primary">
            <div class="box-body">
                {{ csrf_field() }}
                <div class="col-xs-4">
                    <div class="form-group">
                        <br> 
                        <label>Sección: </label>
                        <select class="form-control select2" style="width: 100%;" name="secciones" id="secciones" >
                            <option value="">Escoja la sección</option>
                            @foreach ($Secciones as $seccion)
                            <option value="{{ $seccion->codseccion}}">{{$seccion->nomseccion}}</option>
                            @endforeach
                        </select>
                        <br> 
                        <label>Paralelo: </label>
                        <select class="form-control select2" style="width: 100%;" name="paralelos" id="paralelos" disabled>
                            <option value="">Escoja el paralelo</option>
                        </select>
                        <br> 
                        <label>Materia: </label>
                        <select class="form-control select2" style="width: 100%;" name="materias" id="materias" disabled>
                            <option value="">Escoja la materia</option>
                        </select>
                    </div>   
                </div>
                   
                
                
            </div>
            <div class="box-body" id="box-abreviatura">
            </div>
            <div class="box-body">
                <table id="tabledata" class="table table-hover table-condensed table-bordered">

                </table> 
            </div>
        <div class="box-footer" id="box-footer">
        

        </div>
        </div>



        


    




    <script type="text/javascript">
        var codseccion=0;
        var codparalelo=0;
        
        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            $.get('/paralelos/'+{{$ultimoPeriodo}}+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                    $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }
                document.getElementById("paralelos").disabled=false;
                
                $('#materias').empty();
                $('#materias').append("<option value=''>Escoja la materia</option>"); 
                $('#tabledata').empty();    
                $('#box-footer').empty();
             
            });            
        });

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value; 
            $.get('/materias_docente/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo, function(data) {
                $('#materias').empty();
                $('#materias').append("<option value=''>Escoja la materia</option>");
                for(var i=0; i<data.length; i++){
                    $('#materias').append("<option value='"+data[i].codmateria+"'>"+data[i].nommateria+"</option>");   
                }
                document.getElementById("materias").disabled=false;
                
                $('#tabledata').empty();    
                $('#box-footer').empty();
            });            
        });

        $('#materias').on('change', function(e) { 
            codmateria = e.target.value; 
            $.get('/porcentajesAsistenciaCurso/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria, function(data) {
                
                $('#box-abreviatura').empty();
                $('#box-abreviatura').append("<p><label>Número de horas del modulo: </label>"+data[0].numhorasmateria+"</p>");

                
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>NOMBRES</th><th style='text-align: center;'>Faltas Just.</th><th style='text-align: center;'>Faltas Inj.</th><th style='text-align: center;'>Atrasos</th><th style='text-align: center;'>Horas Asistidas</th><th style='text-align: center;'>%ASISTENCIA</th></thead>");
                for(var i=0; i<data.length; i++){
                    var cont=i+1;
                    var pocentaje = data[i].por.toFixed(2);
                    $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th style='text-align: center;'>"+data[i].contadorJustificado1+"</th><th style='text-align: center;'>"+data[i].contadorAusente1+"</th><th style='text-align: center;'>"+data[i].contadorAtrazo1+"</th><th style='text-align: center;'>"+data[i].contadorPresente1+"</th><th style='text-align: right;'>"+pocentaje+"%  </th><tbody>"); 
                }
                $('#box-footer').empty();
                $('#box-footer').append("<br><a href='asistenciaPorcentajeCursopdf/"+{{$ultimoPeriodo}}+"/"+codseccion+"/"+codparalelo+"/"+codmateria+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");
                
            });             
        });

        function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }
        
   


    </script>


    @endsection