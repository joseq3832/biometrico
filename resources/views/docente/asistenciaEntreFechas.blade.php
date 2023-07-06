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
<li class="active" )>
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
    <h2>Asistencia entre fechas</h2>

    
    
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

            document.getElementById("paralelos").disabled=false;
            document.getElementById("fechainicio").disabled=true;  
            document.getElementById("fechainicio").value='dd/mm/aaaa'; 
            document.getElementById("fechafin").disabled=true;  
            document.getElementById("fechafin").value='dd/mm/aaaa';  
            document.getElementById("materias").disabled=true;
            document.getElementById("btnBuscar").disabled=true;  
            $('#tabledata').empty();    
            $('#box-footer').empty();
            $('#box-abreviatura').empty();  
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
                document.getElementById("fechainicio").disabled=true;  
                document.getElementById("fechainicio").value='dd/mm/aaaa'; 
                document.getElementById("fechafin").disabled=true;  
                document.getElementById("fechafin").value='dd/mm/aaaa';  
                document.getElementById("btnBuscar").disabled=true; 
                $('#tabledata').empty();    
                $('#box-footer').empty();
                $('#box-abreviatura').empty();  
            }); 
                       
        });

        
        $('#materias').on('change', function(e) { 
            codmateria = e.target.value; 
            $.get('/fecha_materia/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria, function(data) {
                fechainicio.min = data[0].fechaMin;
                fechainicio.max = data[0].fechaMax;
                fechainicio.value = data[0].fechaMin;

                fechafin.min = data[0].fechaMin;
                fechafin.max = data[0].fechaMax;
                fechafin.value = data[0].fechaMax;
                
               
                document.getElementById("fechainicio").disabled=false;  
                document.getElementById("fechafin").disabled=false;  ;  
                document.getElementById("btnBuscar").disabled=false; 
                $('#tabledata').empty();    
                $('#box-footer').empty();
                $('#box-abreviatura').empty();  
            });            
        });

        function test(){
            var fechaInicio = document.getElementById("fechainicio").value;
            var fechaFin = document.getElementById("fechafin").value;
            $.get('/asistenciaDeEstudianteRango/'+{{$ultimoPeriodo}}+'/'+codseccion+'/'+codparalelo+'/'+codmateria+'/'+fechaInicio+'/'+fechaFin, function(data) {
                $('#box-abreviatura').empty();
                $('#box-abreviatura').append("<br><p style='text-align: right;'><label>A</label> Atraso <label>F</label> Falta <label>J</label> Justificado <label>P</label> Presente</p>");

                var body = document.getElementsByTagName("tabledata")[0];
                    var tabla   = document.createElement("table");
                    tabla.className = 'table table-hover table-condensed table-bordered';
                    var tblhead   = document.createElement("thead");
                    for (var i = 0; i < 1; i++) {
                        // Crea las hileras de la tabla
                        var hilera = document.createElement("tr");
                        var celda = document.createElement("td");
                        var textoCelda = document.createTextNode("Nº");
                        celda.appendChild(textoCelda);
                        hilera.appendChild(celda);
                        celda = document.createElement("td");
                        textoCelda = document.createTextNode("NOMINA DE ESTUDIANTES");
                        celda.appendChild(textoCelda);
                        hilera.appendChild(celda);

                        for (var j = 0; j < data.Fechas.length; j++) {
                            celda = document.createElement("td");
                            celda.className = 'verticalText';
                            textoCelda = document.createTextNode(""+data.Fechas[j].fecha+"");
                            celda.appendChild(textoCelda);
                            hilera.appendChild(celda);
                        }
                        tblhead.appendChild(hilera);
                    }
                    for (var i = 0; i < data.EstudiantesLista.length; i++) {
                        // Crea las hileras de la tabla
                        var hilera = document.createElement("tr");
                        var celda = document.createElement("td");
                        var cont=i+1;
                        var textoCelda = document.createTextNode(""+cont+"");
                        celda.appendChild(textoCelda);
                        hilera.appendChild(celda);
                        celda = document.createElement("td");
                        textoCelda = document.createTextNode(""+data.EstudiantesLista[i].apepersona+" "+data.EstudiantesLista[i].nompersona+"");
                        celda.appendChild(textoCelda);
                        hilera.appendChild(celda);
                        for (var j = 0; j < data.Estudiantes.length; j++) {
                            if(data.EstudiantesLista[i].codpersona == data.Estudiantes[j].codpersona){
                                celda = document.createElement("td");
                                //celda.style.background = 'red';
                                if(data.Estudiantes[j].estasistencia == 1){
                                    textoCelda = document.createTextNode("F");
                                }else{
                                    if(data.Estudiantes[j].estasistencia == 2){
                                        textoCelda = document.createTextNode("P");
                                    }else{
                                        if(data.Estudiantes[j].estasistencia == 3){
                                            textoCelda = document.createTextNode("J");
                                        }else{
                                            if(data.Estudiantes[j].estasistencia == 4){
                                                textoCelda = document.createTextNode("A");
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
                    $('#box-footer').empty();
                    $('#box-footer').append("<br><a href='asistenciaEntreFechaspdf/"+{{$ultimoPeriodo}}+"/"+codseccion+"/"+codparalelo+"/"+codmateria+"/"+fechaInicio+"/"+fechaFin+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");               
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