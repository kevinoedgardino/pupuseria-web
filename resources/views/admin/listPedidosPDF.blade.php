<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pedidos PDF</title>
    <link rel="stylesheet" href="css/pdfstyles.css">
</head>
<body>
    <header>
        <div class="header_logo">
            <img src="img/logo-pupuseria-web.png" id="logo" alt="Logo">
        </div>
        <h3>PupuseriaWEB</h3>
        <h4>Reporte general de los pedidos</h4>
    </header>
    <div class="container">
        <table id="ped">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->cliente }}</td>
                        <td>{{ $pedido->telefono }}</td>
                        <td>{{ $pedido->producto }}</td>
                        <td>{{ $pedido->cantidad }}</td>
                        <td>{{ $pedido->estado }}</td>
                        <td>{{ $pedido->total }}</td>
                        <td>{{ $pedido->fecha }}</td>
                        <td>{{ $pedido->hora }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <footer>
            <?php $year = date("Y"); $date = date("d-m-y"); $hour = date("h:i:s") ?>
            <span id="year">PupuseriaWEB {{ $year }}</span>
            <br>
            <span>Reporte generado el {{ $date }} a las {{ $hour }}</span>
        </footer>
        <div class="page-break"></div>
    </div>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 800, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ')
        }
    </script>
</body>
</html>