@extends('docenteMaster')

@section('activeInformacion')
<li class="" )>
  @endsection

  @section('activeMenuAsistencias')
<li class="active treeview">
  @endsection

  @section('activeTomaLista')
<li class="active" )>
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

    <h2>Tomar Asistencia</h2>

    <div>
        
       <p>Materia: {{$Datos->nommateria}}</p>
       <p>Seccion: {{$Datos->nomseccion}}</p>
       <input type="text" class="form-control" value="{{$Datos->fecha}}" name="fecha" id="fecha" style="display:none;">    
       
    </div>
    <form id="form_id">
        <div class="box box-primary">
            <div class="box-body">
                {{ csrf_field() }}
                <table id="paralelos" class="table table-hover table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Apellidos</th>
                            <th>Nombres</th>
                            <th>Ausente</th>
                            <th>Presente</th>
                            <th>Atraso</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Estudiantes as $estudiante)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$estudiante->apepersona}}</td>
                            <td>{{$estudiante->nompersona}}</td>
                            
                            @if($estudiante->estasistencia==1)
                                <td><input onclick="marcarAusente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAusente" name="{{$estudiante->codpersona}}" value="1" checked></td>
                                <td><input onclick="marcarPresente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioPresente" name="{{$estudiante->codpersona}}" value="2"></td>
                                <td><input onclick="marcarAtraso({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAtraso" name="{{$estudiante->codpersona}}" value="4"></td>
                            @else
                                @if($estudiante->estasistencia==2 || $estudiante->estasistencia==3 )
                                    <td><input onclick="marcarAusente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAusente" name="{{$estudiante->codpersona}}" value="1" ></td>
                                    <td><input onclick="marcarPresente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioPresente" name="{{$estudiante->codpersona}}" value="2" checked></td>
                                    <td><input onclick="marcarAtraso({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAtraso" name="{{$estudiante->codpersona}}" value="4"></td>
                                @else
                                        @if($estudiante->estasistencia==4)
                                        <td><input onclick="marcarAusente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAusente" name="{{$estudiante->codpersona}}" value="1" ></td>
                                        <td><input onclick="marcarPresente({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioPresente" name="{{$estudiante->codpersona}}" value="2" ></td>
                                        <td><input onclick="marcarAtraso({{$estudiante->codasistencia}},{{$estudiante->codpersona}},{{$Datos->codmateria}})" class="form-check-input" type="radio" id="radioAtraso" name="{{$estudiante->codpersona}}" value="4" checked></td>
                                      
                                         @endif
                                @endif
                            @endif
           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>


    




<script type="text/javascript">

    function marcarPresente(codasistencia,codpersona,codmateria){
        var _token2=$("input[name=_token]").val(); 
        var fecha = document.getElementById("fecha").value;
        var estasistencia=2;
        $.ajax({
            url: "{{route('postActualizarAsistenciaTomarLista')}}",
            type: "POST",
            data: {
                codasistencia: codasistencia,
                codpersona: codpersona,
                estasistencia: estasistencia,
                fecha: fecha,
                codmateria: codmateria,
                _token:_token2

            },
            beforeSend: function() {
                $('#girar2').show();
            },
            success: function(response) {
                if(response){
                    $('#alert').show();
                    $('#alert').html("La asistencia se registro de manera exitos");
                    setTimeout(function() { $('#alert').hide(450); }, 2000);   
                }
            },
            error: function(response) {
                toastr.warning('Ocurrio un error intente nuevamente', '¡FALLIDA!', {timeOut: 3000});
            }
        });
        } 

        function marcarAusente(codasistencia,codpersona,codmateria){
        var _token2=$("input[name=_token]").val(); 
        var fecha = document.getElementById("fecha").value;
        var estasistencia=1;
        $.ajax({
            url: "{{route('postActualizarAsistenciaTomarLista')}}",
            type: "POST",
            data: {
                codasistencia: codasistencia,
                codpersona: codpersona,
                estasistencia: estasistencia,
                fecha: fecha,
                codmateria: codmateria,
                _token:_token2

            },
            beforeSend: function() {
                $('#girar2').show();
            },
            success: function(response) {
                if(response){
                    $('#alert').show();
                    $('#alert').html("La inasistencia se registro de manera exitos");
                    setTimeout(function() { $('#alert').hide(450); }, 2000);   
                }
            },
            error: function(response) {
                toastr.warning('Ocurrio un error intente nuevamente', '¡FALLIDA!', {timeOut: 3000});
            }
        });
        } 

        function marcarAtraso(codasistencia,codpersona,codmateria){
        var _token2=$("input[name=_token]").val(); 
        var fecha = document.getElementById("fecha").value;
        var estasistencia=4;
        $.ajax({
            url: "{{route('postActualizarAsistenciaTomarLista')}}",
            type: "POST",
            data: {
                codasistencia: codasistencia,
                codpersona: codpersona,
                estasistencia: estasistencia,
                fecha: fecha,
                codmateria: codmateria,
                _token:_token2

            },
            beforeSend: function() {
                $('#girar2').show();
            },
            success: function(response) {
                if(response){
                    $('#alert').show();
                    $('#alert').html("El atraso se registro de manera exitos");
                    setTimeout(function() { $('#alert').hide(450); }, 2000);   
                }
            },
            error: function(response) {
                toastr.warning('Ocurrio un error intente nuevamente', '¡FALLIDA!', {timeOut: 3000});
            }
        });
        } 


   


</script>


@endsection