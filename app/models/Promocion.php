<?php

require_once __DIR__ . '/../config/database.php';

class Promocion
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    /*
    |--------------------------------------------------------------------------
    | OBTENER TODAS LAS PROMOCIONES
    |--------------------------------------------------------------------------
    */

    public function obtenerTodas()
    {
        $sql = "SELECT *
                FROM promociones
                ORDER BY id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER PROMOCIÓN POR ID
    |--------------------------------------------------------------------------
    */

    public function obtenerPorId($id)
    {
        $sql = "SELECT *
                FROM promociones
                WHERE id = ?
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER PRODUCTOS DE UNA PROMOCIÓN
    |--------------------------------------------------------------------------
    */

    public function obtenerProductos($promocionId)
    {
        $sql = "SELECT p.*
                FROM productos p
                INNER JOIN promocion_productos pp
                    ON p.id = pp.producto_id
                WHERE pp.promocion_id = ?
                ORDER BY p.nombre ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([$promocionId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CREAR PROMOCIÓN
    |--------------------------------------------------------------------------
    */

    public function crear($datos)
    {
        $sql = "INSERT INTO promociones
                (
                    nombre,
                    titulo,
                    descripcion,
                    imagen,
                    tipo_descuento,
                    descuento,
                    fecha_inicio,
                    fecha_fin,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $resultado = $stmt->execute([
            $datos['nombre'],
            $datos['titulo'],
            $datos['descripcion'],
            $datos['imagen'],
            $datos['tipo_descuento'],
            $datos['descuento'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['estado']
        ]);

        if (!$resultado) {
            return false;
        }

        return $this->conexion->lastInsertId();
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR PROMOCIÓN
    |--------------------------------------------------------------------------
    */

    public function actualizar($datos)
    {
        $sql = "UPDATE promociones SET

                    nombre = ?,
                    titulo = ?,
                    descripcion = ?,
                    imagen = ?,
                    tipo_descuento = ?,
                    descuento = ?,
                    fecha_inicio = ?,
                    fecha_fin = ?,
                    estado = ?

                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            $datos['nombre'],
            $datos['titulo'],
            $datos['descripcion'],
            $datos['imagen'],
            $datos['tipo_descuento'],
            $datos['descuento'],
            $datos['fecha_inicio'],
            $datos['fecha_fin'],
            $datos['estado'],
            $datos['id']
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR PROMOCIÓN
    |--------------------------------------------------------------------------
    */

    public function eliminar($id)
    {
        $sql = "DELETE FROM promociones
                WHERE id = ?";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([$id]);
    }


    /*
    |--------------------------------------------------------------------------
    | ASIGNAR PRODUCTOS A UNA PROMOCIÓN
    |--------------------------------------------------------------------------
    */

    public function asignarProductos($promocionId, $productos)
    {
        /*
         * Primero eliminamos las relaciones anteriores.
         */

        $sqlEliminar = "DELETE FROM promocion_productos
                        WHERE promocion_id = ?";

        $stmtEliminar = $this->conexion->prepare($sqlEliminar);
        $stmtEliminar->execute([$promocionId]);


        /*
         * Si no hay productos seleccionados,
         * dejamos la promoción sin productos.
         */

        if (empty($productos)) {
            return true;
        }


        /*
         * Insertar los nuevos productos.
         */

        $sqlInsertar = "INSERT INTO promocion_productos
                        (
                            promocion_id,
                            producto_id
                        )
                        VALUES (?, ?)";

        $stmtInsertar = $this->conexion->prepare($sqlInsertar);


        foreach ($productos as $productoId) {

            $stmtInsertar->execute([
                $promocionId,
                (int) $productoId
            ]);
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER PROMOCIONES ACTIVAS PARA LA TIENDA
    |--------------------------------------------------------------------------
    */

    public function obtenerActivas()
    {
        $sql = "SELECT *
                FROM promociones
                WHERE estado = 'Activa'
                AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
                ORDER BY id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | OBTENER PROMOCIÓN ACTIVA DE UN PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function obtenerPromocionProducto($productoId)
    {
        $sql = "SELECT
                    pr.*
                FROM promociones pr

                INNER JOIN promocion_productos pp
                    ON pr.id = pp.promocion_id

                WHERE pp.producto_id = ?
                AND pr.estado = 'Activa'
                AND CURDATE() BETWEEN pr.fecha_inicio
                                  AND pr.fecha_fin

                ORDER BY pr.id DESC

                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute([
            $productoId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULAR PRECIO CON DESCUENTO
    |--------------------------------------------------------------------------
    */

    public function calcularPrecioPromocional(
        $precio,
        $tipoDescuento,
        $descuento
    ) {
        $precio = (float) $precio;
        $descuento = (float) $descuento;


        /*
         * Descuento porcentual
         */

        if ($tipoDescuento === 'porcentaje') {

            $precioFinal =
                $precio - ($precio * $descuento / 100);

        }


        /*
         * Descuento de valor fijo
         */

        elseif ($tipoDescuento === 'valor_fijo') {

            $precioFinal =
                $precio - $descuento;

        }


        /*
         * Tipo desconocido
         */

        else {

            $precioFinal = $precio;

        }


        /*
         * Nunca permitimos un precio negativo.
         */

        if ($precioFinal < 0) {
            $precioFinal = 0;
        }


        return round($precioFinal, 2);
    }
}