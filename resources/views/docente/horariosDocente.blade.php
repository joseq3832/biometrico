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
<li class="treeview">
  @endsection

  @section('activeListaJustificaciones')
<li class="" )>
  @endsection

  @section('activeMenuReportes')
<li class="active treeview">
    @endsection

    @section('activeListadoEstudiantes')
<li class="" )>
    @endsection

    @section('activeHorarioClases')
<li class=" active" )>
    @endsection

    @section('activeReporteFinalAsistencia')
<li class="" )>
    @endsection

    
    
    @section('content')


    <h2>Horarios de Clases</h2>
    <form id="">
    <div class="box box-primary">
        <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Seccion</th>
                    <th>Paralelo</th>
                    <th>Fase</th>
                    <th>Materia</th>
                    <th>Imprimir</th>
                </tr>
            </thead>
                @foreach ($listaFases as $lista)
                <tr>
                <td>{{$lista->nomseccion}}</td>
                <td>{{$lista->codparalelo}}</td>
                <td>{{$lista->nomfase}}</td>
                <td>{{$lista->nommateria}}</td>
                <td><a href="reporte_horario_clases/{{$lista->codfase}}"><button type="button" id="pdf" class="btn btn-default btn-bg" onclick="ver()"><span class="glyphicon glyphicon-print"></span></button></a></td>
                       
            </tr>
                @endforeach
            </table>

        </div>
    </div>

    </form>
    





    <script type="text/javascript">
       

        
        

       

        function ver() {
            $('#contenedor_carga_ajax').show();
            setTimeout(function() {
                $('#contenedor_carga_ajax').hide(450);
            }, 12000);
        }
        function justNumbers(e) {
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
                return true;

            return /\d/.test(String.fromCharCode(keynum));
        }

        
    </script>


@endsection