<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receta</title>
    <link href="/SistemaOptica/web/reportes/css/bootstrap.css" rel="stylesheet">
    <link href="/SistemaOptica/web/reportes/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body {padding: 0 ;margin: 0 ;color:dimgrey;font-family: Arial, Helvetica, sans-serif;}
        h2 {text-align: center;font-weight: bold;}
        h2 img {vertical-align:text-bottom;}
        h3 {padding: 0 ;margin: 0 ;}
        hr {border: dimgrey 2px solid;text-align: center;}
    </style>
</head>
<body>
    <div class="container">
        <!-- ENCABEZADO -->
        <div class="row">
            <div class="col-xs-12">
                <h2><img src="opBeTransparente.png" /></h2>
                <hr>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">
                <h2>LISTADO DE PRODUCTOS</h2>
                <hr>
            </div>
        </div>
        <br><br>
        <div class="row">
            <!-- CICLO -->
            <div class="col-xs-12">
                <table class="table table-bordered" style="border: 2px solid dimgrey; text-align: center; padding:0; margin:0;">
                    <tr>
                        <td style="background-color: dimgrey;color:white;text-align: left;">PROVEEDOR : </td>
                    </tr>
                    <tr>
                        <table class="table table-bordered" style="border: 2px solid dimgrey; text-align: center;border-top:1px solid white;">
                            <tr>
                                <td style="background-color: dimgrey;color:white; text-align: center;">PRODUCTO</td>
                                <td style="background-color: dimgrey;color:white; text-align: center;">PRECIO VENTA</td>
                                <td style="background-color: dimgrey;color:white; text-align: center;">VIGENCIA</td>
                                <td style="background-color: dimgrey;color:white; text-align: center;"  >COD. BARRA</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>1 </td>
                                <td>1</td>
                                <td>1</td>
                            </tr>
                        </table>
                    </tr>
                </table>
            </div>
            <br>

        </div>
        <br>
       
        <div class="row">
            <div class="col-xs-6">
                <div>FECHA: <?=date("d/m/Y");?></div>
            </div>
            <div class="col-xs-3">
                <div></div>
            </div>
            <div class="col-xs-3" >
               
            </div>
        </div>
        <br>    
    </div>
    <script src="/SistemaOptica/web/reportes/js/jquery.min.js"></script>
    <script src="/SistemaOptica/web/reportes/js/bootstrap.js"></script>
</body>
</html>

