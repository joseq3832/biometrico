@extends('administradorMaster')

    @section('activeInformacion')
<li class="" )>
    @endsection

    @section('activeMenuAsistencia')
<li class="active treeview">
  @endsection

  @section('activeMenuAsistenciaParalelo')
<li class="" )>
  @endsection

  @section('activeMenuAsistenciaParaleloRango')
<li class=" active" )>
  @endsection
  

  @section('activeMenuAsistenciaProcentajes')
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
    <h2>Asistencia entre fechas del curso</h2>
    <div class="box box-primary">
        <div class="box-body">
            {{ csrf_field() }}

            <div class="col-xs-4">
                <div class="form-group">
                <br>    
                <label>Perido: </label>
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
                    <br>
                    <label>Materia:</label>                  
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
            }); 
            document.getElementById("secciones").disabled=false;
            $('#paralelos').empty();
            $('#paralelos').append("<option value=''>Escoja el paralelo</option>"); 
            $('#materias').empty();
            $('#materias').append("<option value=''>Escoja la materia</option>"); 
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

        $('#secciones').on('change', function(e) { 
            codseccion = e.target.value; 
            $.get('/paralelos/'+codperiodo+'/'+codseccion, function(data) {
                $('#paralelos').empty();
                $('#paralelos').append("<option value=''>Escoja el paralelo</option>");
                for(var i=0; i<data.length; i++){
                $('#paralelos').append("<option value='"+data[i].codparalelo+"'>"+data[i].codparalelo+"</option>");   
                }
            });
            document.getElementById("paralelos").disabled=false;
 
            $('#materias').empty();
            $('#materias').append("<option value=''>Escoja la materia</option>"); 
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

        $('#paralelos').on('change', function(e) { 
            codparalelo = e.target.value;
            $.get('/materias/'+codperiodo+'/'+codseccion+'/'+codparalelo, function(data) {
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

        $('#materias').on('change', function(e) {
            codmateria = e.target.value;
            $.get('/fechas_materia/'+codperiodoseccionparalelo+'/'+codmateria, function(data) {
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

            $.get('/asistenciaDeEstudianteRango/'+codperiodo+'/'+codseccion+'/'+codparalelo+'/'+codmateria+'/'+fechaInicio+'/'+fechaFin, function(data) {
               
                $('#box-abreviatura').empty();
                $('#box-abreviatura').append("<br><p style='text-align: right;'><label>A</label> Atraso <label>F</label> Falta <label>J</label> Justificado <label>P</label> Presente</p>");

                var body = document.getElementsByTagName("tabledata")[0];

                    // Crea un elemento <table> y un elemento <tbody>
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
                        //celda = document.createElement("td");
                        //textoCelda = document.createTextNode("% ASISTENCIA");
                        //celda.appendChild(textoCelda);
                        //hilera.appendChild(celda);
                        // agrega la hilera al final de la tabla (al final del elemento tblbody)
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
                    // modifica el atributo "border" de la tabla y lo fija a "2";
                    //tabla.setAttribute("border", "2");
                    $('#box-footer').empty();
                    $('#box-footer').append("<br><a href='asistenciaEntreFechaspdf/"+codperiodo+"/"+codseccion+"/"+codparalelo+"/"+codmateria+"/"+fechaInicio+"/"+fechaFin+"'><button type='button' class='btn btn-primary' onclick='ver()'>Imprimir</button></a>");
                
            });
        } 




















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

        


        function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }



    </script>


    @endsection