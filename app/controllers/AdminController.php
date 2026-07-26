<?php

require_once '../app/models/Producto.php';

class AdminController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    // Dashboard
    public function dashboard()
    {
        require_once '../app/views/admin/dashboard.php';
    }

    // Listar productos
    public function productos()
    {
        $productos = $this->producto->obtenerProductos();

        require_once '../app/views/admin/productos.php';
    }

    // Mostrar formulario nuevo
    public function nuevoProducto()
    {
        require_once '../app/views/admin/nuevo_producto.php';
    }

    // Guardar producto
    public function guardarProducto()
    {

    }

    // Mostrar formulario editar
    public function editarProducto($id)
    {

    }

    // Actualizar producto
    public function actualizarProducto()
    {

    }

    // Eliminar producto
    public function eliminarProducto($id)
    {

    }
}