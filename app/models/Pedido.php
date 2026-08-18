<?php

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    private $conexion;
    private $wompi;


    public function __construct()
    {
        $database = new Database();

        $this->conexion = $database->conectar();

        $this->wompi = require __DIR__ . '/../config/wompi.php';
    }


    /**
     * Genera una referencia única para Wompi.
     */
    private function generarReferencia($pedidoId)
    {
        return 'CALZASPORT-' .
            $pedidoId .
            '-' .
            strtoupper(bin2hex(random_bytes(4)));
    }


    /**
     * Genera la firma de integridad de Wompi.
     *
     * Referencia + monto en centavos + moneda + secreto
     */
    private function generarFirmaIntegridad(
        $referencia,
        $montoCentavos
    ) {

        $cadena =

            $referencia .
            $montoCentavos .
            'COP' .
            $this->wompi['integrity_secret'];


        return hash(
            'sha256',
            $cadena
        );
    }


    /**
     * Crear cliente y pedido.
     *
     * IMPORTANTE:
     *
     * En esta etapa NO se descuenta stock.
     *
     * El stock se descontará posteriormente
     * cuando Wompi confirme el pago.
     */
    public function crearPedido($datos)
    {
        try {

            $this->conexion->beginTransaction();


            /*======================================
            1. VALIDAR PRODUCTOS
            ======================================*/

            foreach ($datos['productos'] as $producto) {

                $sqlProducto = "

                    SELECT
                        id,
                        nombre,
                        referencia,
                        precio,
                        tallas,
                        stock,
                        estado

                    FROM productos

                    WHERE id = ?

                    FOR UPDATE

                ";


                $stmtProducto =
                    $this->conexion->prepare($sqlProducto);


                $stmtProducto->execute([
                    $producto['id']
                ]);


                $productoBD =
                    $stmtProducto->fetch(PDO::FETCH_ASSOC);


                /* Producto no existe */

                if (!$productoBD) {

                    throw new Exception(
                        "El producto seleccionado ya no existe."
                    );

                }


                /*==================================
                VALIDAR TALLA
                ==================================*/

                $tallaSeleccionada =
                    trim($producto['talla'] ?? '');


                $tallasDisponibles =
                    array_map(
                        'trim',
                        explode(
                            ',',
                            $productoBD['tallas']
                        )
                    );


                if (
                    $tallaSeleccionada === '' ||
                    !in_array(
                        $tallaSeleccionada,
                        $tallasDisponibles
                    )
                ) {

                    throw new Exception(
                        "La talla seleccionada para " .
                        $productoBD['nombre'] .
                        " ya no está disponible."
                    );

                }


                /*==================================
                VALIDAR CANTIDAD
                ==================================*/

                $cantidad =
                    (int) $producto['cantidad'];


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad seleccionada no es válida."
                    );

                }


                /*==================================
                VALIDAR STOCK DISPONIBLE
                ==================================*/

                if (
                    (int) $productoBD['stock']
                    < $cantidad
                ) {

                    throw new Exception(
                        "El producto " .
                        $productoBD['nombre'] .
                        " no tiene suficiente stock. " .
                        "Disponible: " .
                        $productoBD['stock']
                    );

                }

            }


            /*======================================
            2. CREAR CLIENTE
            ======================================*/

            $sqlCliente = "

                INSERT INTO clientes
                (
                    nombre,
                    telefono,
                    correo,
                    direccion,
                    departamento,
                    ciudad,
                    barrio,
                    codigo_postal,
                    indicaciones
                )

                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)

            ";


            $stmtCliente =
                $this->conexion->prepare($sqlCliente);


            $stmtCliente->execute([

                $datos['cliente']['nombre'],

                $datos['cliente']['telefono'],

                $datos['cliente']['correo'],

                $datos['envio']['direccion'],

                $datos['envio']['departamento'],

                $datos['envio']['ciudad'],

                $datos['envio']['barrio'],

                !empty(
                    $datos['envio']['codigo_postal']
                )
                    ? $datos['envio']['codigo_postal']
                    : null,

                !empty(
                    $datos['envio']['indicaciones']
                )
                    ? $datos['envio']['indicaciones']
                    : null

            ]);


            $clienteId =
                $this->conexion->lastInsertId();


            /*======================================
            3. CALCULAR TOTAL
            ======================================*/

            $subtotal =
                (float) $datos['subtotal'];


            $costoEnvio = 0;


            $total =
                (float) $datos['total'];


            /*
             * Wompi trabaja los valores en centavos.
             *
             * Ejemplo:
             *
             * $150.000 COP
             *
             * = 15.000.000 centavos
             */

            $montoCentavos =
                (int) round(
                    $total * 100
                );


            if ($montoCentavos <= 0) {

                throw new Exception(
                    "El valor del pedido no es válido."
                );

            }


            /*======================================
            4. CREAR PEDIDO
            ======================================*/

            $sqlPedido = "

                INSERT INTO pedidos
                (
                    cliente_id,
                    subtotal,
                    costo_envio,
                    total,
                    metodo_pago,
                    referencia_pago,
                    transaccion_id,
                    estado_pago,
                    estado_pedido
                )

                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)

            ";


            /*
             * Primero creamos una referencia temporal.
             * Después de obtener el ID del pedido
             * generaremos la referencia definitiva.
             */

            $referenciaTemporal =
                'TEMP-' . uniqid();


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute([

                $clienteId,

                $subtotal,

                $costoEnvio,

                $total,

                $datos['metodo_pago'],

                $referenciaTemporal,

                null,

                'Pendiente',

                'Pendiente'

            ]);


            $pedidoId =
                $this->conexion->lastInsertId();


            /*======================================
            5. GENERAR REFERENCIA WOMPI
            ======================================*/

            $referencia =
                $this->generarReferencia(
                    $pedidoId
                );


            /*======================================
            6. GENERAR FIRMA DE INTEGRIDAD
            ======================================*/

            $firma =
                $this->generarFirmaIntegridad(
                    $referencia,
                    $montoCentavos
                );


            /*======================================
            7. ACTUALIZAR REFERENCIA DEL PEDIDO
            ======================================*/

            $sqlReferencia = "

                UPDATE pedidos

                SET referencia_pago = ?

                WHERE id = ?

            ";


            $stmtReferencia =
                $this->conexion->prepare(
                    $sqlReferencia
                );


            $stmtReferencia->execute([

                $referencia,

                $pedidoId

            ]);


            /*======================================
            8. GUARDAR DETALLE DEL PEDIDO
            ======================================*/

            $sqlDetalle = "

                INSERT INTO detalle_pedido
                (
                    pedido_id,
                    producto_id,
                    nombre_producto,
                    referencia,
                    talla,
                    cantidad,
                    precio,
                    subtotal
                )

                VALUES (?, ?, ?, ?, ?, ?, ?, ?)

            ";


            $stmtDetalle =
                $this->conexion->prepare(
                    $sqlDetalle
                );


            foreach (
                $datos['productos']
                as $producto
            ) {

                $cantidad =
                    (int) $producto['cantidad'];


                $precio =
                    (float) $producto['precio'];


                $subtotalProducto =
                    $precio * $cantidad;


                $stmtDetalle->execute([

                    $pedidoId,

                    $producto['id'],

                    $producto['nombre'],

                    $producto['referencia'] ?? 'N/A',

                    $producto['talla'] ?? 'N/A',

                    $cantidad,

                    $precio,

                    $subtotalProducto

                ]);

            }


            /*======================================
            IMPORTANTE:
            NO DESCONTAMOS STOCK AQUÍ
            ======================================*/


            /*
             * El stock se descontará solamente
             * cuando Wompi confirme:
             *
             * status = APPROVED
             *
             * mediante nuestro webhook.
             */


            /*======================================
            9. CONFIRMAR TRANSACCIÓN
            ======================================*/

            $this->conexion->commit();


            /*======================================
            10. RESPUESTA
            ======================================*/

            return [

                'success' => true,

                'pedido_id' =>
                    $pedidoId,

                'cliente_id' =>
                    $clienteId,

                'referencia' =>
                    $referencia,

                'amount_in_cents' =>
                    $montoCentavos,

                'currency' =>
                    'COP',

                'public_key' =>
                    $this->wompi['public_key'],

                'integrity_signature' =>
                    $firma

            ];


        } catch (Exception $e) {


            /*======================================
            CANCELAR TODO SI ALGO FALLA
            ======================================*/

            if (
                $this->conexion->inTransaction()
            ) {

                $this->conexion->rollBack();

            }


            return [

                'success' => false,

                'error' =>
                    $e->getMessage()

            ];

        }

    }

}