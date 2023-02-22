<html>
    <meta charset="UTF-8">
    <title>Código de Barras</title>
    <head>
        <style>
            @page {
                margin: 1px;
            }
        </style>
        <style>
            table {
                font-family: Helvetica, Sans-Serif;
                border-collapse: collapse;
                width: 100%;
            }
            .table-emisor {
                border: 2px solid #999797;
                padding: 5px;
            }
            .table-emisor tr .factura {
                width: 70%;
                text-align: left;
                padding-left: 5px;
                font-size: 14px;
            }
            .table-emisor tr .emisor {
                width: 30%;
                text-align: center;
            }
            .nombre{
                font-size: 18px;
                border-top:1px double #999797;
                border-bottom:1px double #999797;
                padding: 10px 5px;
            }
        </style>
    </head>
    <body>
        <div style="overflow:hidden;">
            <table class="table-emisor">
                <tr>
                    <th class="emisor">
                        <img src="{{public_path('image003.png')}}" width="45%">
                    </th>

                    <th class="factura">
                        <span style="font-size: 14px;">
                            Lacteos Barraza S.A. <br>
                            Ruta 40 Km 76, General Las Heras, Buenos Aires <br>
                            Tel: 011 - 15000005
                        </span>
                    </th>
                </tr>
                <tr>
                    <th>
                        RNPA N° {{$rnpa}}
                    </th>
                    <th>
                        RNPE N°
                    </th>
                </tr>
            </table>

            <table style="padding: 0px 5px;">
                <tr>
                    <th class="nombre">
                        {{$producto}}
                    </th>
                </tr>
            </table>

            <table style="padding: 5px">
                <tr>
                    <td style="width: 35%;text-align:center;">
                        <table>
                            <tr>
                                <td style="border: 1px solid #999797; text-align:center;padding: 5px;">Peso Neto</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #999797; text-align:center;padding: 5px; font-size:26px">
                                    {{$peso}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 35%;text-align:center;">
                        <table>
                            <tr>
                                <td style="border: 1px solid #999797; text-align:center;padding: 5px;">Unidades</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #999797; text-align:center;padding: 5px; font-size:26px">
                                    {{$unidades}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td rowspan="2" style="width: 30%;text-align:center;padding:5px;margin:0px">
                        <img src="{{$qr_url}}" style="margin:-10px" width="100%">
                    </td>
                </tr>
                <tr style="padding-top:15px">
                    @php $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
                    <td colspan="2" style="width: 60%;text-align:center;margin-top:10px;padding:0px 20px;">
                        <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($code_bar, $generatorPNG::TYPE_CODE_128, 1, 30)) }}">
                        <span style="text-align:center;margin-top:-30px;letter-spacing:3px;font-size:11px">{{$code_bar}}</span>
                    </td>
                </tr>
            </table>

            <table style="padding: 15px">
                <tr >
                    <td @if($tipo == 'C') style="width: 50%;text-align:center;" @else style="width: 100%;text-align:center;" @endif>
                        <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($art_gs1, $generatorPNG::TYPE_EAN_13, 1, 30)) }}"><br>
                        <span style="text-align:center;margin-top:-30px;letter-spacing:3px;font-size:11px">{{$art_gs1}}</span>
                    </td>

                    @if($tipo == 'C')
                        <td style="width: 50%;text-align:center;">
                            <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($caja_gs1, $generatorPNG::TYPE_CODE_128, 1, 30)) }}">
                            <span style="text-align:center;margin-top:-30px;letter-spacing:3px;font-size:11px">{{$caja_gs1}}</span>
                        </td>
                    @endif
                </tr>
            </table>
        </div>

    </body>
</html>

