@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class=" treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class=" " )>
  @endsection

  @section('activeMenuAsistenciaParaleloRango')
<li class=" " )>
  @endsection
  
  @section('activeMenuAsistenciaProcentajes')
<li class="" )>
  @endsection

  @section('activeMenuAsistenciaIndividual')
<li class="" )>
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
<li class=" active treeview">
    @endsection

    @section('activeMenuReportesListadoEstudiantes')
<li class=" " )>
    @endsection

    @section('activeMenuReportesHorarioClases')
<li class="" )>
    @endsection

    @section('activeMenuReportesDeAsistenciaMateria')
<li class="active" )>
    @endsection

    @section('activeMenuReportesGeneralDeAsistencia')
<li class="" )>
    @endsection
    
    
    @section('content')
    <h2>Reporte de asistencia por materia</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">
                <div class="form-group">
                <br>
                    <label>Periodo:</label>
                    <select class="form-control select2" style="width: 100%;" name="periodos" id="periodos">
                        <option value="">Escoja el periodo</option>
                        @foreach ($periodos as $periodo)
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
                </div>
            </div>
        </div>

        <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table>

        </div>
    </div>

    <!-- Modal -->


    <script type="text/javascript">
        var codperiodo=0;
        var codseccion=0;
        var  codparalelo;
        var codmateria=0;
        var codperiodoseccionparalelo=0;
        var codhorariodia=0;
        var codfase=0;
        var codhorariofase=0;
        var asistencia = 0;
        
        $('#periodos').on('change', function(e) { 
            codperiodo = e.target.value; 
            $.get('/secciones/' + codperiodo, function(data) {
                $('#secciones').empty();
                $('#secciones').append("<option value=''>Escoja la sección</option>");
                for(var i=0; i<data.length; i++){
                $('#secciones').append("<option value='"+data[i].codseccion+"'>"+data[i].nomseccion+"</option>");   
                }
                document.getElementById("secciones").disabled=false;
                document.getElementById("paralelos").disabled=true;
                $('#tabledata').empty();    
               
            });  
        });

        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            $.get('/paralelos/'+codperiodo+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }
                
                document.getElementById("paralelos").disabled=false;
                $('#tabledata').empty();    
                
            });            
        });

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value;
            
            $.get('/materias_asistencia/'+codperiodo+'/'+codseccion+'/'+codparalelo, function(data) {
                $(document).ready(function() {
                    $('#tabledata').empty();
                    $('#tabledata').append("<thead><tr><th>Materias </th><th>Imprimir</th></tr></thead><tbody>");
                    $.each(data, function(index, materias) {
                        $('#tabledata').append('<tr><td>' + materias.nommateria + '</td><td><a href="asistenciaDeMateriapdf/'+codperiodo+'/'+codseccion+'/'+codparalelo+'/'+materias.codmateria+'"><button type="button" class="btn btn-default btn-sm" onclick="ver()"><span class="glyphicon glyphicon-print"></span></button></a></td></tr>');
                    })
                    $('#tabledata').append('</tbody>');

                   
                });
            }); 
            
        });
    </script>
    <script>
        

        function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }
    </script>

    @endsection