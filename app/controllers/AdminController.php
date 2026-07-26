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
    // Verificar que se envió una imagen
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] != 0) {

        die("Debes seleccionar una imagen.");

    }

    // Nombre original
    $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);

    // Ruta donde se guardará
    $rutaDestino = __DIR__ . "/../../public/assets/img/" . $nombreImagen;

    // Copiar imagen
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {

        die("No se pudo subir la imagen.");

    }

    // Preparar datos
    $datos = [

        "nombre" => $_POST['nombre'],

        "referencia" => $_POST['referencia'],

        "descripcion" => $_POST['descripcion'],

        "categoria" => $_POST['categoria'],

        "marca" => $_POST['marca'],

        "precio" => $_POST['precio'],

        "imagen" => $nombreImagen,

        "tallas" => $_POST['tallas'],

        "stock" => $_POST['stock'],

        "estado" => $_POST['estado'],

        "destacado" => $_POST['destacado']

    ];

    $this->producto->guardar($datos);

    header("Location: admin.php?accion=productos");

    exit;
}

    // Mostrar formulario editar
   public function editarProducto($id)
{
    $producto = $this->producto->obtenerPorId($id);

    if (!$producto) {
        die("Producto no encontrado.");
    }

    require_once '../app/views/admin/editar_producto.php';
}

    // Actualizar producto
    public function actualizarProducto()
{
    $productoActual = $this->producto->obtenerPorId($_POST['id']);

    $nombreImagen = $productoActual['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {

        $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);

        $rutaDestino = __DIR__ . "/../../public/assets/img/" . $nombreImagen;

        move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);

        // Eliminar la imagen anterior
        $imagenAnterior = __DIR__ . "/../../public/assets/img/" . $productoActual['imagen'];

        if (file_exists($imagenAnterior)) {
            unlink($imagenAnterior);
        }
    }

    $datos = [

        "id" => $_POST['id'],

        "nombre" => $_POST['nombre'],

        "referencia" => $_POST['referencia'],

        "descripcion" => $_POST['descripcion'],

        "categoria" => $_POST['categoria'],

        "marca" => $_POST['marca'],

        "precio" => $_POST['precio'],

        "imagen" => $nombreImagen,

        "tallas" => $_POST['tallas'],

        "stock" => $_POST['stock'],

        "estado" => $_POST['estado'],

        "destacado" => $_POST['destacado']

    ];

    $this->producto->actualizar($datos);

    header("Location: admin.php?accion=productos");

    exit;
}

    // Eliminar producto
    public function eliminarProducto($id)
    {

    }
}