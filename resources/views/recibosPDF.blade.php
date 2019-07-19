<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>
    <style>
        body {
            font-family: 'Calibri', sans-serif;
        }

        h1 {
            font-size: 30px;
        }

        h4 {
            font-size: 15px;
        }

        .center {
            text-align: center;
        }

        .header p {
            font-size: 10px;
        }

        .container {
            margin: 0;
            padding: 0;
        }

        .header {
            border: solid 1px black;
            border-radius: 10px;
            width: 100%;
            height: 35mm;
            display: block;
        }

        .header-left {
            width: 50%;
            text-align: center;
            line-height: 4px;
            float: left;
        }

        .header-middle {
            width: 10%;
            height: 15mm;
            border: 1px solid black;
            border-radius: 5px;
            background-color: white;
            position: absolute;
            margin-left: 45%;
            margin-top: 30mm;
        }

        .header-middle .type {
            position: absolute;
            margin-left: 3.5mm;
            margin-top: 3mm;
        }

        .header-right {
            width: 40%;
            border: solid 2px black;
            text-align: center;
            float: right;
            margin: -12px 10px 0px 10px;
        }

        .title {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            margin-top: -2px;
        }

        .teacher {
            font-style: italic;
            margin-top: -20px;
        }

        .date {
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            margin-top: -5px;
        }

        .condition {
            line-height: 2mm;
            margin-top: 25px;
        }

        .body {
            width: 100%;
            height: 90mm;
            padding: 2mm;
        }

        .footer {
            border: solid 1px black;
            width: 100%;
            height: 30mm;
            padding: 2mm;
        }

        .footer-left {
            width: 30%;
            line-height: 25px;
            float: left;
        }

        .footer-right {
            width: 70%;
            line-height: 25px;
            float: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <br>
            <div class="header-left">
                <h1><b>{{$configuracion->nombrefantasia}}</b></h1>
                <h4>{{$configuracion->tagline}}</h4>
                <p>{{$configuracion->domiciliocomercial}}</p>
                <p>CEL: {{$configuracion->telefono}}</p>
                <p>{{$configuracion->codigopostal}} {{$configuracion->localidad}} - {{$configuracion->provincia}}</p>
            </div>
            <div class="header-middle">
                <h1 class="type">X</h1>
            </div>
            <div class="header-right">
                <h1 class="condition"><b>Recibo</b></h1>
                <h2 class="title">Nº. 000{{$recibo->numrecibo}}</h2>
                <h5 class="date">Fecha: {{$recibo->fecha}}</h5>
            </div>
        </div>
        <br><br>
        <div class="body">
            <hr>
            <p><b>Cliente: </b>{{$cliente->razonsocial}}</p>
            <hr>
            <p><b>Domicilio: </b>{{$cliente->direccion}}</p>
            <hr>
            <p><b>Recibimos la suma de: </b>$ {{$recibo->total}}</p>

            <p><b>En Concepto de:
                    <br>
                    @foreach($pagos as $pago)
                </b>Pago Cuenta Nº. {{$pago->ctacte_id}} - Importe: $ {{$pago->importe}}</p>
            @if($cuenta->saldo == 0)
            <p><b>CUENTA CANCELADA</b></p>
            @else
            <p><b>SALDO Cuenta Nº. {{$pago->ctacte_id}} : $ {{$pago->ctacte->saldo}}</b></p>
            @endif
            @endforeach



        </div>
        <br>
        <div class="footer">
            <div class="footer-left">
                <p><b>SON: </b>$ {{$recibo->total}}</p>
            </div>
            <div class="footer-right">
                <p><b>Firma: </b>...........................................................</p>
                <p><b>Aclaración: </b>..................................................</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <br>
            <div class="header-left">
                <h1><b>{{$configuracion->nombrefantasia}}</b></h1>
                <h4>{{$configuracion->tagline}}</h4>
                <p>{{$configuracion->domiciliocomercial}}</p>
                <p>CEL: {{$configuracion->telefono}}</p>
                <p>{{$configuracion->codigopostal}} {{$configuracion->localidad}} - {{$configuracion->provincia}}</p>
            </div>
            <div class="header-middle">
                <h1 class="type">X</h1>
            </div>
            <div class="header-right">
                <h1 class="condition"><b>Recibo</b></h1>
                <h2 class="title">Nº. 000{{$recibo->numrecibo}}</h2>
                <h5 class="date">Fecha: {{$recibo->fecha}}</h5>
            </div>
        </div>
        <br><br>
        <div class="body">
            <p class="center"><b>Duplicado</b></p>
            <hr>
            <p><b>Cliente: </b>{{$cliente->razonsocial}}</p>
            <hr>
            <p><b>Domicilio: </b>{{$cliente->direccion}}</p>
            <hr>
            <p><b>Recibimos la suma de: </b>$ {{$recibo->total}}</p>

            <p><b>En Concepto de:
                    <br>
                    @foreach($pagos as $pago)
                </b>Pago Cuenta Nº. {{$pago->ctacte_id}} - Importe: $ {{$pago->importe}}</p>
            @if($cuenta->saldo == 0)
            <p><b>CUENTA CANCELADA</b></p>
            @else
            <p><b>SALDO Cuenta Nº. {{$pago->ctacte_id}} : $ {{$pago->ctacte->saldo}}</b></p>
            @endif
            @endforeach

        </div>
        <br>
        <div class="footer">
            <div class="footer-left">
                <p><b>SON: </b>$ {{$recibo->total}}</p>
            </div>
            <div class="footer-right">
                <p><b>Firma: </b>...........................................................</p>
                <p><b>Aclaración: </b>..................................................</p>
            </div>
        </div>
    </div>

</body>

</html>