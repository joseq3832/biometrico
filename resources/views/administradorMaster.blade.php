<!DOCTYPE html>

<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.jpg')}}">
  <link rel="icon" type="image/png" href="{{asset('assets/img/now-logo.png')}}">
  <title>Control de Asistencia Administrador</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <link rel="stylesheet" href="{{asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/skin-blue.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/skin-green.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/skin-green-light.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/skin-blue.min.css')}}">
  <link rel="stylesheet" href="{{asset('dist/css/skins/skin-red.min.css')}}">




  
  


   
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">



  <!-- nuevo loader -->
  <style>
    *,
    *:after,
    *:before {
      margin: 0;
      padding: 0;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      box-sizing: border-box;
    }

    #contenedor_carga {
      background-color: rgba(0, 0, 0, 0.473);
      height: 100%;
      width: 100%;
      position: fixed;
      -webkit-transition: all 1s ease;
      -o-transition: all 1s ease;
      transition: all 1s ease;
      z-index: 10000;
    }   

    #contenedor_carga_ajax {
      background-color: rgba(0, 0, 0, 0.473);
      height: 100%;
      height: 100%;
      width: 100%;
      position: fixed;
      -webkit-transition: all 1s ease;
      -o-transition: all 1s ease;
      transition: all 1s ease;
      z-index: 10000;
    }

    #carga {
      border: 9px solid #ccc;
      border-top-color: rgb(31, 110, 35);
      /*border-top-style: groove;*/
      height: 60px;
      width: 60px;
      border-radius: 100%;
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      -webkit-animation: girar 1.5s linear infinite;
      -o-animation: girar 1.5s linear infinite;
      animation: girar 1.5s linear infinite;
    }

    #texto {
      height: 10px;
      width: 180px;
      border-radius: 100%;
      position: absolute;
      top: 15%;
      left: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      color:#ffffff;
      font-size: 1.5em;
    }

    @keyframes girar {
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    .vertical
    {
      transform: rotate(-90deg);
      -webkit-transform: rotate(-90deg); /* Safari/Chrome */
      -moz-transform: rotate(-90deg); /* Firefox */
      -o-transform: rotate(-90deg); /* Opera */
      -ms-transform: rotate(-90deg); /* IE 9 */
    }

    .verticalText {
      
      writing-mode: vertical-lr;
      transform: rotate(180deg);
    }
  </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div id="contenedor_carga">
    <img id="carga" src="{{asset('assets/img/now-logo.png')}}" alt="">
  </div>

  <div id="contenedor_carga_ajax" style="display: none;">
    <img id="carga" src="{{asset('assets/img/now-logo.png')}}" alt="">
    <div id="texto">Espere por favor ...</div>
  </div>

  <div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
      <!-- Logo -->
      <a href="{{route('home')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>
            <img src="{{asset('assets/img/now-logo.png')}}" style="width: 60%; height: 60%;">
          </b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Administrador</b></span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">

              <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <!--<img src="/storage/app/public/{{Auth()->user()->huella}}" class="user-image" alt="User Image">-->
                <img src="/storage/{{Auth()->user()->huella}}" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{!!auth()->user()->apepersona.' '.auth()->user()->nompersona!!}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <!--<img src="/storage/app/public/{{Auth()->user()->huella}}" class="img-circle" alt="User Image">-->
                  <img src="/storage/{{Auth()->user()->huella}}" class="img-circle" alt="User Image">
                  <p>
                    {!! auth()->user()->abreviatura.' '.auth()->user()->apepersona.' '.auth()->user()->nompersona !!}
                  </p>
                </li>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">



              <div class="pull-right">
                <form method="POST" action="{{route('logout')}}">
                  {{ csrf_field() }}
                  <button class="btn btn-default btn-flat">Cerrar Sesión </button>
                </form>
              </div>

            </li>
          </ul>
          </li>
          </ul>
        </div>
      </nav>
    </header>
    
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <br>
        <div class="user-panel">
          <div class="pull-left image">
          <img src="/storage/{{Auth()->user()->huella}}" class="img-circle" alt="User Image">
            <!--<img src="/storage/app/public/{{Auth()->user()->huella}}" class="img-circle" alt="User Image">-->
          </div>
          <div class="pull-left info">
            <p>{{ auth()->user()->apepersona }}<br> {{auth()->user()->nompersona}}</p>
            <!-- Status -->
            <a href=""><i class="fa fa-circle text-success"></i> Administrador Activo </a>
          </div>
        </div>
        <br><br>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
          <li class="header">Opciones</li>
          <!-- Optionally, you can add icons to the links -->
          @yield('activeInformacion')<a href="{{route('InformacionAdministrador')}}"><i class="fa fa-drivers-license-o"></i> <span>Información Personal</span></a></li>
          @yield('activeMenuAsistencia')
          <a href=""><i class="fa fa-book"></i> <span>Asistencias</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @yield('activeMenuAsistenciaParalelo')<a href="{{route('AsistenciasParaleloAdministrador')}}">Asistencia por día</a></li>
            @yield('activeMenuAsistenciaParaleloRango')<a href="{{route('AsistenciasParaleloRangoAdministrador')}}">Asistencia entre fechas</a></li>
            @yield('activeMenuAsistenciaProcentajes')<a href="{{route('ProcentajesDeAsistenciaEstudiante')}}">Porcentajes de asistencias</a></li>
          </ul>
          </li>
          @yield('activeMenuJustificaciones')
          <a href="#"><i class="fa fa-book"></i> <span>Justificaciones </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            
            @yield('activeMenuJustificacionesIndividual')<a href="{{route('RegistroDeJustificacionDeEstudianteAdministrador')}}">Registro individual</a></li>
            @yield('activeListaJustificaciones')<a href="{{route('ListaJustificacionesAdministrador')}}">Lista de justificaciones</a></li>

          </ul>
          </li>
          @yield('activeMenuReportes')
          <a href="#"><i class="fa fa-file-text"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @yield('activeMenuReportesListadoEstudiantes')<a href="{{route('FormatoAsistencia')}}">Formato de asistencia</a></li>
            @yield('activeMenuReportesHorarioClases')<a href="{{route('AdministradorHorarioClasesEstudiantes')}}">Horarios de clases</a></li>
            @yield('activeMenuReportesDeAsistenciaMateria')<a href="{{route('AdministradorReporteDeAsistenciaMateria')}}">Reporte final de asistencia</a></li>
            @yield('activeMenuReportesGeneralDeAsistencia')<a href="{{route('AdministradorReporteExcelDeAsistenciaMateria')}}">Cuadro general de asistencia</a></li>
            </ul>
          </li>
         
        </ul>
      </section>
    </aside>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          @yield('tituloProceso')
        </h1>

      </section>


      <center>
        <div id="alert" class="alert alert-info" style="display:none; width:50%;"></div>
      </center>

      <center>
        <div id="alertdanger" class="alert alert-danger" style="display:none; width:50%;"></div>
      </center>
      <section class="content container-fluid">
        <center>
          @if(Session::has('message'))
          <div id="message" style="width:50%;">
            <p class=" alert alert-info">{{ Session::get('message') }}</p>
          </div>
          @endif
          @if(Session::has('messagedanger'))
          <div id="messagedanger" style="width:50%;">
            <p class=" alert alert-danger">{{ Session::get('messagedanger') }}</p>
          </div>
          @endif
        </center>
        @yield('content')

      </section>
    </div>


    <!-- Main Footer -->
    <footer class="main-footer">
      <div class=" container ">
      <center>
      <div class="copyright" id="copyright">
          &copy;
          Derechos Reservados
          <a href="http://conduespoch.com/" target="_blank">CONDUESPOCH E.P.</a>
          <script>
            document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
          </script>.
        </div>
      </center>
        
      </div>
    </footer>

    <!-- jQuery 3 -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- Select 2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <!--  Data Tables-->

    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    

 
    <script>
       window.onload = function() {
        var contenedor = document.getElementById('contenedor_carga');
        contenedor.style.visibility = 'hidden';
        contenedor.style.opacity = '0';
      }

      $('.actualizar').on('click', function() {
            $.ajax({
                type: 'post',
                url: "",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { },
                beforeSend: function() {
                    $('#contenedor_carga_ajax').show();

                },
                success: function(data) {
                    $("#contenedor_carga_ajax").hide();
                    $('#alert').show();
                    $('#alert').html("Los promedios se actualizaron correctamente");
                    setTimeout(function() {
                        $('#alert').hide(450);
                    }, 7000);                    
                },
                error: function(error, textStatus, thrownError) {
                    $("#contenedor_carga_ajax").hide();                    
                }
            });
        });
    </script>


</body>

</html>
