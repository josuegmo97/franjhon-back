<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota de Entrega</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap');

    html,
    body {
        background-color: #ffffff;
        font-family: 'Open Sans', sans-serif;
        //font-family: "Times New Roman", Times, serif;
        font-weight: 400;
        height: 100vh;
    }
    
    .font-weight-bold {
        font-weight: bold;
    }
    
    .float-right {
        float: right!important;
    }
    
    .border {
        border: 2px solid #dedede;
        padding: 2em;
    }
    
    .mayu {
        text-transform: uppercase;
    }
    /*.lineas {
        line-height: 0.4rem;
    }
    
    p,
    span {
        line-height: 0.4rem;
    }*/
</style>

<body>


    <div class="container mt-4">

        <div class="row lineas">
            <div class="col-9 col-xs-9 col-sm-9 col-md-9 col-lg-9 lineas">
                <p class="font-weight-bold lineas">COMERCIALIZADORA FRANJHON 2013 C.A</p>
                <p>AV SAN MARTIN PRIMER CALLE 2 TRANSVERSAL</p>
                <p>RIF J402405642</p>
                <p>02124617812 - 04120904122</p>
                <p>ALMACEN 02</p>
            </div>
            <div class="col-3 col-xs-3 col-sm-3 col-md-3 col-lg-3 lineas">
                <p class="font-weight-bold lineas">FACTURA Nro.000091</p>
                <p class="font-weight-bold lineas">CONTADO</p>
                <p>FECHA: {{$fecha}}</p>
                <p>VENCE: {{$fecha}}</p>
            </div>


        </div>

        <br>


        <div class=" lineas row">
            <div class=" lineas col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <div class=" lineas border p-4">
                    <p><span class=" lineas font-weight-bold mt-5">Cliente:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="mayu"> {{ $cliente}}</span></p>
                    <p><span class=" lineas font-weight-bold">Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="mayu"> {{ $nombre}}</span></p>
                    <p><span class=" lineas font-weight-bold">Direccion:&nbsp;&nbsp;</span> <span class="mayu"> {{ $direccion}}</span></p>
                    <p><span class=" lineas font-weight-bold">Telefono:&nbsp;&nbsp;&nbsp;</span> <span class="mayu"> {{ $telefono}}</span></p>
                </div>
            </div>

            <div class=" lineas col-4 col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <div class=" lineas border p-4" style="    height: 180px;">
                    <p><span class=" lineas font-weight-bold">Cedula:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="mayu"> {{ $cedula }}</span></p>
                    <p><span class=" lineas font-weight-bold">RIF:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="mayu">{{$rif}}</span></p>
                    </p>
                    <p><span class=" lineas font-weight-bold">NIT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="mayu">{{$nit}}</span></p>
                    <br>
                </div>
            </div>
        </div>

        <br>
        <br>

        <div class="row">
            <table class="table">
                <tr>
                    <th scope="col" class="font-weight-bold text-center">Codigo</th>
                    <th scope="col" class="font-weight-bold text-center">Descripcion</th>
                    <th scope="col" class="font-weight-bold text-center">Cantidad</th>
                    <th scope="col" class="font-weight-bold text-center">Precio</th>
                    <th scope="col" class="font-weight-bold text-center">Descuento</th>
                    <th scope="col" class="font-weight-bold text-center">Total</th>
                </tr>
                <tbody>
                    @foreach($ventas as $v)
                    <tr>
                        <th scope="row" class="text-center">{{$v->articulo_id}}</th>
                        <td class="text-center mayu">{{$v->articulo}}</td>
                        <td class="text-center">{{$v->cantidad}}</td>
                        <td class="text-center">{{number_format($v->cantidad, 2)}}</td>
                        <td class="text-center">{{number_format($v->descuento, 2)}}</td>
                        <td class="text-center">{{number_format($v->total, 2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <br>
        <br>


        <div class="row">
            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="border p-4">
                    <p style="line-height: 1.4!important;"><span class="font-weight-bold">Observaciones:&nbsp;&nbsp;&nbsp;</span> <span class="mayu">{{$observaciones}}</span>
                    </p>
                </div>
            </div>

            <div class="col-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="border p-4">
                    <p><span class="font-weight-bold">Total Factura:</span > <span class="float-right mr-5 font-weight-bold">{{$total_factura}}</span></p>
                    <p><span>Total Neto:</span> <span class="float-right mr-5">{{$total_factura}}</span></p>
                    <p><span class="font-weight-bold">I.V.A:</span > <span class="float-right mr-5">{{$iva}}</span></p>
                    <p><span class="font-weight-bold">Total Operacion:</span> <span class="float-right mr-5 font-weight-bold">{{$total_operacion}}</span></p>
                    <p><span>Imp. Retenido:</span> <span class="float-right mr-5">0.00</span></p>
                    <p><span>Total Cancelado:</span> <span class="float-right mr-5">{{$total_operacion}}</span></p>
                    <p><span>Saldo Facturado:</span> <span class="float-right mr-5">0.00</span></p>
                </div>
            </div>
        </div>



    </div>
</body>

</html>