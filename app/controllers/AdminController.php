<?php

require_once '../app/models/Producto.php';
require_once '../app/models/Pedido.php';
require_once '../app/models/Promocion.php';

class AdminController
{
    private $producto;
    private $pedido;

    private $promocion;

    public function __construct()
    {
        $this->producto = new Producto();
        $this->pedido = new Pedido();
        $this->promocion = new Promocion();
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

            $rutaDestino = __DIR__ . "/../../public/assets/img/productos/" . $nombreImagen;

            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);

            // Eliminar la imagen anterior
            $imagenAnterior = __DIR__ . "/../../public/assets/img/productos/" . $productoActual['imagen'];

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

        $rutaImagen = __DIR__ . "/../../public/assets/img/productos/" . $producto['imagen'];

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

        $ruta = __DIR__ . "/../../public/assets/img/productos/" . $nombre;

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

    public function promociones()
    {
        $promociones = $this->promocion->obtenerTodas();

        require_once '../app/views/admin/promociones.php';
    }

    public function nuevaPromocion()
    {
        $productos = $this->producto->obtenerProductos();

        require_once '../app/views/admin/nueva_promocion.php';
    }

    public function guardarPromocion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?accion=promociones');
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $tipoDescuento = $_POST['tipo_descuento'] ?? 'porcentaje';
        $descuento = (float) ($_POST['descuento'] ?? 0);
        $fechaInicio = $_POST['fecha_inicio'] ?? '';
        $fechaFin = $_POST['fecha_fin'] ?? '';
        $estado = $_POST['estado'] ?? 'Activa';
        $productos = $_POST['productos'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Validaciones
        |--------------------------------------------------------------------------
        */

        if (
            $nombre === '' ||
            $titulo === '' ||
            $fechaInicio === '' ||
            $fechaFin === ''
        ) {
            die('Por favor completa todos los campos obligatorios.');
        }

        if (!in_array($tipoDescuento, ['porcentaje', 'valor_fijo'], true)) {
            die('El tipo de descuento no es válido.');
        }

        if (!in_array($estado, ['Activa', 'Inactiva'], true)) {
            die('El estado seleccionado no es válido.');
        }

        if ($descuento <= 0) {
            die('El descuento debe ser mayor que cero.');
        }

        if (
            $tipoDescuento === 'porcentaje' &&
            $descuento > 100
        ) {
            die('El descuento porcentual no puede ser mayor al 100%.');
        }

        if ($fechaInicio > $fechaFin) {
            die('La fecha de inicio no puede ser posterior a la fecha de finalización.');
        }

        /*
        |--------------------------------------------------------------------------
        | Imagen
        |--------------------------------------------------------------------------
        */

        $nombreImagen = null;

        if (
            isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
        ) {

            if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
                die('Ocurrió un error al subir la imagen.');
            }

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            $nombreOriginal = $_FILES['imagen']['name'];
            $extension = strtolower(
                pathinfo($nombreOriginal, PATHINFO_EXTENSION)
            );

            if (!in_array($extension, $extensionesPermitidas, true)) {
                die('El formato de imagen no está permitido.');
            }

            if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
                die('La imagen no puede superar los 5 MB.');
            }

            $nombreImagen =
                'promocion_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $extension;

            $directorioDestino =
                __DIR__ .
                '/../../public/assets/img/ofertas/';

            if (!is_dir($directorioDestino)) {
                mkdir(
                    $directorioDestino,
                    0755,
                    true
                );
            }

            $rutaDestino =
                $directorioDestino .
                $nombreImagen;

            if (
                !move_uploaded_file(
                    $_FILES['imagen']['tmp_name'],
                    $rutaDestino
                )
            ) {
                die('No fue posible guardar la imagen.');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Crear promoción
        |--------------------------------------------------------------------------
        */

        $datos = [
            'nombre' => $nombre,
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'imagen' => $nombreImagen,
            'tipo_descuento' => $tipoDescuento,
            'descuento' => $descuento,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estado
        ];

        $promocionId = $this->promocion->crear($datos);

        if (!$promocionId) {

            /*
             * Si la promoción no pudo crearse,
             * eliminamos la imagen que acabamos de subir.
             */

            if ($nombreImagen !== null) {

                $rutaImagen =
                    __DIR__ .
                    '/../../public/assets/img/ofertas/' .
                    $nombreImagen;

                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }

            die('No fue posible crear la promoción.');
        }

        /*
        |--------------------------------------------------------------------------
        | Asignar productos
        |--------------------------------------------------------------------------
        */

        $this->promocion->asignarProductos(
            $promocionId,
            $productos
        );

        /*
        |--------------------------------------------------------------------------
        | Volver al listado
        |--------------------------------------------------------------------------
        */

        header(
            'Location: admin.php?accion=promociones'
        );
        exit;
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