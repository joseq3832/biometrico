@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class=" treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class="" )>
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
<li class="active" )>
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

    @section('activeMenuReportesDeAsistenciaMateria')
<li class="" )>
    @endsection

    @section('content')
    <h2>Listar justificaciones</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

<div class="col-xs-4">
                <div class="form-group">
                <br>    
                <label>Perido: </label>
                    <select class="form-control select2" style="width: 100%;" name="periodos" id="periodos">
                        <option value="">Escoja el periodo</option>
                        @foreach ($Periodos as $periodo)
                            @if($periodo['codperiodo'] < 56 )
                                <option value="{{ $periodo['codperiodo']}}" disabled>{{$periodo['nomperiodo']}}</option>
                            @else
                                <option value="{{ $periodo['codperiodo']}}">{{$periodo['nomperiodo']}}</option>
                            @endif
                        @endforeach
                    </select>
                    <br>
                    <label>Sección:</label>
                    <select class="form-control select2" style="width: 100%;" name="secciones" id="secciones" disabled>                  
                        <option value="">Escoja la sección</option>                     
                    </select>
                    <br>
                    <label>Paralelo:</label>
                    <select class="form-control select2" style="width: 100%;" name="paralelos" id="paralelos" disabled>                   
                        <option value="">Escoja el paralelo</option>
                    </select>
                    <br>
                    
                    
                         
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
        var  codparalelo;
        var codmateria=0;
        var codperiodoseccionparalelo=0;
        var codhorariodia=0;
        var codfase=0;
        var codhorariofase=0;
        var asistencia = 0;
        //var fechaInicio;
        //var fechaFin;
        
        $('#periodos').on('change', function(e) { 
            codperiodo = e.target.value; 
            $.get('/secciones/' + codperiodo, function(data) {
                $('#secciones').empty();
                $('#secciones').append("<option value=''>Escoja la sección</option>");
                for(var i=0; i<data.length; i++){
                $('#secciones').append("<option value='"+data[i].codseccion+"'>"+data[i].nomseccion+"</option>");   
                }
            });  

            $('#box-body').empty();
            $('#secciones').prop('disabled', false);
            $('#paralelos').prop('disabled', true);
            //$('#paralelos').empty();
            $('#paralelos').append("<option value=''>Escoja el paralelo</option>"); 
          
        });

        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            $.get('/paralelos/'+codperiodo+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }

                $('#box-body').empty();
                $('#tabledata').empty();
                $('#paralelos').prop('disabled', false);
            });            
        });

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value;
            $.get('/lista_jusatificaciones_administrador/'+codperiodo+'/'+codseccion+'/'+codparalelo, function(data) {
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>FECHA</th><th>NOMBRES</th><th style='text-align: center;'>CÉDULA</th><th style='text-align: center;'>MATERIA</th><th style='text-align: center;'>Nº SOLICITUD</th></thead>");
                for(var i=0; i<data.length; i++){
                    var cont=i+1;

                    $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].fecha+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th style='text-align: right;'>"+data[i].cedpersona+"</th><th style='text-align: right;'>"+data[i].nommateria+"</th><th style='text-align: right;'>"+data[i].solicitud+"</th><tbody>"); 
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