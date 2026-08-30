<?php

header('Content-Type: application/json; charset=utf-8');


/* =========================================================
   CONFIGURACIÓN
========================================================= */

$wompiConfig =
    require_once __DIR__ . '/../app/config/wompi.php';


require_once __DIR__ . '/../app/models/Pedido.php';


/* =========================================================
   SOLO POST
========================================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);

    exit;
}


/* =========================================================
   RECIBIR EVENTO
========================================================= */

$contenido =
    file_get_contents('php://input');


$evento =
    json_decode($contenido, true);


/* =========================================================
   VALIDAR JSON
========================================================= */

if (!is_array($evento)) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Evento inválido.'
    ]);

    exit;
}


/* =========================================================
   VALIDAR FIRMA DE WOMPI
========================================================= */

$properties =
    $evento['signature']['properties'] ?? [];


$checksumWompi =
    $evento['signature']['checksum'] ?? '';


$timestamp =
    $evento['timestamp'] ?? null;


if (
    empty($properties) ||
    empty($checksumWompi) ||
    !$timestamp
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' =>
            'El evento no contiene información de seguridad válida.'
    ]);

    exit;
}


/* =========================================================
   CONSTRUIR CADENA DE FIRMA
========================================================= */

$cadenaFirma = '';


foreach ($properties as $property) {

    $partes =
        explode('.', $property);


    $valor =
        $evento['data'] ?? [];


    foreach ($partes as $parte) {

        if (
            !is_array($valor) ||
            !array_key_exists($parte, $valor)
        ) {

            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' =>
                    'No fue posible validar la firma del evento.'
            ]);

            exit;
        }


        $valor =
            $valor[$parte];

    }


    $cadenaFirma .=
        (string) $valor;

}


/* =========================================================
   AGREGAR TIMESTAMP
========================================================= */

$cadenaFirma .=
    (string) $timestamp;


/* =========================================================
   AGREGAR SECRETO DE EVENTOS
========================================================= */

$cadenaFirma .=
    $wompiConfig['events_secret'];


/* =========================================================
   GENERAR CHECKSUM
========================================================= */

$checksumCalculado =
    hash(
        'sha256',
        $cadenaFirma
    );


/* =========================================================
   COMPARAR CHECKSUM
========================================================= */

if (
    !hash_equals(
        strtolower($checksumWompi),
        strtolower($checksumCalculado)
    )
) {

    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' =>
            'Firma de evento no válida.'
    ]);

    exit;
}


/* =========================================================
   EVENTO AUTÉNTICO
========================================================= */

$tipoEvento =
    $evento['event'] ?? '';


/* =========================================================
   IGNORAR EVENTOS QUE NO NECESITAMOS
========================================================= */

if (
    $tipoEvento !== 'transaction.updated'
) {

    http_response_code(200);

    echo json_encode([
        'success' => true,
        'message' =>
            'Evento recibido e ignorado.'
    ]);

    exit;
}


/* =========================================================
   OBTENER TRANSACCIÓN
========================================================= */

$transaccion =
    $evento['data']['transaction'] ?? null;


if (!$transaccion) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' =>
            'No se encontró la transacción.'
    ]);

    exit;
}


/* =========================================================
   DATOS DE LA TRANSACCIÓN
========================================================= */

$transaccionId =
    $transaccion['id'] ?? null;


$referencia =
    $transaccion['reference'] ?? null;


$estado =
    $transaccion['status'] ?? null;


$montoCentavos =
    $transaccion['amount_in_cents'] ?? null;


if (
    !$transaccionId ||
    !$referencia ||
    !$estado ||
    $montoCentavos === null
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' =>
            'Faltan datos de la transacción.'
    ]);

    exit;
}


/* =========================================================
   LOG DEL EVENTO
========================================================= */

$log = [

    'fecha' =>
        date('Y-m-d H:i:s'),

    'evento' =>
        $tipoEvento,

    'transaccion_id' =>
        $transaccionId,

    'referencia' =>
        $referencia,

    'estado' =>
        $estado,

    'monto_centavos' =>
        $montoCentavos

];


file_put_contents(

    __DIR__ . '/wompi-events.log',

    json_encode(
        $log,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_UNICODE
    ) .
    PHP_EOL .
    PHP_EOL,

    FILE_APPEND

);


/* =========================================================
   BUSCAR PEDIDO
========================================================= */

$pedidoModel =
    new Pedido();


$pedido =
    $pedidoModel->obtenerPorReferencia(
        $referencia
    );


if (!$pedido) {

    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' =>
            'No se encontró un pedido asociado a la referencia.'
    ]);

    exit;
}


/* =========================================================
   VALIDAR MONTO
========================================================= */

$totalPedidoCentavos =
    (int) round(
        ((float) $pedido['total']) * 100
    );


if (
    $totalPedidoCentavos !==
    (int) $montoCentavos
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' =>
            'El monto de la transacción no coincide con el pedido.'
    ]);

    exit;
}


/* =========================================================
   CONVERTIR ESTADO WOMPI
   A ESTADO DE CALZASPORT
========================================================= */

if ($estado === 'APPROVED') {

    $estadoPagoCalzaSport =
        'Pagado';

} elseif (
    $estado === 'DECLINED' ||
    $estado === 'ERROR' ||
    $estado === 'VOIDED'
) {

    $estadoPagoCalzaSport =
        'Rechazado';

} else {

    $estadoPagoCalzaSport =
        'Pendiente';

}


/* =========================================================
   ACTUALIZAR PAGO
========================================================= */

$actualizado =
    $pedidoModel->actualizarPago(

        $pedido['id'],

        $estadoPagoCalzaSport,

        $transaccionId

    );


/* =========================================================
   DEBUG TEMPORAL
========================================================= */

file_put_contents(

    __DIR__ . '/wompi-debug.log',

    json_encode([

        'fecha' =>
            date('Y-m-d H:i:s'),

        'pedido_id' =>
            $pedido['id'],

        'referencia' =>
            $referencia,

        'estado_wompi' =>
            $estado,

        'estado_calzasport' =>
            $estadoPagoCalzaSport,

        'transaccion_id' =>
            $transaccionId,

        'actualizado' =>
            $actualizado

    ],
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_UNICODE
    ) .
    PHP_EOL .
    PHP_EOL,

    FILE_APPEND

);



$pedidoDespues =
    $pedidoModel->obtenerPorId(
        $pedido['id']
    );


file_put_contents(

    __DIR__ . '/wompi-debug.log',

    json_encode([

        'VERIFICACION_DESPUES_DE_ACTUALIZAR' => [

            'pedido_id' =>
                $pedidoDespues['id'] ?? null,

            'estado_pago' =>
                $pedidoDespues['estado_pago'] ?? null,

            'estado_pedido' =>
                $pedidoDespues['estado_pedido'] ?? null,

            'transaccion_id' =>
                $pedidoDespues['transaccion_id'] ?? null

        ]

    ],
    JSON_PRETTY_PRINT |
    JSON_UNESCAPED_UNICODE
    ) .
    PHP_EOL .
    PHP_EOL,

    FILE_APPEND

);


if (!$actualizado) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' =>
            'No fue posible actualizar el pedido.'
    ]);

    exit;
}


/* =========================================================
   PAGO APROBADO
========================================================= */

if ($estado === 'APPROVED') {

    $resultadoStock =
        $pedidoModel->descontarStockPedido(
            $pedido['id']
        );


    /* =====================================================
       DEBUG STOCK
    ===================================================== */

    file_put_contents(

        __DIR__ . '/wompi-stock-debug.log',

        json_encode(

            [
                'fecha' =>
                    date('Y-m-d H:i:s'),

                'pedido_id' =>
                    $pedido['id'],

                'estado_wompi' =>
                    $estado,

                'estado_pago_antes_stock' =>
                    $pedido['estado_pago'],

                'resultado_stock' =>
                    $resultadoStock

            ],

            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE

        ) .
        PHP_EOL .
        PHP_EOL,

        FILE_APPEND

    );


    /* =====================================================
       VALIDAR RESULTADO
    ===================================================== */

    if (
        !isset($resultadoStock['success']) ||
        !$resultadoStock['success']
    ) {

        http_response_code(500);

        echo json_encode([

            'success' => false,

            'message' =>
                'El pago fue recibido, pero ocurrió un error al actualizar el inventario.',

            'error' =>
                $resultadoStock['error'] ?? 'Error desconocido.'

        ]);

        exit;

    }

}


/* =========================================================
   RESPUESTA EXITOSA
========================================================= */

http_response_code(200);

echo json_encode([

    'success' => true,

    'message' =>
        'Evento procesado correctamente.',

    'pedido_id' =>
        $pedido['id'],

    'estado_wompi' =>
        $estado,

    'estado_pago' =>
        $estadoPagoCalzaSport

]);

exit;