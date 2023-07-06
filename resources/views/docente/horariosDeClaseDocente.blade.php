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
<li class="" )>
  @endsection

  @section('activePorcentajeAsistencia')
<li class="active" )>
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
    <h2>Horarios de Clases</h2>
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
                </div>
            </div>
        </div>

        <div class="box-body">
            <table id="tabledata" class="table table-hover table-condensed table-bordered">

            </table>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalVerCalificaciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Revisar Horario</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-body table-responsive no-padding" name="calificaciones" id="calificaciones">

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer">
                        <div id="cerrar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $('#periodos').on('change', function(e) {
            var codperiodo = e.target.value;
            cal = "";
            $.get('/periodoshorarios/' + codperiodo, function(data) {
                $(document).ready(function() {
                    $('#tabledata').empty();
                    $('#tabledata').append("<thead><tr><th>Seccion</th><th>Paralelo</th><th>Fase</th><th>Imprimir</th></tr></thead><tbody>");
                    $.each(data, function(index, periodosobj) {
                        $('#tabledata').append('<tr><td>' + periodosobj.nomseccion + '</td><td>' + periodosobj.codparalelo + '</td><td>' + periodosobj.nomfase + '</td><td><a href="horarioclasespdf/' + periodosobj.codfase + '"><button type="button" class="btn btn-default btn-sm" onclick="ver()"><span class="glyphicon glyphicon-print"></span></button></a></td></tr>');
                    })
                    $('#tabledata').append('</tbody>');

                    $("#tabledata").dataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
                        },
                        "bDestroy": true
                    });

                   
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