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
}