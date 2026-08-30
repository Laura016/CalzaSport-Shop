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
     * Crea el pedido y sus detalles.
     *
     * IMPORTANTE:
     * NO descuenta stock.
     *
     * El stock se descuenta únicamente cuando Wompi
     * confirma el pago como APPROVED.
     *
     */
    public function crearPedido($datos)
    {
        try {

            $this->conexion->beginTransaction();


            /* ==================================================
               1. VALIDAR Y PREPARAR PRODUCTOS
            ================================================== */

            $productosProcesados = [];

            $subtotalReal = 0;


            foreach ($datos['productos'] as $producto) {

                $productoId =
                    (int) ($producto['id'] ?? 0);


                if ($productoId <= 0) {

                    throw new Exception(
                        "El producto seleccionado no es válido."
                    );

                }


                /* ==============================================
                   OBTENER PRODUCTO
                ============================================== */

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
                    $productoId
                ]);


                $productoBD =
                    $stmtProducto->fetch(PDO::FETCH_ASSOC);


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
                        $tallasDisponibles,
                        true
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
                    (int) ($producto['cantidad'] ?? 0);


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad seleccionada para " .
                        $productoBD['nombre'] .
                        " no es válida."
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


                /* ==============================================
                   PRECIO REAL
                ============================================== */

                $precioReal =
                    (float) $productoBD['precio'];


                if ($precioReal < 0) {

                    throw new Exception(
                        "El precio del producto " .
                        $productoBD['nombre'] .
                        " no es válido."
                    );

                }


                /* ==============================================
                   SUBTOTAL
                ============================================== */

                $subtotalProducto =
                    $precioReal * $cantidad;


                $subtotalReal +=
                    $subtotalProducto;


                /* ==============================================
                   GUARDAR PRODUCTO PROCESADO
                ============================================== */

                $productosProcesados[] = [

                    'id' =>
                        (int) $productoBD['id'],

                    'nombre' =>
                        $productoBD['nombre'],

                    'referencia' =>
                        $productoBD['referencia'],

                    'talla' =>
                        $tallaSeleccionada,

                    'cantidad' =>
                        $cantidad,

                    'precio' =>
                        $precioReal,

                    'subtotal' =>
                        $subtotalProducto

                ];

            }


            /* ==================================================
               VALIDAR PRODUCTOS
            ================================================== */

            if (empty($productosProcesados)) {

                throw new Exception(
                    "El pedido no contiene productos."
                );

            }


            /* ==================================================
               2. COSTO DE ENVÍO
            ================================================== */

            $costoEnvio = 0;


            /* ==================================================
               3. TOTAL REAL
            ================================================== */

            $totalReal =
                $subtotalReal + $costoEnvio;


            /* ==================================================
               4. CREAR CLIENTE
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
               5. CREAR PEDIDO
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


            $referenciaPago =
                $datos['referencia_pago'] ?? null;


            /*
             * Estado inicial.
             *
             * Debe coincidir con el ENUM de la BD.
             */

            $estadoPago =
                'Pendiente';


            $estadoPedido =
                'Pendiente';


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute([

                $clienteId,

                $subtotalReal,

                $costoEnvio,

                $totalReal,

                $datos['metodo_pago'],

                $referenciaPago,

                $estadoPago,

                $estadoPedido

            ]);


            $pedidoId =
                $this->conexion->lastInsertId();


            /* ==================================================
               6. CREAR DETALLE DEL PEDIDO
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


            foreach ($productosProcesados as $producto) {

                $stmtDetalle->execute([

                    $pedidoId,

                    $producto['id'],

                    $producto['nombre'],

                    $producto['referencia'] ?? 'N/A',

                    $producto['talla'],

                    $producto['cantidad'],

                    $producto['precio'],

                    $producto['subtotal']

                ]);

            }


            /* ==================================================
               7. CONFIRMAR TRANSACCIÓN
            ================================================== */

            $this->conexion->commit();


            /* ==================================================
               8. RESPUESTA
            ================================================== */

            return [

                'success' => true,

                'pedido_id' => $pedidoId,

                'cliente_id' => $clienteId,

                'subtotal' => $subtotalReal,

                'costo_envio' => $costoEnvio,

                'total' => $totalReal

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


    /**
     * =========================================================
     * ACTUALIZAR PAGO
     * =========================================================
     *
     * Convierte los estados de Wompi a los estados
     * permitidos por nuestra base de datos.
     *
     * Wompi:
     *
     * APPROVED
     * DECLINED
     * ERROR
     * VOIDED
     *
     * BD:
     *
     * Pagado
     * Rechazado
     * Pendiente
     *
     */
    /**
     * =========================================================
     * ACTUALIZAR PAGO
     * =========================================================
     *
     * Convierte los estados de Wompi a los estados utilizados
     * por nuestra base de datos.
     *
     * Wompi:
     *
     * APPROVED  -> Pagado
     * DECLINED  -> Rechazado
     * ERROR     -> Rechazado
     * VOIDED    -> Rechazado
     * PENDING   -> Pendiente
     *
     */
    public function actualizarPago(
        $pedidoId,
        $estadoPago,
        $transaccionId = null
    ) {

        try {

            /* ==================================================
               NORMALIZAR ESTADO DE PAGO
            ================================================== */

            switch (strtoupper(trim($estadoPago))) {

                case 'APPROVED':
                case 'PAGADO':

                    $estadoBD = 'Pagado';

                    break;


                case 'DECLINED':
                case 'ERROR':
                case 'VOIDED':
                case 'RECHAZADO':

                    $estadoBD = 'Rechazado';

                    break;


                case 'PENDING':
                case 'PENDIENTE':

                    $estadoBD = 'Pendiente';

                    break;


                default:

                    $estadoBD = 'Pendiente';

                    break;

            }


            /* ==================================================
               ACTUALIZAR PEDIDO
            ================================================== */

            $sql = "
            UPDATE pedidos
            SET
                estado_pago = ?,
                transaccion_id = ?
            WHERE id = ?
        ";


            $stmt =
                $this->conexion->prepare($sql);


            $stmt->execute([

                $estadoBD,

                $transaccionId,

                $pedidoId

            ]);


            /* ==================================================
               VERIFICAR QUE EL PEDIDO EXISTE
            ================================================== */

            $sqlVerificar = "
            SELECT
                id,
                estado_pago,
                estado_pedido,
                transaccion_id
            FROM pedidos
            WHERE id = ?
            LIMIT 1
        ";


            $stmtVerificar =
                $this->conexion->prepare($sqlVerificar);


            $stmtVerificar->execute([

                $pedidoId

            ]);


            $pedido =
                $stmtVerificar->fetch(PDO::FETCH_ASSOC);


            if (!$pedido) {

                error_log(
                    "ERROR actualizarPago(): no existe el pedido ID " .
                    $pedidoId
                );

                return false;

            }


            /* ==================================================
               VERIFICAR ESTADO GUARDADO
            ================================================== */

            if (
                $pedido['estado_pago'] !== $estadoBD
            ) {

                error_log(
                    "ERROR actualizarPago(): estado esperado [" .
                    $estadoBD .
                    "] pero BD tiene [" .
                    $pedido['estado_pago'] .
                    "]"
                );

                return false;

            }


            /* ==================================================
               VERIFICAR TRANSACCIÓN
            ================================================== */

            if (
                $transaccionId !== null &&
                $pedido['transaccion_id'] !== $transaccionId
            ) {

                error_log(
                    "ERROR actualizarPago(): la transacción no se guardó correctamente."
                );

                return false;

            }


            return true;


        } catch (PDOException $e) {

            error_log(
                "ERROR actualizarPago(): " .
                $e->getMessage()
            );

            return false;

        } catch (Exception $e) {

            error_log(
                "ERROR actualizarPago(): " .
                $e->getMessage()
            );

            return false;

        }

    }


    /**
     * =========================================================
     * OBTENER PEDIDO POR ID
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
     * OBTENER PEDIDO POR REFERENCIA
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
     * OBTENER DETALLES
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
     * SOLO se ejecuta después de un pago APPROVED.
     *
     */
    public function descontarStockPedido($pedidoId)
    {

        try {

            $this->conexion->beginTransaction();


            /* ==============================================
               OBTENER PEDIDO
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


            /* ==============================================
               VERIFICAR PAGO
            ============================================== */

            /*
             * IMPORTANTE:
             *
             * La BD utiliza "Pagado",
             * no "Aprobado".
             */

            if (
                strtolower(trim($pedido['estado_pago'])) !==
                'pagado'
            ) {

                throw new Exception(
                    "El pago del pedido todavía no está aprobado."
                );

            }


            /* ==============================================
               EVITAR DOBLE DESCUENTO
            ============================================== */

            if (
                strtolower(trim($pedido['estado_pedido'])) !==
                'pendiente'
            ) {

                $this->conexion->commit();

                return [

                    'success' => true,

                    'message' =>
                        'El stock de este pedido ya había sido descontado.'

                ];

            }


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
               CONFIRMAR PEDIDO
            ============================================== */

            $sqlEstado = "
    UPDATE pedidos
    SET
        estado_pago = 'Pagado',
        estado_pedido = 'Preparando'
    WHERE id = ?
";


            $stmtEstado =
                $this->conexion->prepare($sqlEstado);


            $stmtEstado->execute([

                $pedidoId

            ]);


            /* ==============================================
               CONFIRMAR TRANSACCIÓN
            ============================================== */

            $this->conexion->commit();


            return [

                'success' => true,

                'message' =>
                    'Pago confirmado y stock actualizado.'

            ];


        } catch (Exception $e) {

            if (
                $this->conexion->inTransaction()
            ) {

                $this->conexion->rollBack();

            }


            return [

                'success' => false,

                'error' => $e->getMessage()

            ];

        }

    }

}