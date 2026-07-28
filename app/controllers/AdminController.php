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
    // Subir la imagen usando la función reutilizable
    $nombreImagen = $this->subirImagen($_FILES['imagen']);

    if ($nombreImagen === null) {

        die("Debes seleccionar una imagen válida (JPG, JPEG, PNG o WEBP, máximo 5 MB).");

    }

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

        "destacado" => $_POST['destacado']

    ];

    $this->producto->actualizar($datos);

    header("Location: admin.php?accion=productos");

    exit;
}

    // Eliminar producto
    public function eliminarProducto($id)
{
    $producto = $this->producto->obtenerPorId($id);

    if (!$producto) {
        die("Producto no encontrado.");
    }

    $rutaImagen = __DIR__ . "/../../public/assets/img/" . $producto['imagen'];

    if (file_exists($rutaImagen)) {
        unlink($rutaImagen);
    }

    $this->producto->eliminar($id);

    header("Location: admin.php?accion=productos");

    exit;
 
}

private function subirImagen($archivo)
{
    if (
        !isset($archivo) ||
        $archivo['error'] != 0 ||
        empty($archivo['name'])
    ) {
        return null;
    }

    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($extension, $permitidas)) {
        return null;
    }

    // Tamaño máximo: 5 MB
    if ($archivo['size'] > 5 * 1024 * 1024) {
        return null;
    }

    $nombre = uniqid('producto_') . "." . $extension;

    $ruta = __DIR__ . "/../../public/assets/img/" . $nombre;

    if (move_uploaded_file($archivo['tmp_name'], $ruta)) {

        return $nombre;

    }

    return null;
}
public function inventario()
{
    $productos = $this->producto->obtenerProductos();

    $totalProductos = $this->producto->totalProductos();

    $productosDisponibles = $this->producto->productosDisponibles();

    $productosAgotados = $this->producto->productosAgotados();

    $productosBajoStock = $this->producto->productosBajoStock();

    require_once '../app/views/admin/inventario.php';
}
}