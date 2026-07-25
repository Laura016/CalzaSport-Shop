<?php

require_once __DIR__ . '/../models/Producto.php';

class HomeController
{
    public function index()
    {
        $productoModel = new Producto();

        $productos = $productoModel->obtenerDestacados();

        return $productos;
    }
}