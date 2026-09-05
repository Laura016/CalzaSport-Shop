<?php

require_once '../app/config/auth.php';

cerrarSesionAdmin();

header('Location: admin-login.php');
exit;