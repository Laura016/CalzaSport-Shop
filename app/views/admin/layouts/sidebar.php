<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo">

        <h2>CalzaSport</h2>

        <span>ADMIN</span>

    </div>


    <!-- MENÚ PRINCIPAL -->
    <nav class="sidebar-nav">

        <ul>

            <li class="active">
                <a href="admin.php">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            <li>
                <a href="admin.php?accion=productos">
                    <i class="fa-solid fa-shoe-prints"></i>
                    <span>Productos</span>
                </a>
            </li>


            <li>
                <a href="admin.php?accion=nuevo">
                    <i class="fa-solid fa-plus"></i>
                    <span>Agregar Producto</span>
                </a>
            </li>


            <li>
                <a href="admin.php?accion=inventario">
                    <i class="fa-solid fa-box"></i>
                    <span>Inventario</span>
                </a>
            </li>


            <li>
                <a href="admin.php?accion=ventas">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Ventas</span>
                </a>
            </li>

        </ul>

    </nav>


    <!-- OPCIONES INFERIORES -->
    <div class="sidebar-bottom">


        <!-- CONFIGURACIÓN -->
        <div class="sidebar-dropdown">

            <button type="button" class="sidebar-link sidebar-dropdown-toggle">

                <i class="fa-solid fa-gear"></i>

                <span>Configuración</span>

                <i class="fa-solid fa-chevron-down dropdown-arrow"></i>

            </button>


            <div class="sidebar-dropdown-menu">

                <a href="#" class="sidebar-dropdown-link">

                    <i class="fa-solid fa-sliders"></i>

                    <span>Ajustes</span>

                </a>

            </div>

        </div>


        <!-- CERRAR SESIÓN -->
        <a href="admin-logout.php" class="sidebar-link sidebar-logout">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Cerrar sesión</span>

        </a>

    </div>

</aside>