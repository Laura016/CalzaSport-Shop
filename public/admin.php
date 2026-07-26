<?php

require_once '../app/controllers/AdminController.php';

$admin = new AdminController();

$accion = $_GET['accion'] ?? 'dashboard';

switch ($accion) {

    case 'dashboard':
        $admin->dashboard();
        break;

    case 'productos':
        $admin->productos();
        break;

    case 'nuevo':
        $admin->nuevoProducto();
        break;

    case 'editar':
        if(isset($_GET['id'])){
            $admin->editarProducto($_GET['id']);
        }
        break;

    case 'guardar':
        $admin->guardarProducto();
        break;

    case 'actualizar':
        $admin->actualizarProducto();
        break;

    case 'eliminar':
        if(isset($_GET['id'])){
            $admin->eliminarProducto($_GET['id']);
        }
        break;

    default:
        $admin->dashboard();
        break;
}