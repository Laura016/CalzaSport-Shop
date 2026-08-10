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
     * Crear cliente, pedido y detalle del pedido
     * dentro de una sola transacción.
     */
    public function crearPedido($datos)
    {
        try {

            $this->conexion->beginTransaction();


            /*======================================
            1. VALIDAR PRODUCTOS Y STOCK
            ======================================*/

            foreach ($datos['productos'] as $producto) {

                $sqlProducto = "SELECT id, nombre, referencia, precio, tallas, stock
                            FROM productos
                            WHERE id = ?
                            FOR UPDATE";

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


                /*======================================
                VALIDAR TALLA
                ======================================*/

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


                /*======================================
                VALIDAR CANTIDAD
                ======================================*/

                $cantidad =
                    (int) $producto['cantidad'];


                if ($cantidad <= 0) {

                    throw new Exception(
                        "La cantidad seleccionada no es válida."
                    );

                }


                /*======================================
                VALIDAR STOCK
                ======================================*/

                if ($productoBD['stock'] < $cantidad) {

                    throw new Exception(
                        "No hay suficiente stock para " .
                        $productoBD['nombre'] .
                        ". Disponible: " .
                        $productoBD['stock']
                    );

                }

            }


            /*======================================
            2. CREAR CLIENTE
            ======================================*/

            $sqlCliente = "INSERT INTO clientes
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
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

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

                $datos['envio']['codigo_postal'] ?: null,

                $datos['envio']['indicaciones'] ?: null

            ]);


            $clienteId =
                $this->conexion->lastInsertId();


            /*======================================
            3. CREAR PEDIDO
            ======================================*/

            $sqlPedido = "INSERT INTO pedidos
        (
            cliente_id,
            subtotal,
            costo_envio,
            total,
            metodo_pago
        )
        VALUES (?, ?, ?, ?, ?)";

            $costoEnvio = 0;

            $stmtPedido =
                $this->conexion->prepare($sqlPedido);

            $stmtPedido->execute([

                $clienteId,

                $datos['subtotal'],

                $costoEnvio,

                $datos['total'],

                $datos['metodo_pago']

            ]);


            $pedidoId =
                $this->conexion->lastInsertId();


            /*======================================
            4. CREAR DETALLE + DESCONTAR STOCK
            ======================================*/

            $sqlDetalle = "INSERT INTO detalle_pedido
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
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmtDetalle =
                $this->conexion->prepare($sqlDetalle);


            $sqlStock = "UPDATE productos
                     SET stock = stock - ?,
                         estado =
                            CASE
                                WHEN stock - ? <= 0
                                THEN 'Agotado'
                                ELSE 'Disponible'
                            END
                     WHERE id = ?
                     AND stock >= ?";

            $stmtStock =
                $this->conexion->prepare($sqlStock);


            foreach ($datos['productos'] as $producto) {

                $cantidad =
                    (int) $producto['cantidad'];

                $precio =
                    (float) $producto['precio'];

                $subtotalProducto =
                    $precio * $cantidad;


                /*----------------------------------
                GUARDAR DETALLE
                ----------------------------------*/

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


                /*----------------------------------
                DESCONTAR STOCK
                ----------------------------------*/

                $stmtStock->execute([

                    $cantidad,

                    $cantidad,

                    $producto['id'],

                    $cantidad

                ]);


                if ($stmtStock->rowCount() !== 1) {

                    throw new Exception(
                        "No fue posible actualizar el stock de " .
                        $producto['nombre']
                    );

                }

            }


            /*======================================
            5. CONFIRMAR TRANSACCIÓN
            ======================================*/

            $this->conexion->commit();


            return [

                'success' => true,

                'pedido_id' => $pedidoId,

                'cliente_id' => $clienteId

            ];


        } catch (Exception $e) {


            /*======================================
            CANCELAR TODO SI ALGO FALLA
            ======================================*/

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