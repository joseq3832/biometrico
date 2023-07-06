@extends('estudianteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="active treeview">
  @endsection

  @section('activeAsistenciaPorFecha')
<li class="active" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="" )>
  @endsection

  @section('activeAsistenciasPorModulo')
<li class="" )>
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
    <h2>Asistencia entre fechas</h2>

    
    
        <div class="box box-primary">
            <div class="box-body">
                {{ csrf_field() }}
                <div class="col-xs-4">
                    <div class="form-group">
                        

                        <br>
                        <label>Fecha:</label>                  
                        <input type="date" style="width: 100%; line-height: 30px;" name="fecha" id="fecha" min="{{$fechaMin}}" max="{{$fechaMax}}" value="{{$fechaMax}}" >
                        <br>
                     
                    <br>
                    <a><button type="button" class="btn btn-primary" id="btnBuscar" name="btnBuscar" onclick="test()" >Buscar</button></a>

                       
                    </div>   
                </div>
                   
                
                
            </div>
            <div class="box-body" id="box-abreviatura">
        </div>
            <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table> 
            

        </div>
        </div>



        


    




    <script type="text/javascript">

        var codmateria=0;
                
        

        function test(){
            var fecha = document.getElementById("fecha").value;
            
            $.get('/lista_asistencia_por_fecha_estudiante/'+{{$codperiodo}}+'/'+{{$codseccion}}+'/'+fecha, function(data) {
                $('#box-abreviatura').empty();
                $('#box-abreviatura').append("<br><p style='text-align: right;'><label>A</label> Atraso <label>F</label> Falta <label>J</label> Justificado <label>P</label> Presente</p>");

                $('#tabledata').empty();
                $('#tabledata').append("<thead><tr><th>HORA</th><th>MATERIA</th><th>ASISTENCIA</th></thead>");
                for(var i=0; i<data.length; i++){
               
                    if(data[i].estasistencia==1){
                        $('#tabledata').append("<tbody><tr><th>"+data[i].nomhorahorario+"</th><th>"+data[i].nommateria+"</th><th>F</th><tbody>"); 
                    }else{
                        if(data[i].estasistencia==2){
                            $('#tabledata').append("<tbody><tr><th>"+data[i].nomhorahorario+"</th><th>"+data[i].nommateria+"</th><th>P</th><tbody>"); 
                        }else{
                            if(data[i].estasistencia==3){
                            $('#tabledata').append("<tbody><tr><th>"+data[i].nomhorahorario+"</th><th>"+data[i].nommateria+"</th><th>J</th><tbody>"); 
                        }else{
                            if(data[i].estasistencia==4){
                            $('#tabledata').append("<tbody><tr><th>"+data[i].nomhorahorario+"</th><th>"+data[i].nommateria+"</th><th>A</th><tbody>"); 
                        }
                            

                        }
                            

                        }
                        

                    }
                    
                }
            });
        } 

        
        function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }


    </script>


    @endsection