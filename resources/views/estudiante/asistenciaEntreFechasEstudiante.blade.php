@extends('estudianteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="active treeview">
  @endsection

  @section('activeAsistenciaPorFecha')
<li class="" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="active" )>
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
    <h2>Asistencia del curso</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">
                <div class="form-group">
                <br>    
                <label>Perido: </label>
                    <select class="form-control select2" style="width: 100%;" name="fases" id="fases">
                        <option value="">Escoja la fase</option>
                        @foreach ($listaFases as $fase)
                            <option value="{{ $fase->codfase}}" >{{$fase->nomfase}}</option>
                        @endforeach
                    </select>

                    <br>
                    <label>Desde:</label>                 
                        <input type="date" style="width: 41%; line-height: 30px;" name="fechainicio" id="fechainicio" value="" disabled>
                    <label>Hasta:</label>  
                        <input type="date" style="width: 41%; line-height: 30px;" name="fechafin" id="fechafin" value="" disabled>           
                    <br>
                    <br>
                    <a><button type="button" class="btn btn-primary" id="btnBuscar" name="btnBuscar" onclick="test()" disabled>Buscar</button></a>     
                </div>
            </div>    
        </div>

        <div class="box-body" id="box-abreviatura">
        </div>
        <div class="box-body" style="overflow-x:auto;">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">
            </table> 
        </div>
        <div class="box-footer" id="box-footer">
        </div>
    </div>




    <script type="text/javascript">

        var codfase=0;



        $('#fases').on('change', function(e) {
            codfase = e.target.value;
            $.get('/fechas_fase/'+{{$codperiodoseccionparalelo}}+'/'+codfase, function(data) {
                //var fecha = data[0].feciniciofase;
                fechainicio.min = data[0].feciniciofase;
                fechainicio.max = data[0].fecfinfase;
                fechainicio.value = data[0].feciniciofase;
                fechafin.min = data[0].feciniciofase;
                fechafin.max = data[0].fecfinfase;
                fechafin.value = data[0].fecfinfase;
            }); 
            document.getElementById("fechainicio").disabled=false;
            document.getElementById("fechafin").disabled=false;
            document.getElementById("btnBuscar").disabled=false;
             
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty(); 
        });

        $('#fechainicio').on('change', function(e) { 
            fechaInicio = e.target.value; 
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty(); 
                     
        });
        $('#fechafin').on('change', function(e) { 
            fechaFin = e.target.value; 
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty(); 
                     
        });

        function test(){
            document.getElementById("tabledata").disabled=false;
            document.getElementById("box-footer").disabled=false;
            document.getElementById("box-abreviatura").disabled=false;
            //document.getElementById("fecha").value = fechaInicio;
            var fechaInicio = document.getElementById("fechainicio").value;
            //document.getElementById("myDate").value;
            var fechaFin = document.getElementById("fechafin").value;

            $.get('/asistencia_estudiante_entre_fechas/'+codfase+'/'+fechaInicio+'/'+fechaFin, function(data) {

                var body = document.getElementsByTagName("tabledata")[0];
                    // Crea un elemento <table> y un elemento <tbody>
                var tabla   = document.createElement("table");
                tabla.className = 'table table-hover table-condensed table-bordered';
                var tblhead   = document.createElement("thead");
               // Crea las hileras de la tabla
               var hilera = document.createElement("tr");
                var celda = document.createElement("td");
                var textoCelda = document.createTextNode("HORA");
                celda.appendChild(textoCelda);
                hilera.appendChild(celda);
                

                for (var i = 0; i < data.Fechas.length; i++) {
                    celda = document.createElement("td");
                    celda.className = 'verticalText';
                    textoCelda = document.createTextNode(""+data.Fechas[i].fecha+"");
                    celda.appendChild(textoCelda);
                    hilera.appendChild(celda);
                }
                tblhead.appendChild(hilera);
                for (var i = 0; i < data.Horas.length; i++) {
                     // Crea las hileras de la tabla
                    var hilera = document.createElement("tr");
                    var celda = document.createElement("td");
                       
                    textoCelda = document.createTextNode(""+data.Horas[i].nomhorahorario+"");
                    celda.appendChild(textoCelda);
                    hilera.appendChild(celda);
                    for(var j = 0; j < data.Asistencias.length; j++) {
                        if(data.Asistencias[j].nomhorahorario == data.Horas[i].nomhorahorario){
                            celda = document.createElement("td");
                                //celda.style.background = 'red';
                                if(data.Asistencias[j].estasistencia == 1){
                                    textoCelda = document.createTextNode("F-"+data.Asistencias[i].nommateria+"");
                                    celda.style.color = 'red';
                                    
                                }else{
                                    if(data.Asistencias[j].estasistencia == 2){
                                        textoCelda = document.createTextNode("P-"+data.Asistencias[i].nommateria+"");
                                
                                    }else{
                                        if(data.Asistencias[j].estasistencia == 3){
                                            textoCelda = document.createTextNode("J-"+data.Asistencias[i].nommateria+"");
                              
                                        }else{
                                            if(data.Asistencias[j].estasistencia == 4){
                                                textoCelda = document.createTextNode("A-"+data.Asistencias[i].nommateria+"");
        
                                            }
                                         }
                                    }
                                }
                                celda.appendChild(textoCelda);
                                hilera.appendChild(celda);

                        }

                    }
                    tblhead.appendChild(hilera);
                    
                    
                        
                }

                
                tabla.appendChild(tblhead);
                tabledata.appendChild(tabla);
                
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