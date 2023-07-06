@extends('docenteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="treeview">
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
<li class="" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class=" active treeview">
  @endsection

  @section('activeListaJustificaciones')
<li class="active" )>
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
    <h2>Lista de justificaciones</h2>

    
    
        <div class="box box-primary">
            <div class="box-body">
                {{ csrf_field() }}
                <div class="col-xs-4">
                    <div class="form-group">
                        <br> 
                        <label>Sección: </label>
                        <select class="form-control select2" style="width: 100%;" name="secciones" id="secciones">
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
            <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table> 

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
                document.getElementById("materias").disabled=true;
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
            $.get('/lista_jusatificaciones/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria, function(data) {
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>FECHA</th><th>NOMBRES</th><th style='text-align: center;'>CÉDULA</th><th style='text-align: center;'>Nº SOLICITUD</th></thead>");
                for(var i=0; i<data.length; i++){
                    var cont=i+1;

                    $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].fecha+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th style='text-align: right;'>"+data[i].cedpersona+"</th><th style='text-align: right;'>"+data[i].solicitud+"</th><tbody>"); 
                }
                $("#tabledata").dataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        "bDestroy": true
                    });
                
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