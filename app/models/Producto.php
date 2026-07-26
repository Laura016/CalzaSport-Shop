<?php

require_once __DIR__ . '/../config/database.php';

class Producto
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    public function obtenerDestacados()
    {
        $sql = "SELECT * FROM productos
                WHERE destacado = 1
                ORDER BY id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProductos()
{
    $sql = "SELECT *
            FROM productos
            ORDER BY id DESC";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function totalProductos()
{
    $sql = "SELECT COUNT(*) AS total FROM productos";

    $stmt = $this->conexion->prepare($sql);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function productosDisponibles()
{
    $sql = "SELECT COUNT(*) AS total
            FROM productos
            WHERE estado='Disponible'";

    $stmt = $this->conexion->prepare($sql);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function productosAgotados()
{
    $sql = "SELECT COUNT(*) AS total
            FROM productos
            WHERE estado='Agotado'";

    $stmt = $this->conexion->prepare($sql);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function valorInventario()
{
    $sql = "SELECT SUM(precio * stock) AS total
            FROM productos";

    $stmt = $this->conexion->prepare($sql);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerPorId($id)
{
    $sql = "SELECT *
            FROM productos
            WHERE id = ?";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function eliminar($id)
{
    $sql = "DELETE FROM productos
            WHERE id=?";

    $stmt = $this->conexion->prepare($sql);

    return $stmt->execute([$id]);
}

public function guardar($datos)
{
    $sql = "INSERT INTO productos
    (nombre,referencia,descripcion,categoria,marca,precio,imagen,tallas,stock,estado,destacado)

    VALUES (?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $this->conexion->prepare($sql);

    return $stmt->execute([
        $datos['nombre'],
        $datos['referencia'],
        $datos['descripcion'],
        $datos['categoria'],
        $datos['marca'],
        $datos['precio'],
        $datos['imagen'],
        $datos['tallas'],
        $datos['stock'],
        $datos['estado'],
        $datos['destacado']
    ]);
}

public function actualizar($datos)
{
    $sql = "UPDATE productos SET

        nombre=?,
        referencia=?,
        descripcion=?,
        categoria=?,
        marca=?,
        precio=?,
        imagen=?,
        tallas=?,
        stock=?,
        estado=?,
        destacado=?

        WHERE id=?";

    $stmt = $this->conexion->prepare($sql);

    return $stmt->execute([

        $datos['nombre'],
        $datos['referencia'],
        $datos['descripcion'],
        $datos['categoria'],
        $datos['marca'],
        $datos['precio'],
        $datos['imagen'],
        $datos['tallas'],
        $datos['stock'],
        $datos['estado'],
        $datos['destacado'],
        $datos['id']

    ]);
}
}