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

  @section('activeListaJustificaciones')
<li class="" )>
  @endsection

  @section('activeMenuJustificaciones')
<li class="treeview">
  @endsection

  @section('activeMenuJustificacionesIndividual')
<li class="" )>
  @endsection

  @section('activeMenuReportes')
<li class=" active treeview">
    @endsection

    @section('activeMenuReportesListadoEstudiantes')
<li class="active " )>
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
    <h2>Formato para la toma de asistencia</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">
                <div class="form-group">
                    <br>    
                    <input type="text" class="form-control" value="{{$ultimoPeriodo}}" name="codperiodo" id="codperiodo" style="display:none;">                  
                                     
                    <br>
                    
                    <label>Sección:</label>
                    <select class="form-control select2" style="width: 100%;" name="secciones" id="secciones" > 
                    <option value="">Escoja una seccion</option>
                    @foreach ($secciones as $seccion)          
                    <option value="{{ $seccion->codseccion}}">{{$seccion->nomseccion}}</option>
                    @endforeach                    
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
        <div class="box-footer" id="box-footer">

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
        
        codperiodo = 56;
  

        $('#secciones').on('change', function(e) { 
            //codperiodo = document.getElementById("codperiodo").value;
            codperiodo = document.getElementById("codperiodo").value;
            codseccion = e.target.value; 
            $.get('/paralelos/'+codperiodo+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }

                $('#paralelos').prop('disabled', false);
                $('#box-body').empty();
                $('#box-footer').empty();
                $('#tabledata').empty(); 
            });            
        });

       

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value; 

            $.get('/lista_estudiante/'+codperiodo+'/'+codseccion+'/'+codparalelo, function(data) {
                var cont=1;
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>CÉDULA</th><th>NOMBRES</th><th>ASISTENCIA</th></thead>");
                for(var i=0; i<data.length; i++){
                    $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].cedpersona+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th></th><tbody>");    
                    cont++;
                }
                $('#box-footer').empty();
                $('#box-footer').append("<br><a href='reportes/formatoDeAsistenciapdf/"+codperiodo+"/"+codseccion+"/"+codparalelo+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");
                
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