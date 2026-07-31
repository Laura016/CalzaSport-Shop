<?php

require_once __DIR__ . '/../models/Producto.php';

class CatalogoController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    public function index()
    {
        return $this->producto->obtenerProductos();
    }
}