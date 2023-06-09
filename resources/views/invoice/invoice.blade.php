<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura consumidor final {{ $sale->created_at }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        p,
        label,
        span,
        table {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        .nombre {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            font-weight: bold;
        }

        .company {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            font-weight: bold;
            color: black;
        }

        .h2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16pt;
        }

        .h3 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13pt;
            font-weight: bold;
            display: block;
            text-align: center;
            padding: 2px;
            margin-bottom: -4;
        }

        .h33 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13pt;
            display: block;
            color: red;
            text-align: center;
            padding: 1px;

        }

        .h333 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            display: block;
            color: #000;
            padding: 1px;
            margin-top: -10px;
            margin-left: 68%;
        }

        #page_pdf {
            width: 95%;
            margin: 10px auto 10px auto;
        }

        #factura_head,
        #factura_cliente,
        #factura_detalle {
            width: 100%;
            margin-bottom: 1px;
        }

        .logo_factura {
            width: 10%;
        }

        .info_empresa {
            width: 40%;
            text-align: center;
        }

        .info_factura {
            width: 40%;
            position: absolute;
        }

        .info_cliente {
            width: 100%;
        }

        .datos_cliente {
            width: 100%;
        }

        .datos_cliente tr td {
            width: 50%;
        }

        .datos_cliente {
            padding: 5px 10px 0 10px;
        }

        .datos_cliente label {
            width: 70px;
            font-size: 8pt;
        }

        .datos_cliente p {
            display: inline-block;
        }


        .textright {
            text-align: right;
            font-size: 8pt;
        }

        .textleft {
            text-align: left;
            font-size: 8pt;
        }

        .textcenter {
            text-align: center;
            font-size: 8pt;
        }

        .round {
            border-radius: 10px;
            border: 1px solid #0a4661;
            overflow: hidden;
            padding-bottom: 15px;
        }

        .round p {
            padding: 0 15px;
        }

        .round1 {
            border-radius: 10px;
            border: 1px solid #0a4661;
            overflow: hidden;
            width: 100%;
            padding-bottom: 15px;
        }

        #factura_detalle {
            border-collapse: collapse;
            width: 100%;
        }

        #factura_detalle thead th {
            background: #3B96AA;
            color: #000;
            padding: 5px;

        }

        #detalle_productos tr:nth-child(even) {
            background: #ededed;
        }

        #detalle_totales span {
            font-family: Arial, Helvetica, sans-serif;
        }

        .detalle_totales {
            background-color: #0a4661;
            border-color: #0a4661;
            border: 0.2pt;
        }

        .nota {
            font-size: 8pt;
        }

        .label_gracias {
            font-family: verdana;
            font-weight: bold;
            font-style: italic;
            text-align: center;
            margin-top: 20px;
        }

        .anulada {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
        }

        .imglogo {
            width: 100px;
            height: 100px;
        }
    </style>

</head>

<body>
    <div id="page_pdf">
        <table id="factura_head">
            <tr>
                <td colspan="2" class="info_empresa">
                    <div>
                        <p class="company" style="font-size: 17px;">FARMACIA</p>
                        <p class="company" style="font-size: 17px;">"SANTA ISABEL"</p>
                        <p class="nombre">MARIA DORA HENRRIQUEZ DE MEJIA</p>
                        <div>
                            <p class="company">VENTA DE PRODUCTOS FARMACEUTICOS Y MEDICINALES</p>
                            <p style="font-size: 13px; font-style: italic; text-align: justify;">2º Calle Oriente y 4º
                                Avenida Sur, Barrio Concepción,</p>
                            <p style="font-size: 13px; font-style: italic; text-align: justify;">San Rafael Obrajuelo,
                                Depto, La Paz, Tel.: 2330-0880</p>
                        </div>
                    </div>
                </td>
                <td class="info_factura">
                    <div class="round">
                        <span class="h3">FACTURA</span>
                        <p class="textcenter">SERIE</p>
                        <span>
                            <center style="margin-bottom:5px;"></center>
                        </span>
                        <span class="h33">
                            <center><strong>N° </strong> </center>
                        </span>
                        <center class="h33" style="text-align: justify;">
                            <p>NIT: 0817-060244-001-0</p>
                            <p>N.R.C: 134916-0</p>
                            <p style="color: #000">DUI: 00770676-2</p>
                        </center>
                    </div>
                </td>
            </tr>
        </table>
        <table id="factura_cliente">
            <tr>
                <td class="info_cliente">
                    <div class="">
                        <table class="datos_cliente round">
                            <tr>
                                <td>
                                    @php
                                        $name = ( $sale->name ? $sale->name : '__________________________________' );
                                    @endphp
                                    <label>Cliente: <b>{{ $name }}</b></label>
                                </td>
                                <td>
                                    <label>Fecha: <b>{{ $sale->created_at }}</b></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @php
                                        $address = ( $sale->address ? $sale->address : '________________________________' );
                                    @endphp
                                    <label>Direccion: <b>{{ $address }}</b></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @php
                                        $duiornit = ( $sale->duiornit ? $sale->duiornit : '_________________________________' );
                                    @endphp
                                    <label>DUI o NIT: <b>{{ $duiornit }}</b></label>
                                </td>
                            </tr>
                            <td>
                                <label>Venta a cuenta de: <b>{{ $user->getFullName() }}</b></label>
                            </td>
            </tr>
        </table>
    </div>
    </td>

    </tr>
    </table>
    <div class="round">
        <table id="factura_detalle">
            <thead>
                <tr>
                    <th class="textcenter" width="10%">CANT.</th>
                    <th class="textleft">DESCRIPCIÓN</th>
                    <th class="textcenter" width="15%">PRECIO <br>UNITARIO</th>
                    <th class="textcenter" width="15%">VENTAS <br>EXENTAS</th>
                    <th class="textcenter" width="15%">VENTAS NO <br>SUJETAS</th>
                    <th class="textcenter" width="15%"> VENTAS<br> AFECTAS</th>
                </tr>
            </thead>
            <tbody id="detalle_productos">

                @foreach ($products as $product)

                    @php
                        if ($product->discount > 0) {
                            $subtotal = ((100 - $product->discount) * $product->price) / 100;
                            $subtotal = $subtotal * $product->quantity;
                        } else {
                            $subtotal = $product->quantity * $product->price;
                        }
                    @endphp

                <tr>
                    <td class="textcenter">
                        {{ $product->quantity }}
                    </td>
                    <td class="textleft">
                        {{ $product->name }}
                    </td>
                    <td class="textcenter">
                        @if ($product->discount > 0)
                            ${{ number_format(((100 - $product->discount) * $product->price) / 100, 2) }}
                        @else
                            ${{ $product->price }}
                        @endif
                    </td>
                    <td class="textcenter"></td>
                    <td class="textcenter"></td>
                    <td class="textcenter">
                        ${{number_format($subtotal ,2)}}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="6">
                        <hr class="detalle_totales">
                    </td>
                </tr>
            </tbody>
            <tfoot id="detalle_totales">
                <tr>
                    <td colspan="5" class="textright">
                        <span>SUMAS</span>
                    </td>
                    <td class="textcenter">
                        $<span>{{ $sale->total }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>(-) IVA RETENIDO</span>
                    </td>
                    <td class="textcenter">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>SUB-TOTAL</span>
                    </td>
                    <td class="textcenter">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>VENTA NO SUJETA</span>
                    </td>
                    <td class="textcenter">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>VENTA EXENTA</span>
                    </td>
                    <td class="textcenter">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>FOVIAL</span>
                    </td>
                    <td class="textcenter">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="textright">
                        <span>TOTAL</span>
                    </td>
                    <td class="textcenter">
                        $<span>{{ $sale->total }}</span>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div>
        <h4 style="margin-left:-10px;" class="label_gracias">¡Gracias por su compra!</h4>
    </div>

    </div>

</body>

</html>