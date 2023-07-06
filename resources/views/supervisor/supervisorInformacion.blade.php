@extends('supervisorMaster')

@section('activeInformacion')
<li class="active" )>
  @endsection

  @section('activeMenuAsistencias')
<li class=" treeview">
  @endsection

  @section('activeAsistenciaPorFecha')
<li class="" )>
  @endsection

  @section('activeAsistenciaRangoFechas')
<li class="" )>
  @endsection

    

  @section('content')

  <h2>Información Personal</h2>

  @foreach ($informacionPersonal as $inf)


  <br>


  <section class="content">
    <div class="row">
      <form role="form" method="post" action="{{route('UpdateEstudiante', $inf->codpersona)}}" enctype="multipart/form-data">
        <div class="col-md-6">
          <div class="box box-primary">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label>Cédula</label>
                <input type="text" class="form-control" value="{{ $inf -> cedpersona}}" name="cedpersona" disabled>
              </div>

              <div class="form-group">
                <label>Nombres</label>
                <input type="text" class="form-control" value="{!! $inf -> apepersona.' '.$inf->nompersona!!}" name="nomPersona" disabled>
              </div>

              <div class="form-group">
                <label>Tipo</label>
                <input type="text" class="form-control" value="{{$inf -> tippersona}}" name="tipPersona" disabled>
              </div>

              <div class="form-group {{ $errors->has('convencional') ? ' has-error' : '' }}">
                <label>Número Convencional</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-phone"></i>
                  </div>
                  <input type="text" class="form-control" placeholder="Telef. Convencional" data-inputmask='"mask": "(99) 9999-999"' value="{{$inf -> telconvencionalpersona}}" name="convencional" onkeypress="return justNumbers(event);" data-mask required disabled>
                </div>
                @if ($errors->has('convencional'))
                <span class="help-block">
                  <strong>{{ $errors->first('convencional') }}</strong>
                </span>
                @endif
              </div>

              <div class="form-group {{ $errors->has('celular') ? ' has-error' : '' }}">
                <label>Número Celular</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-mobile"></i>
                  </div>
                  <input type="text" class="form-control" placeholder="Telef. Celular" data-inputmask='"mask": "(99) 99999999"' value="{{$inf -> telcelularpersona}}" name="celular" onkeypress="return justNumbers(event);" data-mask required disabled>
                </div>
                @if ($errors->has('celular'))
                <span class="help-block">
                  <strong>{{ $errors->first('celular') }}</strong>
                </span>
                @endif
              </div>

              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="exampleInputEmail1">Correo Electrónico</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="email" class="form-control" placeholder="Email" value="{{$inf -> corpersona}}" name="email" disabled>
                </div>
                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>


            </div>
          </div>
        </div>

        

      </form>
    </div>
  </section>

  @endforeach

  <script>
    function justNumbers(e) {
      var keynum = window.event ? window.event.keyCode : e.which;
      if ((keynum == 8) || (keynum == 46))
        return true;

      return /\d/.test(String.fromCharCode(keynum));
    }
  </script>

  @endsection