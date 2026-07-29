<?php

require_once __DIR__ . '/../models/Producto.php';

class ProductoController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    /*==========================
    CATÁLOGO
    ==========================*/

    public function catalogo()
    {
        $productos = $this->producto->obtenerProductos();

        require_once __DIR__ . '/../views/tienda/catalogo.php';
    }

    /*==========================
    DETALLE DEL PRODUCTO
    ==========================*/

    public function detalle($id)
    {
        $producto = $this->producto->obtenerPorId($id);

        if(!$producto){

            die("Producto no encontrado.");

        }

        $relacionados = $this->producto->relacionados(

            $producto['categoria'],
            $producto['id']

        );

        require_once __DIR__ . '/../views/tienda/producto.php';
    }

}