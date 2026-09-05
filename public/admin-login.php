<?php

require_once '../app/config/database.php';
require_once '../app/config/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {

        $error = 'Por favor, completa todos los campos.';

    } else {

        try {

            $database = new Database();
            $db = $database->conectar();

            $sql = "SELECT id, nombre, email, password, rol, estado
                    FROM usuarios
                    WHERE email = :email
                    LIMIT 1";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':email' => $email
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {

                $error = 'El correo o la contraseña no son correctos.';

            } elseif ($usuario['estado'] !== 'Activo') {

                $error = 'Este usuario se encuentra inactivo.';

            } elseif ($usuario['rol'] !== 'admin') {

                $error = 'No tienes permisos para acceder al panel.';

            } elseif (!password_verify($password, $usuario['password'])) {

                $error = 'El correo o la contraseña no son correctos.';

            } else {

                /*
                 * Regeneramos el ID de sesión para evitar
                 * ataques de fijación de sesión.
                 */
                session_regenerate_id(true);

                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nombre'] = $usuario['nombre'];
                $_SESSION['admin_email'] = $usuario['email'];
                $_SESSION['admin_rol'] = $usuario['rol'];

                header('Location: admin.php');
                exit;
            }

        } catch (PDOException $e) {

            $error = 'No fue posible iniciar sesión. Inténtalo nuevamente.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Acceso Administrador | CalzaSport</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: #f5f6f8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand h1 {
            font-size: 30px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
        }

        .brand span {
            font-size: 14px;
            color: #6b7280;
        }

        .title {
            margin-bottom: 25px;
        }

        .title h2 {
            font-size: 22px;
            color: #111827;
            margin-bottom: 6px;
        }

        .title p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            height: 48px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 0 14px;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.08);
        }

        .btn-login {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: #111827;
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-login:hover {
            background: #000000;
            transform: translateY(-1px);
        }

        .error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #9ca3af;
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 28px 22px;
            }

            .brand h1 {
                font-size: 26px;
            }
        }

    </style>

</head>

<body>

    <div class="login-container">

        <div class="login-card">

            <div class="brand">
                <h1>CalzaSport</h1>
                <span>Panel administrativo</span>
            </div>

            <div class="title">
                <h2>Iniciar sesión</h2>
                <p>Accede al panel de administración.</p>
            </div>

            <?php if ($error !== ''): ?>

                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <form method="POST" autocomplete="off">

                <div class="form-group">

                    <label for="email">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@calzasport.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ingresa tu contraseña"
                        required
                    >

                </div>

                <button
                    type="submit"
                    class="btn-login"
                >
                    Iniciar sesión
                </button>

            </form>

            <div class="footer">
                CalzaSport © <?= date('Y') ?>
            </div>

        </div>

    </div>

</body>

</html>