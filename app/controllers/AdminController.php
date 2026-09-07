<?php

require_once '../app/models/Producto.php';
require_once '../app/models/Pedido.php';

class AdminController
{
    private $producto;
    private $pedido;

    public function __construct()
    {
        $this->producto = new Producto();
        $this->pedido = new Pedido();
    }

    // Dashboard
    public function dashboard()
    {
        $totalProductos = (int) $this->producto->totalProductos()['total'];

        $productosDisponibles = (int) $this->producto->productosDisponibles()['total'];

        $totalVentas = $this->pedido->totalVentas();

        $totalClientes = $this->pedido->totalClientes();

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

    public function ventas()
    {
        $pedidos = $this->pedido->obtenerTodos();

        $totalPedidos = count($pedidos);

        $ventasPagadas = 0;
        $pedidosPendientes = 0;
        $pedidosCompletados = 0;

        foreach ($pedidos as $pedido) {

            if ($pedido['estado_pago'] === 'Pagado') {
                $ventasPagadas++;
            }

            if ($pedido['estado_pedido'] === 'Pendiente') {
                $pedidosPendientes++;
            }

            if ($pedido['estado_pedido'] === 'Entregado') {
                $pedidosCompletados++;
            }
        }

        require_once '../app/views/admin/ventas.php';
    }

    public function verPedido($id)
    {
        $pedido = $this->pedido->obtenerPorId($id);

        if (!$pedido) {
            header('Location: admin.php?accion=ventas');
            exit;
        }

        $detalles = $this->pedido->obtenerDetalles($id);


        /*
         * =====================================================
         * TOKEN CSRF
         * =====================================================
         */

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        $csrfToken = $_SESSION['csrf_token'];


        /*
         * =====================================================
         * MENSAJES
         * =====================================================
         */

        $mensajeExito = $_SESSION['admin_mensaje_exito'] ?? null;
        $mensajeError = $_SESSION['admin_mensaje_error'] ?? null;

        unset($_SESSION['admin_mensaje_exito']);
        unset($_SESSION['admin_mensaje_error']);


        require_once '../app/views/admin/pedido-detalle.php';
    }

    public function actualizarEstadoPedido()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?accion=ventas');
            exit;
        }

        $pedidoId = (int) ($_POST['pedido_id'] ?? 0);
        $nuevoEstado = trim($_POST['nuevo_estado'] ?? '');

        if ($pedidoId <= 0 || $nuevoEstado === '') {
            header(
                'Location: admin.php?accion=ventas'
            );
            exit;
        }


        /*
         * =====================================================
         * PROTECCIÓN CSRF
         * =====================================================
         */

        $tokenSesion = $_SESSION['csrf_token'] ?? '';
        $tokenFormulario = $_POST['csrf_token'] ?? '';

        if (
            empty($tokenSesion) ||
            empty($tokenFormulario) ||
            !hash_equals($tokenSesion, $tokenFormulario)
        ) {

            $_SESSION['admin_mensaje_error'] =
                'La solicitud no es válida. Intenta nuevamente.';

            header(
                'Location: admin.php?accion=verPedido&id=' .
                $pedidoId
            );

            exit;
        }


        /*
         * Actualizar estado
         */

        $resultado = $this->pedido->actualizarEstadoPedido(
            $pedidoId,
            $nuevoEstado
        );


        /*
         * Guardar mensaje para mostrarlo
         */

        if ($resultado['success']) {

            $_SESSION['admin_mensaje_exito'] =
                $resultado['message'];

        } else {

            $_SESSION['admin_mensaje_error'] =
                $resultado['error'];
        }


        /*
         * Regresar al detalle
         */

        header(
            'Location: admin.php?accion=verPedido&id=' .
            $pedidoId
        );

        exit;
    }
}