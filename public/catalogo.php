<?php

require_once '../app/controllers/CatalogoController.php';

$controller = new CatalogoController();

$productos = $controller->index();

require_once '../app/views/tienda/layouts/header.php';

require_once '../app/views/tienda/catalogo.php';

require_once '../app/views/tienda/layouts/footer.php';