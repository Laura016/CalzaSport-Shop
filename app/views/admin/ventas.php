<?php
require_once 'layouts/header.php';
require_once 'layouts/sidebar.php';
?>

<main class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">

        <div class="left-topbar">

            <button id="menuToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <h1>Ventas</h1>

        </div>

        <div class="admin-user">

            <i class="fa-solid fa-user"></i>

            <span>
                <?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Administrador') ?>
            </span>

        </div>

    </div>


    <!-- CONTENIDO -->
    <section class="dashboard-content">

        <div class="page-header">

            <div>
                <h2>Pedidos y ventas</h2>

                <p>
                    Consulta y administra las compras realizadas en CalzaSport.
                </p>
            </div>

        </div>


        <!-- TABLA -->
        <div class="table-container">

            <table id="tablaVentas" class="admin-table">

                <thead>

                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Pago</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($pedidos)): ?>

                        <?php foreach ($pedidos as $pedido): ?>

                            <tr>

                                <!-- PEDIDO -->
                                <td>
                                    <strong>
                                        #<?= (int) $pedido['id'] ?>
                                    </strong>
                                </td>


                                <!-- CLIENTE -->
                                <td>

                                    <div class="cliente-info">

                                        <strong>
                                            <?= htmlspecialchars($pedido['cliente_nombre']) ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars($pedido['cliente_correo']) ?>
                                        </small>

                                    </div>

                                </td>


                                <!-- FECHA -->
                                <td>

                                    <?= date(
                                        'd/m/Y H:i',
                                        strtotime($pedido['fecha_creacion'])
                                    ) ?>

                                </td>


                                <!-- TOTAL -->
                                <td>

                                    <strong>
                                        $<?= number_format(
                                            $pedido['total'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>
                                    </strong>

                                </td>


                                <!-- PAGO -->
                                <td>

                                    <?php if ($pedido['estado_pago'] === 'Pagado'): ?>

                                        <span class="status-badge status-paid">
                                            <i class="fa-solid fa-circle-check"></i>
                                            Pagado
                                        </span>

                                    <?php elseif ($pedido['estado_pago'] === 'Rechazado'): ?>

                                        <span class="status-badge status-rejected">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            Rechazado
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-pending">
                                            <i class="fa-solid fa-clock"></i>
                                            Pendiente
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- ESTADO PEDIDO -->
                                <td>

                                    <?php

                                    $estado = $pedido['estado_pedido'];

                                    $claseEstado = 'status-pending';

                                    $iconoEstado = 'fa-clock';

                                    if ($estado === 'Preparando') {
                                        $claseEstado = 'status-preparing';
                                        $iconoEstado = 'fa-box';
                                    }

                                    if ($estado === 'Enviado') {
                                        $claseEstado = 'status-shipped';
                                        $iconoEstado = 'fa-truck';
                                    }

                                    if ($estado === 'Entregado') {
                                        $claseEstado = 'status-delivered';
                                        $iconoEstado = 'fa-circle-check';
                                    }

                                    if ($estado === 'Cancelado') {
                                        $claseEstado = 'status-rejected';
                                        $iconoEstado = 'fa-circle-xmark';
                                    }

                                    ?>

                                    <span class="status-badge <?= $claseEstado ?>">

                                        <i class="fa-solid <?= $iconoEstado ?>"></i>

                                        <?= htmlspecialchars($estado) ?>

                                    </span>

                                </td>


                                <!-- ACCIONES -->
                                <td>

                                    <a
                                        href="#"
                                        class="btn-action btn-view"
                                        title="Ver pedido"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="7"
                                class="empty-table"
                            >

                                <div>

                                    <i class="fa-solid fa-box-open"></i>

                                    <p>
                                        No hay pedidos registrados.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main>


<?php
require_once 'layouts/footer.php';
?>