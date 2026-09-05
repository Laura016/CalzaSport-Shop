<?php

require_once 'layouts/header.php';

require_once 'layouts/sidebar.php';

?>

<main class="main-content">

    <div class="topbar">

    <div class="left-topbar">

        <button id="menuToggle">

            <i class="fa-solid fa-bars"></i>

        </button>

        <h1>Dashboard</h1>

    </div>

    <div class="admin-user">

        <i class="fa-solid fa-user"></i>

        <span> 
            <?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?>
        </span>

    </div>

</div>

    <section class="dashboard-content">

        <h2>Bienvenida a CalzaSport</h2>

        <p>

            Desde este panel podrás administrar toda tu tienda.

        </p>

        <div class="stats-grid">

    <div class="stat-card">

        <i class="fa-solid fa-shoe-prints"></i>

        <h3>Productos</h3>

        <span><?= $totalProductos ?></span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-box"></i>

        <h3>Disponibles</h3>

        <span><?= $productosDisponibles ?></span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-dollar-sign"></i>

        <h3>Ventas</h3>

        <span>$<?= number_format($totalVentas, 0, ',', '.') ?></span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-users"></i>

        <h3>Clientes</h3>

        <span><?= $totalClientes ?></span>

    </div>

</div>

    </section>

</main>

<?php

require_once 'layouts/footer.php';

?>