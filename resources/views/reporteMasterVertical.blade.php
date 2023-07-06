<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title></title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">

    
   
    <div style="margin-top:-35px;">
        @yield('contenidoEncabesado')
    </div>
    <style>
        .girar{
                height: 60px;
                width:20px;
                padding:0px;
                
                
                vertical-align: top;
                
                padding: 0;
            }
            .girar2{
                height: 60px;
                width:20px;
                padding:0px;
                
                
                vertical-align: top;
                
                padding: 0;
            }
            .divFechasFinal
            {
                position: relative;
                top: 20px; left: -10px;
               transform: rotate(-90deg);
                -webkit-transform: rotate(-90deg);
                /* Safari/Chrome */
                -moz-transform: rotate(-90deg);
                /* Firefox */
                -o-transform: rotate(-90deg);
                /* Opera */
                -ms-transform: rotate(-90deg);
                /* IE 9 */
               
                /*width:55px;*/
                height: 20px;
                width:200%;


            }
            .divFechasFinal2
            {
                position: relative;
                top: 20px; left: -10px;
               transform: rotate(-90deg);
                -webkit-transform: rotate(-90deg);
                /* Safari/Chrome */
                -moz-transform: rotate(-90deg);
                /* Firefox */
                -o-transform: rotate(-90deg);
                /* Opera */
                -ms-transform: rotate(-90deg);
                /* IE 9 */
               
                /*width:55px;*/
                height: 20px;
                width:120%;


            }
            .divFechasFinal3
            {
                text-align: left;
                vertical-align: middle;
                position: relative;
                top: 10px; left: -10px;
               transform: rotate(-90deg);
                -webkit-transform: rotate(-90deg);
                /* Safari/Chrome */
                -moz-transform: rotate(-90deg);
                /* Firefox */
                -o-transform: rotate(-90deg);
                /* Opera */
                -ms-transform: rotate(-90deg);
                /* IE 9 */
               
                /*width:55px;*/
                height: 20px;
                width:200%;


            }
            

.box 
            {
                display: inline-flex;
                width: 100%;
                margin: 0;
                text:center;
            }
            .push { 
                display: inline;
                margin: 0;
                float:left;
                padding-left:20px;
            }
            .push2 {
                display: inline;
                text-align: center;
                padding-right:60px;
                margin: 0;
                float:right;
            }
        .cuadro_fecha{
            background-color: red;
                width: 80px;
                max-width: 10px;
                margin-left:10px;
                margin-right:-10px;
                padding: 10px;
              
                
            }
            .cuadro_fecha2{
                background-color: coral;


                transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg); /* Safari/Chrome */
            -moz-transform: rotate(-90deg); /* Firefox */
            -o-transform: rotate(-90deg); /* Opera */
            -ms-transform: rotate(-90deg); /* IE 9 */
                
            }
            
        
        .verticaltext{
            background-color: coral;
            height: 150px;
            width: 150px;
               
            transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg); /* Safari/Chrome */
            -moz-transform: rotate(-90deg); /* Firefox */
            -o-transform: rotate(-90deg); /* Opera */
            -ms-transform: rotate(-90deg); /* IE 9 */
            
        }

      
.rotar 
{        
    display: block;white-space:nowrap;
    text-align: center;
    /*width:10px;*/
    -webkit-transform: rotate(-90deg);
    -moz-transform: rotate(-90deg);

}
.divTabla
            {
               text-align:center;
            }
            
            #tablaHeaders
            {
                position: absolute;
                border:0px ;
            }
            .thheaders {
                border:0px solid;
                width:0px;
                border-spacing: 0;
                font-size: 0.9em;
                max-width: 100px;
                position: relative;
            }
    </style>
    
  
</head>

<body>
    <!-- Contenido Reporte -->
    @yield('contenidoReporte')
</body>


</html>