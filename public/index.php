<?php

require_once '../app/controllers/HomeController.php';

$controller = new HomeController();

$productos = $controller->index();

require_once '../app/views/tienda/layouts/header.php';

require_once '../app/views/tienda/home.php';

require_once '../app/views/tienda/layouts/footer.php';