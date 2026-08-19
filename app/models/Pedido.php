<?php

require_once __DIR__ . '/../config/database.php';

class Pedido
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }


    /**
     * =========================================================
     * CREAR PEDIDO
     * =========================================================
     *
     * IMPORTANTE:
     *
     * Esta función SOLO crea el pedido.
     *
     * NO descuenta stock.
     *
     * El stock será descontado posteriormente cuando Wompi
     * confirme que el pago fue aprobado.
     *
     */
    public function crearPedido($datos)
    {
        try {

            $this->conexion->beginTransaction();


            /* ==================================================
               1. VALIDAR PRODUCTOS
            ================================================== */

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


                /* ==============================================
                   PRODUCTO NO EXISTE
                ============================================== */

                if (!$productoBD) {

                    throw new Exception(
                        "El producto seleccionado ya no existe."
                    );

                }


                /* ==============================================
                   VALIDAR ESTADO
                ============================================== */

                if (
                    isset($productoBD['estado']) &&
                    strtolower(trim($productoBD['estado'])) === 'agotado'
                ) {

                    throw new Exception(
                        "El producto " .
                        $productoBD['nombre'] .
                        " se encuentra agotado."
                    );

                }


                /* ==============================================
                   VALIDAR TALLA
                ============================================== */

                $tallaSeleccionada =
                    trim($producto['talla'] ?? '');


                $tallasDisponibles =
                    array_map(
                        'trim',
                        explode(',', $productoBD['tallas'])
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


                /* ==============================================
                   VALIDAR CANTIDAD
                ============================================== */

                $cantidad =
                    (int) $producto['cantidad'];


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad seleccionada no es válida."
                    );

                }


                /* ==============================================
                   VALIDAR STOCK
                ============================================== */

                if ($productoBD['stock'] < $cantidad) {

                    throw new Exception(
                        "No hay suficiente stock para " .
                        $productoBD['nombre'] .
                        ". Disponible: " .
                        $productoBD['stock']
                    );

                }

            }


            /* ==================================================
               2. CREAR CLIENTE
            ================================================== */

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

                !empty($datos['envio']['codigo_postal'])
                ? $datos['envio']['codigo_postal']
                : null,

                !empty($datos['envio']['indicaciones'])
                ? $datos['envio']['indicaciones']
                : null

            ]);


            $clienteId =
                $this->conexion->lastInsertId();


            /* ==================================================
               3. CREAR PEDIDO
            ================================================== */

            $sqlPedido = "
                INSERT INTO pedidos
                (
                    cliente_id,
                    subtotal,
                    costo_envio,
                    total,
                    metodo_pago,
                    referencia_pago,
                    estado_pago,
                    estado_pedido
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ";


            $costoEnvio = 0;


            /*
             * Si Wompi ya generó una referencia,
             * la guardamos.
             *
             * Si todavía no existe, queda NULL.
             */

            $referenciaPago =
                $datos['referencia_pago'] ?? null;


            $estadoPago =
                'Pendiente';


            $estadoPedido =
                'Pendiente';


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute([

                $clienteId,

                $datos['subtotal'],

                $costoEnvio,

                $datos['total'],

                $datos['metodo_pago'],

                $referenciaPago,

                $estadoPago,

                $estadoPedido

            ]);


            $pedidoId =
                $this->conexion->lastInsertId();


            /* ==================================================
               4. CREAR DETALLE DEL PEDIDO
            ================================================== */

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
                $this->conexion->prepare($sqlDetalle);


            foreach ($datos['productos'] as $producto) {

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


            /* ==================================================
               5. CONFIRMAR TRANSACCIÓN
            ================================================== */

            $this->conexion->commit();


            return [

                'success' => true,

                'pedido_id' => $pedidoId,

                'cliente_id' => $clienteId

            ];


        } catch (Exception $e) {


            /* ==================================================
               CANCELAR TODO SI ALGO FALLA
            ================================================== */

            if ($this->conexion->inTransaction()) {

                $this->conexion->rollBack();

            }


            return [

                'success' => false,

                'error' => $e->getMessage()

            ];

        }

    }


    /**
     * =========================================================
     * ACTUALIZAR PAGO
     * =========================================================
     *
     * Esta función será utilizada cuando Wompi confirme
     * el resultado de la transacción.
     *
     */
    public function actualizarPago(
        $pedidoId,
        $estadoPago,
        $transaccionId = null
    ) {

        $sql = "
            UPDATE pedidos
            SET
                estado_pago = ?,
                transaccion_id = ?
            WHERE id = ?
        ";


        $stmt =
            $this->conexion->prepare($sql);


        return $stmt->execute([

            $estadoPago,

            $transaccionId,

            $pedidoId

        ]);

    }


    /**
     * =========================================================
     * OBTENER PEDIDO
     * =========================================================
     */

    public function obtenerPorId($pedidoId)
    {

        $sql = "
            SELECT *
            FROM pedidos
            WHERE id = ?
        ";


        $stmt =
            $this->conexion->prepare($sql);


        $stmt->execute([

            $pedidoId

        ]);


        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * =========================================================
     * OBTENER PEDIDO POR REFERENCIA DE PAGO
     * =========================================================
     */

    public function obtenerPorReferencia($referencia)
    {
        $sql = "
        SELECT *
        FROM pedidos
        WHERE referencia_pago = ?
        LIMIT 1
    ";

        $stmt =
            $this->conexion->prepare($sql);

        $stmt->execute([
            $referencia
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * =========================================================
     * OBTENER DETALLES DEL PEDIDO
     * =========================================================
     */

    public function obtenerDetalles($pedidoId)
    {

        $sql = "
            SELECT *
            FROM detalle_pedido
            WHERE pedido_id = ?
        ";


        $stmt =
            $this->conexion->prepare($sql);


        $stmt->execute([

            $pedidoId

        ]);


        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    /**
     * =========================================================
     * DESCONTAR STOCK
     * =========================================================
     *
     * ESTA FUNCIÓN NO SE EJECUTA AL CREAR EL PEDIDO.
     *
     * Será utilizada posteriormente únicamente cuando
     * Wompi confirme el pago como APPROVED.
     *
     */
    public function descontarStockPedido($pedidoId)
    {

        try {

            $this->conexion->beginTransaction();


            /* ==============================================
               OBTENER DETALLES
            ============================================== */

            $sqlDetalles = "
                SELECT
                    producto_id,
                    cantidad
                FROM detalle_pedido
                WHERE pedido_id = ?
            ";


            $stmtDetalles =
                $this->conexion->prepare($sqlDetalles);


            $stmtDetalles->execute([

                $pedidoId

            ]);


            $detalles =
                $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);


            if (!$detalles) {

                throw new Exception(
                    "El pedido no tiene productos."
                );

            }


            /* ==============================================
               VERIFICAR QUE EL PEDIDO NO HAYA SIDO PROCESADO
            ============================================== */

            $sqlPedido = "
                SELECT
                    estado_pago,
                    estado_pedido
                FROM pedidos
                WHERE id = ?
                FOR UPDATE
            ";


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute([

                $pedidoId

            ]);


            $pedido =
                $stmtPedido->fetch(PDO::FETCH_ASSOC);


            if (!$pedido) {

                throw new Exception(
                    "El pedido no existe."
                );

            }


            /*
             * Si el pedido ya fue confirmado,
             * NO volvemos a descontar stock.
             */

            if (
                strtolower($pedido['estado_pedido']) ===
                'confirmado'
            ) {

                $this->conexion->commit();

                return [

                    'success' => true,

                    'message' =>
                        'El stock de este pedido ya había sido descontado.'

                ];

            }


            /* ==============================================
               DESCONTAR STOCK
            ============================================== */

            $sqlStock = "
                UPDATE productos
                SET
                    stock = stock - ?,
                    estado =
                        CASE
                            WHEN stock - ? <= 0
                            THEN 'Agotado'
                            ELSE 'Disponible'
                        END
                WHERE id = ?
                AND stock >= ?
            ";


            $stmtStock =
                $this->conexion->prepare($sqlStock);


            foreach ($detalles as $detalle) {

                $cantidad =
                    (int) $detalle['cantidad'];


                $productoId =
                    (int) $detalle['producto_id'];


                $stmtStock->execute([

                    $cantidad,

                    $cantidad,

                    $productoId,

                    $cantidad

                ]);


                if ($stmtStock->rowCount() !== 1) {

                    throw new Exception(
                        "No hay suficiente stock para el producto ID " .
                        $productoId
                    );

                }

            }


            /* ==============================================
               ACTUALIZAR ESTADO DEL PEDIDO
            ============================================== */

            $sqlEstado = "
                UPDATE pedidos
                SET
                    estado_pago = 'Aprobado',
                    estado_pedido = 'Confirmado'
                WHERE id = ?
            ";


            $stmtEstado =
                $this->conexion->prepare($sqlEstado);


            $stmtEstado->execute([

                $pedidoId

            ]);


            /* ==============================================
               CONFIRMAR
            ============================================== */

            $this->conexion->commit();


            return [

                'success' => true,

                'message' =>
                    'Pago confirmado y stock actualizado.'

            ];


        } catch (Exception $e) {


            if ($this->conexion->inTransaction()) {

                $this->conexion->rollBack();

            }


            return [

                'success' => false,

                'error' => $e->getMessage()

            ];

        }

    }

}