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
    
    .center {
        margin-top: 2em;
        text-align: center;
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
            <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-12 lineas center">
                <p class="font-weight-bold lineas">COMERCIALIZADORA FRANJHON 2013 C.A</p>
                <p class="font-weight-bold lineas">INVENTARIO DE MERCANCIA AL {{$fecha}}</p>
                <p class="lineas">(expresado en dolares americanos)</p>

            </div>
        </div>

        <br>

        <div class="row">
            <table class="table table-bordered">
                <tr>
                    <th scope="col" class="font-weight-bold text-center">Codigo</th>
                    <th scope="col" class="font-weight-bold text-center">Descripcion</th>
                    <th scope="col" class="font-weight-bold text-center">Cantidad</th>
                    <th scope="col" class="font-weight-bold text-center">Precio</th>
                </tr>
                <tbody>
                    @foreach($inventarios as $in)
                    <tr>
                        <th scope="row" class="text-center">{{$in->codigo}}</th>
                        <td class="text-center mayu">{{$in->articulo}}</td>
                        <td class="text-center">{{$in->cantidad}}</td>
                        <td class="text-center">{{number_format($in->precio, 2)}} $</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



    </div>
</body>

</html>