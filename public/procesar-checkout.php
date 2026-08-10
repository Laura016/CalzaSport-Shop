<?php

header('Content-Type: application/json; charset=utf-8');


require_once '../app/controllers/CheckoutController.php';


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

$contenido = file_get_contents('php://input');

$datos = json_decode($contenido, true);


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
REGISTRAR PEDIDO
======================================*/

$controller = new CheckoutController();

$resultado =
    $controller->registrarPedido($datos);


/*======================================
RESPUESTA
======================================*/

if ($resultado['success']) {

    echo json_encode([

        'success' => true,

        'message' =>
            'Pedido registrado correctamente.',

        'pedido_id' =>
            $resultado['pedido_id']

    ]);

} else {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'message' =>
            'No fue posible registrar el pedido.',

        'error' =>
            $resultado['error']

    ]);

}