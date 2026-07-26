<?php

require_once 'layouts/header.php';

require_once 'layouts/sidebar.php';

?>

<main class="main-content">

    <div class="topbar">

        <h1>Dashboard</h1>

        <div class="admin-user">

            <i class="fa-solid fa-user"></i>

            <span>Administrador</span>

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

        <span>5</span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-box"></i>

        <h3>Disponibles</h3>

        <span>5</span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-dollar-sign"></i>

        <h3>Ventas</h3>

        <span>$0</span>

    </div>

    <div class="stat-card">

        <i class="fa-solid fa-users"></i>

        <h3>Clientes</h3>

        <span>0</span>

    </div>

</div>

    </section>

</main>

<?php

require_once 'layouts/footer.php';

?>