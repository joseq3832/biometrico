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
<li class="active" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="" )>
  @endsection

  @section('activePorcentajeAsistencia')
<li class="" )>
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
    <h2>Asistencia por día</h2>

    
    
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

                        <br>
                        <label>Fecha:</label>                  
                        <input type="date" style="width: 100%; line-height: 30px;" name="fecha" id="fecha" value="" disabled>
                        <br>

                       
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
        var codmateria=0;
        
        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            $.get('/paralelos/'+{{$ultimoPeriodo}}+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                    $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }
            });  
            document.getElementById("paralelos").disabled=false;          
            document.getElementById("materias").disabled=true;
            document.getElementById("fecha").disabled=true;
            document.getElementById("fecha").value='dd/mm/aaaa';  
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty();
            
        });

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value; 
            $.get('/materias_docente/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo, function(data) {
                $('#materias').empty();
                $('#materias').append("<option value=''>Escoja la materia</option>");
                for(var i=0; i<data.length; i++){
                    $('#materias').append("<option value='"+data[i].codmateria+"'>"+data[i].nommateria+"</option>");   
                }
            });  
            document.getElementById("materias").disabled=false;
            document.getElementById("fecha").disabled=true;
            document.getElementById("fecha").value='dd/mm/aaaa';
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty();            
        });

        
        $('#materias').on('change', function(e) { 
            codmateria = e.target.value; 
            $.get('/fecha_materia/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria, function(data) {
                fecha.min = data[0].fechaMin;
                fecha.max = data[0].fechaMax;
                fecha.value = data[0].fechaMin;
                
                document.getElementById("fecha").disabled=false;
                $('#tabledata').empty();    
                $('#box-footer').empty();
                $('#box-abreviatura').empty();
            });            
        });

        $('#fecha').on('change', function(e) { 
            fecha = e.target.value; 
            $.get('/lista_asistencia_por_fecha/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria+'/'+fecha, function(data) {
                $('#box-abreviatura').empty();
                $('#box-abreviatura').append("<br><p style='text-align: right;'><label>A</label> Atraso <label>F</label> Falta <label>J</label> Justificado <label>P</label> Presente</p>");
                
                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>Nº</th><th>NOMBRES</th><th>ASISTENCIA</th></thead>");
                for(var i=0; i<data.length; i++){
                    var cont = i +1;
                    if(data[i].estasistencia==1){
                        $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>F</th><tbody>"); 
                    }else{
                        if(data[i].estasistencia==2){
                            $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>P</th><tbody>"); 
                        }else{
                            if(data[i].estasistencia==3){
                            $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>J</th><tbody>"); 
                        }else{
                            if(data[i].estasistencia==4){
                            $('#tabledata').append("<tbody><tr><th>"+cont+"</th><th>"+data[i].apepersona+" "+data[i].nompersona+"</th><th>A</th><tbody>"); 
                        }
                            

                        }
                            

                        }
                        

                    }
                    
                }

                $('#box-footer').empty();
                $('#box-footer').append("<br><a href='asistenciaPorDiapdf/"+{{$ultimoPeriodo}}+"/"+codseccion+"/"+codparalelo+"/"+codmateria+"/"+fecha+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");
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