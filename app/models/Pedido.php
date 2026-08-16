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
     * Esta función:
     *
     * 1. Valida los productos.
     * 2. Obtiene precios reales desde MySQL.
     * 3. Valida tallas.
     * 4. Valida stock disponible.
     * 5. Crea el cliente.
     * 6. Crea el pedido.
     * 7. Guarda los detalles.
     *
     * IMPORTANTE:
     * El stock NO se descuenta aquí.
     *
     * El descuento definitivo se realizará cuando
     * la pasarela confirme el pago.
     */
    public function crearPedido($datos)
    {
        try {

            /* =====================================================
               INICIAR TRANSACCIÓN
            ===================================================== */

            $this->conexion->beginTransaction();


            /* =====================================================
               1. VALIDAR DATOS PRINCIPALES
            ===================================================== */

            if (
                empty($datos['cliente']) ||
                empty($datos['envio']) ||
                empty($datos['productos']) ||
                empty($datos['metodo_pago'])
            ) {

                throw new Exception(
                    "Faltan datos obligatorios para crear el pedido."
                );

            }


            /* =====================================================
               2. VALIDAR MÉTODO DE PAGO
            ===================================================== */

            $metodosPermitidos = [
                'nequi',
                'bancolombia',
                'pse',
                'tarjeta'
            ];

            if (
                !in_array(
                    $datos['metodo_pago'],
                    $metodosPermitidos,
                    true
                )
            ) {

                throw new Exception(
                    "El método de pago seleccionado no es válido."
                );

            }


            /* =====================================================
               3. AGRUPAR PRODUCTOS
            ===================================================== */

            /*
             * Si por alguna razón el mismo producto aparece
             * varias veces en el carrito, sumamos las cantidades.
             */

            $productosAgrupados = [];


            foreach ($datos['productos'] as $producto) {

                if (empty($producto['id'])) {

                    throw new Exception(
                        "Uno de los productos seleccionados no tiene un ID válido."
                    );

                }


                $productoId = (int) $producto['id'];

                $cantidad = (int) ($producto['cantidad'] ?? 0);


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad seleccionada no es válida."
                    );

                }


                if (!isset($productosAgrupados[$productoId])) {

                    $productosAgrupados[$productoId] = 0;

                }


                $productosAgrupados[$productoId] += $cantidad;

            }


            /* =====================================================
               4. OBTENER PRODUCTOS REALES DESDE MYSQL
            ===================================================== */

            $productosBD = [];


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


            $subtotal = 0;


            foreach (
                $productosAgrupados
                as $productoId => $cantidad
            ) {

                $stmtProducto->execute([
                    $productoId
                ]);


                $productoBD =
                    $stmtProducto->fetch(PDO::FETCH_ASSOC);


                /* =================================================
                   PRODUCTO NO EXISTE
                ================================================= */

                if (!$productoBD) {

                    throw new Exception(
                        "Uno de los productos seleccionados ya no existe."
                    );

                }


                /* =================================================
                   VALIDAR STOCK
                ================================================= */

                if ((int) $productoBD['stock'] < $cantidad) {

                    throw new Exception(

                        "No hay suficiente stock para " .
                        $productoBD['nombre'] .
                        ". Disponible: " .
                        $productoBD['stock']

                    );

                }


                /* =================================================
                   OBTENER PRECIO REAL DE MYSQL
                ================================================= */

                $precio = (float) $productoBD['precio'];


                /* =================================================
                   SUBTOTAL DEL PRODUCTO
                ================================================= */

                $subtotalProducto =
                    $precio * $cantidad;


                $subtotal += $subtotalProducto;


                /* =================================================
                   GUARDAR INFORMACIÓN REAL
                ================================================= */

                $productosBD[$productoId] = [

                    'id' =>
                        (int) $productoBD['id'],

                    'nombre' =>
                        $productoBD['nombre'],

                    'referencia' =>
                        $productoBD['referencia'],

                    'precio' =>
                        $precio,

                    'tallas' =>
                        $productoBD['tallas'],

                    'stock' =>
                        (int) $productoBD['stock'],

                    'cantidad' =>
                        $cantidad,

                    'subtotal' =>
                        $subtotalProducto

                ];

            }


            /* =====================================================
               5. VALIDAR TALLAS
            ===================================================== */

            foreach ($datos['productos'] as $producto) {

                $productoId =
                    (int) $producto['id'];


                $tallaSeleccionada =
                    trim($producto['talla'] ?? '');


                if ($tallaSeleccionada === '') {

                    throw new Exception(

                        "Debes seleccionar una talla para " .
                        $productosBD[$productoId]['nombre']

                    );

                }


                $tallasDisponibles =
                    array_map(

                        'trim',

                        explode(
                            ',',
                            $productosBD[$productoId]['tallas']
                        )

                    );


                if (
                    !in_array(
                        $tallaSeleccionada,
                        $tallasDisponibles,
                        true
                    )
                ) {

                    throw new Exception(

                        "La talla " .
                        $tallaSeleccionada .
                        " no está disponible para " .
                        $productosBD[$productoId]['nombre']

                    );

                }

            }


            /* =====================================================
               6. COSTO DE ENVÍO
            ===================================================== */

            /*
             * Por ahora dejamos el envío en 0 porque
             * todavía no tenemos implementado el cálculo
             * automático del envío.
             *
             * Más adelante podemos calcularlo según:
             *
             * - Ciudad
             * - Departamento
             * - Transportadora
             * - Peso
             * - Valor del pedido
             */

            $costoEnvio = 0;


            $total =
                $subtotal + $costoEnvio;


            /* =====================================================
               7. CREAR CLIENTE
            ===================================================== */

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

                trim(
                    $datos['cliente']['nombre']
                ),

                trim(
                    $datos['cliente']['telefono']
                ),

                trim(
                    $datos['cliente']['correo']
                ),

                trim(
                    $datos['envio']['direccion']
                ),

                trim(
                    $datos['envio']['departamento']
                ),

                trim(
                    $datos['envio']['ciudad']
                ),

                trim(
                    $datos['envio']['barrio']
                ),

                !empty(
                    $datos['envio']['codigo_postal']
                )
                    ? trim(
                        $datos['envio']['codigo_postal']
                    )
                    : null,

                !empty(
                    $datos['envio']['indicaciones']
                )
                    ? trim(
                        $datos['envio']['indicaciones']
                    )
                    : null

            ]);


            $clienteId =
                $this->conexion->lastInsertId();


            /* =====================================================
               8. CREAR PEDIDO
            ===================================================== */

            $sqlPedido = "

                INSERT INTO pedidos
                (
                    cliente_id,
                    subtotal,
                    costo_envio,
                    total,
                    metodo_pago,
                    estado_pago,
                    estado_pedido
                )

                VALUES (?, ?, ?, ?, ?, ?, ?)

            ";


            $estadoPago =
                'Pendiente';


            $estadoPedido =
                'Pendiente';


            $stmtPedido =
                $this->conexion->prepare($sqlPedido);


            $stmtPedido->execute([

                $clienteId,

                $subtotal,

                $costoEnvio,

                $total,

                $datos['metodo_pago'],

                $estadoPago,

                $estadoPedido

            ]);


            $pedidoId =
                $this->conexion->lastInsertId();


            /* =====================================================
               9. CREAR DETALLES DEL PEDIDO
            ===================================================== */

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

                $productoId =
                    (int) $producto['id'];


                $talla =
                    trim(
                        $producto['talla']
                    );


                /*
                 * Utilizamos SIEMPRE la información
                 * obtenida desde MySQL.
                 */

                $productoBD =
                    $productosBD[$productoId];


                $cantidad =
                    (int) $producto['cantidad'];


                $precio =
                    $productoBD['precio'];


                $subtotalProducto =
                    $precio * $cantidad;


                $stmtDetalle->execute([

                    $pedidoId,

                    $productoId,

                    $productoBD['nombre'],

                    $productoBD['referencia'],

                    $talla,

                    $cantidad,

                    $precio,

                    $subtotalProducto

                ]);

            }


            /* =====================================================
               10. CONFIRMAR TRANSACCIÓN
            ===================================================== */

            $this->conexion->commit();


            /* =====================================================
               RESPUESTA
            ===================================================== */

            return [

                'success' => true,

                'pedido_id' =>
                    $pedidoId,

                'cliente_id' =>
                    $clienteId,

                'subtotal' =>
                    $subtotal,

                'costo_envio' =>
                    $costoEnvio,

                'total' =>
                    $total

            ];


        } catch (Exception $e) {


            /* =====================================================
               DESHACER TRANSACCIÓN
            ===================================================== */

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


    /**
     * =========================================================
     * DESCONTAR STOCK DESPUÉS DEL PAGO
     * =========================================================
     *
     * ESTA FUNCIÓN TODAVÍA NO SE UTILIZA.
     *
     * La conectaremos cuando integremos la pasarela
     * de pago y recibamos la confirmación del pago.
     */
    public function descontarStockPedido($pedidoId)
    {
        try {

            $this->conexion->beginTransaction();


            /* =====================================================
               OBTENER DETALLES DEL PEDIDO
            ===================================================== */

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
                $stmtDetalles->fetchAll(
                    PDO::FETCH_ASSOC
                );


            if (!$detalles) {

                throw new Exception(
                    "El pedido no tiene productos asociados."
                );

            }


            /* =====================================================
               DESCONTAR STOCK
            ===================================================== */

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


                if (
                    $stmtStock->rowCount() !== 1
                ) {

                    throw new Exception(

                        "No hay suficiente stock para completar el pedido."

                    );

                }

            }


            /* =====================================================
               CONFIRMAR
            ===================================================== */

            $this->conexion->commit();


            return [

                'success' => true

            ];


        } catch (Exception $e) {


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