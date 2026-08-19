<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../app/models/Pedido.php';


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

$contenido = file_get_contents('php://input');

$evento = json_decode($contenido, true);


/* =========================================================
   VALIDAR JSON
========================================================= */

if (!$evento) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Evento inválido.'
    ]);

    exit;
}


/* =========================================================
   VERIFICAR TIPO DE EVENTO
========================================================= */

$tipoEvento =
    $evento['event'] ?? '';


if ($tipoEvento !== 'transaction.updated') {

    echo json_encode([
        'success' => true,
        'message' => 'Evento ignorado.'
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
        'message' => 'No se encontró información de la transacción.'
    ]);

    exit;
}


/* =========================================================
   DATOS IMPORTANTES
========================================================= */

$transaccionId =
    $transaccion['id'] ?? null;


$referencia =
    $transaccion['reference'] ?? null;


$estado =
    $transaccion['status'] ?? null;


$montoCentavos =
    $transaccion['amount_in_cents'] ?? null;


/* =========================================================
   VALIDAR DATOS
========================================================= */

if (
    !$transaccionId ||
    !$referencia ||
    !$estado ||
    !$montoCentavos
) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos de la transacción.'
    ]);

    exit;
}


/* =========================================================
   LOG TEMPORAL
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


/*
 * La referencia que enviamos a Wompi
 * debe corresponder con referencia_pago
 * en nuestra tabla pedidos.
 */

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
   ACTUALIZAR TRANSACCIÓN
========================================================= */

$actualizado =
    $pedidoModel->actualizarPago(

        $pedido['id'],

        $estado,

        $transaccionId

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
   SI EL PAGO FUE APROBADO
========================================================= */

if ($estado === 'APPROVED') {

    $resultadoStock =
        $pedidoModel->descontarStockPedido(
            $pedido['id']
        );


    if (!$resultadoStock['success']) {

        http_response_code(500);

        echo json_encode([
            'success' => false,
            'message' =>
                'El pago fue recibido, pero ocurrió un error al actualizar el inventario.',
            'error' =>
                $resultadoStock['error'] ?? null
        ]);

        exit;
    }

}


/* =========================================================
   RESPUESTA A WOMPI
========================================================= */

http_response_code(200);

echo json_encode([

    'success' => true,

    'message' =>
        'Evento procesado correctamente.'

]);

exit;