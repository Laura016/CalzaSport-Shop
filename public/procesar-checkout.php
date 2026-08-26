<?php

header('Content-Type: application/json; charset=utf-8');


require_once __DIR__ . '/../app/controllers/CheckoutController.php';


/*======================================
CARGAR CONFIGURACIÓN WOMPI
======================================*/

$wompiConfig =
    require __DIR__ . '/../app/config/wompi.php';


/*======================================
SOLO POST
======================================*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([

        'success' => false,

        'message' => 'Método no permitido.'

    ]);

    exit;
}


/*======================================
RECIBIR JSON
======================================*/

$contenido =
    file_get_contents('php://input');


$datos =
    json_decode($contenido, true);


/*======================================
VALIDAR JSON
======================================*/

if (!$datos) {

    http_response_code(400);

    echo json_encode([

        'success' => false,

        'message' => 'Los datos enviados no son válidos.'

    ]);

    exit;
}


/*======================================
VALIDACIONES BÁSICAS
======================================*/

if (

    empty($datos['cliente']) ||

    empty($datos['envio']) ||

    empty($datos['productos']) ||

    empty($datos['metodo_pago'])

) {

    http_response_code(400);

    echo json_encode([

        'success' => false,

        'message' => 'Faltan datos obligatorios.'

    ]);

    exit;
}


/*======================================
VALIDAR CONFIGURACIÓN WOMPI
======================================*/

if (
    empty($wompiConfig['public_key']) ||
    empty($wompiConfig['integrity_secret'])
) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>
            'La configuración de Wompi está incompleta.'

    ]);

    exit;
}


/*======================================
VALIDAR TOTAL
======================================*/

$total =
    (float) ($datos['total'] ?? 0);


if ($total <= 0) {

    http_response_code(400);

    echo json_encode([

        'success' => false,

        'message' =>
            'El total del pedido no es válido.'

    ]);

    exit;
}


/*======================================
GENERAR REFERENCIA ÚNICA
======================================*/

$referenciaPago =
    'CALZA-' .
    date('YmdHis') .
    '-' .
    random_int(1000, 9999);


/*======================================
AGREGAR REFERENCIA AL PEDIDO
======================================*/

$datos['referencia_pago'] =
    $referenciaPago;


/*======================================
CREAR PEDIDO
======================================*/

$controller =
    new CheckoutController();


$resultado =
    $controller->registrarPedido($datos);


/*======================================
SI FALLA EL PEDIDO
======================================*/

if (!$resultado['success']) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>
            'No fue posible registrar el pedido.',

        'error' =>
            $resultado['error'] ?? null

    ]);

    exit;
}


/*======================================
DATOS PARA WOMPI
======================================*/

/*
 * Wompi trabaja el monto en centavos.
 *
 * Ejemplo:
 *
 * $100.000 COP
 *
 * se envía como:
 *
 * 10000000
 */

$montoCentavos =
    (int) round($total * 100);


$moneda =
    'COP';


/*======================================
GENERAR FIRMA DE INTEGRIDAD
======================================*/

$cadenaFirma =
    $referenciaPago .
    $montoCentavos .
    $moneda .
    $wompiConfig['integrity_secret'];


$firmaIntegridad =
    hash(
        'sha256',
        $cadenaFirma
    );


/*======================================
RESPUESTA
======================================*/

echo json_encode([

    'success' => true,

    'message' =>
        'Pedido creado correctamente.',

    'pedido_id' =>
        $resultado['pedido_id'],

    'wompi' => [

        'public_key' =>
            $wompiConfig['public_key'],

        'currency' =>
            $moneda,

        'amount_in_cents' =>
            $montoCentavos,

        'reference' =>
            $referenciaPago,

        'signature_integrity' =>
            $firmaIntegridad

    ]

]);