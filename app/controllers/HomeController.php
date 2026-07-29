<?php

require_once __DIR__ . '/../models/Producto.php';

class HomeController
{
    private $productoModel;

    public function __construct()
    {
        $this->productoModel = new Producto();
    }

    /*=========================
    INICIO
    =========================*/

    public function index()
    {
        return $this->productoModel->obtenerDestacados();
    }

    /*=========================
    CATÁLOGO
    =========================*/

    public function catalogo()
    {
        return $this->productoModel->obtenerProductos();
    }

    /*=========================
    PRODUCTO
    =========================*/

    public function producto($id)
    {
        return $this->productoModel->obtenerPorId($id);
    }

    /*=========================
    RELACIONADOS
    =========================*/

    public function relacionados($categoria, $id)
    {
        return $this->productoModel->relacionados($categoria, $id);
    }

    /*=========================
    BUSCADOR
    =========================*/

    public function buscar($texto)
    {
        return $this->productoModel->buscar($texto);
    }

    /*=========================
    CATEGORÍA
    =========================*/

    public function categoria($categoria)
    {
        return $this->productoModel->obtenerPorCategoria($categoria);
    }
}