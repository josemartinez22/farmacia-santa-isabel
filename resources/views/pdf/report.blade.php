<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de ventas</title>
    <link href="css/custom_pdf.css" rel="stylesheet" type="text/css">  
    <link href="css/custom_page.css" rel="stylesheet" type="text/css">  
</head>
<body>

    <section class="header" style="top: -287px;">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td colspan="2" class="text-center">
                    <span style="font-size: 25px; font-weight: bold;">Farmacia Santa Isabel</span>
                </td>
            </tr>
            <tr>
                <td width="30%" style="vertical-align: top; padding-top: 10px; position: relative;">
                    <img src="img/Logo.png" width="80%" class="invoice-logo">
                </td>
                <td width="70%" class="text-left text-company" style="vertical-align: top; padding-top: 10px;">
                    @if ($reportFrom == '' && $reportTo == '')
                        <span style="font-size: 16px;"><strong>Reporte de ventas del día</strong></span>
                    @else
                        <span style="font-size: 16px;"><strong>Reporte de ventas por fechas</strong></span>
                    @endif
                    <br>
                    @if (!empty($reportFrom) && !empty($reportTo))
                        <span style="font-size: 16px;"><strong>Fecha del reporte: {{ $reportFrom }} al {{ $reportTo }}</strong></span>
                    @else
                        <span style="font-size: 16px;"><strong>Fecha de generado el reporte: {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}</strong></span>                        
                    @endif
                    <br>
                        <span style="font-size: 14px;">Usuario: {{ $user }}</span>
                </td>
            </tr>
        </table>
    </section>

    <section style="margin-top: -110px">
        <table cellpadding="0" cellspacing="0" class="table-items" width="100%">
            <thead>
                <tr>
                    <th width="10%">FOLIO</th>
                    <th width="12%">IMPORTE</th>
                    <th width="10%">ITEMS</th>
                    <th>USUARIO</th>
                    <th width="18%">FECHA</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->id }}</td>
                        <td align="center">${{ number_format($item->total, 2) }}</td>
                        <td align="center">{{ $item->items }}</td>
                        <td align="center">{{ $item->user }}</td>
                        <td align="center">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-center">
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="1">
                        <span><strong>${{ number_format($data->sum('total'), 2) }}</strong></span>
                    </td>
                    <td class="text-center">
                        {{ $data->sum('items') }}
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </section>

    <section class="footer">
        <table class="table-items" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td width="20%">
                   Farmacia Santa Isabel
                </td>
                <td width="60%" class="text-center">
                    Sistema de facturación
                </td>
                <td width="20%" class="text-center">
                    Página <span class="pagenum"></span>
                </td>
            </tr>
        </table>
    </section>
    
</body>
</html>