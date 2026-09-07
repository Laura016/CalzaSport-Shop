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

            <div>
                <h1>Detalle del pedido</h1>
            </div>

        </div>

        <div class="admin-user">

            <i class="fa-solid fa-user"></i>

            <span>
                <?= htmlspecialchars(
                    $_SESSION['admin_nombre'] ?? 'Administrador'
                ) ?>
            </span>

        </div>

    </div>


    <section class="dashboard-content">

        <!-- ENCABEZADO -->
        <div class="order-detail-header">

            <div>

                <a href="admin.php?accion=ventas" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver a ventas
                </a>

                <h2>
                    Pedido #<?= (int) $pedido['id'] ?>
                </h2>

                <p>
                    Creado el
                    <?= date(
                        'd/m/Y H:i',
                        strtotime($pedido['fecha_creacion'])
                    ) ?>
                </p>

            </div>


            <div class="order-header-status">

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

            </div>

        </div>


        <!-- GRID PRINCIPAL -->
        <div class="order-detail-grid">


            <!-- INFORMACIÓN DEL CLIENTE -->
            <div class="detail-card">

                <div class="detail-card-header">

                    <div class="detail-card-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <h3>Información del cliente</h3>
                        <p>Datos de contacto</p>
                    </div>

                </div>


                <div class="detail-info-list">

                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-user"></i>
                            Nombre
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['cliente_nombre']
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-phone"></i>
                            Teléfono
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['cliente_telefono']
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-envelope"></i>
                            Correo
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['cliente_correo']
                            ) ?>
                        </strong>

                    </div>

                </div>

            </div>



            <!-- INFORMACIÓN DE ENVÍO -->
            <div class="detail-card">

                <div class="detail-card-header">

                    <div class="detail-card-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>
                        <h3>Información de envío</h3>
                        <p>Dirección de entrega</p>
                    </div>

                </div>


                <div class="detail-info-list">

                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-location-dot"></i>
                            Dirección
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['direccion'] ?? 'No registrada'
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-city"></i>
                            Ciudad
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['ciudad'] ?? 'No registrada'
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-map"></i>
                            Departamento
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['departamento'] ?? 'No registrado'
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-house"></i>
                            Barrio
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['barrio'] ?? 'No registrado'
                            ) ?>
                        </strong>

                    </div>


                    <?php if (!empty($pedido['codigo_postal'])): ?>

                        <div class="detail-info-item">

                            <span>
                                <i class="fa-solid fa-envelope-open"></i>
                                Código postal
                            </span>

                            <strong>
                                <?= htmlspecialchars(
                                    $pedido['codigo_postal']
                                ) ?>
                            </strong>

                        </div>

                    <?php endif; ?>


                    <?php if (!empty($pedido['indicaciones'])): ?>

                        <div class="detail-info-item detail-info-full">

                            <span>
                                <i class="fa-solid fa-note-sticky"></i>
                                Indicaciones
                            </span>

                            <strong>
                                <?= nl2br(
                                    htmlspecialchars(
                                        $pedido['indicaciones']
                                    )
                                ) ?>
                            </strong>

                        </div>

                    <?php endif; ?>

                </div>

            </div>


        </div>



        <!-- PRODUCTOS -->
        <div class="detail-card products-detail-card">

            <div class="detail-card-header">

                <div class="detail-card-icon">
                    <i class="fa-solid fa-box-open"></i>
                </div>

                <div>

                    <h3>Productos del pedido</h3>

                    <p>
                        <?= count($detalles) ?>
                        producto(s)
                    </p>

                </div>

            </div>


            <div class="order-products-table">

                <table>

                    <thead>

                        <tr>

                            <th>Producto</th>

                            <th>Referencia</th>

                            <th>Talla</th>

                            <th>Cantidad</th>

                            <th>Precio</th>

                            <th>Subtotal</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($detalles as $detalle): ?>

                            <tr>

                                <td>

                                    <div class="product-order-name">

                                        <i class="fa-solid fa-shoe-prints"></i>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $detalle['nombre_producto']
                                            ) ?>
                                        </strong>

                                    </div>

                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $detalle['referencia'] ?? 'N/A'
                                    ) ?>
                                </td>


                                <td>

                                    <span class="size-badge">

                                        <?= htmlspecialchars(
                                            $detalle['talla']
                                        ) ?>

                                    </span>

                                </td>


                                <td>
                                    <?= (int) $detalle['cantidad'] ?>
                                </td>


                                <td>

                                    $<?= number_format(
                                        $detalle['precio'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </td>


                                <td>

                                    <strong>

                                        $<?= number_format(
                                            $detalle['subtotal'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                    </strong>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>



        <!-- PARTE INFERIOR -->
        <div class="order-bottom-grid">


            <!-- INFORMACIÓN DEL PAGO -->
            <div class="detail-card">

                <div class="detail-card-header">

                    <div class="detail-card-icon">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>

                    <div>

                        <h3>Información del pago</h3>

                        <p>
                            Información de la transacción
                        </p>

                    </div>

                </div>


                <div class="detail-info-list">

                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-wallet"></i>
                            Método de pago
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['metodo_pago']
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-hashtag"></i>
                            Referencia
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['referencia_pago'] ?? 'N/A'
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-receipt"></i>
                            Transacción
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $pedido['transaccion_id'] ?? 'N/A'
                            ) ?>
                        </strong>

                    </div>


                    <div class="detail-info-item">

                        <span>
                            <i class="fa-solid fa-circle-check"></i>
                            Estado del pago
                        </span>


                        <?php if ($pedido['estado_pago'] === 'Pagado'): ?>

                            <span class="status-badge status-paid">
                                <i class="fa-solid fa-circle-check"></i>
                                Pagado
                            </span>

                        <?php elseif (
                            $pedido['estado_pago'] === 'Rechazado'
                        ): ?>

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

                    </div>

                </div>

            </div>



            <!-- RESUMEN -->
            <div class="detail-card order-summary-card">

                <div class="detail-card-header">

                    <div class="detail-card-icon">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>

                    <div>

                        <h3>Resumen del pedido</h3>

                        <p>
                            Total de la compra
                        </p>

                    </div>

                </div>


                <div class="order-summary">

                    <div class="summary-row">

                        <span>Subtotal</span>

                        <strong>
                            $<?= number_format(
                                $pedido['subtotal'],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>Costo de envío</span>

                        <strong>
                            $<?= number_format(
                                $pedido['costo_envio'],
                                0,
                                ',',
                                '.'
                            ) ?>
                        </strong>

                    </div>


                    <div class="summary-divider"></div>


                    <div class="summary-total">

                        <span>Total</span>

                        <strong>

                            $<?= number_format(
                                $pedido['total'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </strong>

                    </div>

                </div>

            </div>

        </div>



        <!-- ACCIONES -->
        <div class="detail-card order-actions-card">

            <div class="detail-card-header">

                <div class="detail-card-icon">
                    <i class="fa-solid fa-sliders"></i>
                </div>

                <div>

                    <h3>Acciones del pedido</h3>

                    <p>
                        Gestiona el estado del envío
                    </p>

                </div>

            </div>


            <div class="order-actions">

                <?php if ($pedido['estado_pedido'] === 'Pendiente'): ?>

                    <?php if ($pedido['estado_pago'] === 'Pagado'): ?>

                        <form method="POST" action="admin.php?accion=actualizarEstadoPedido">

                            <input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>">

                            <input type="hidden" name="nuevo_estado" value="Preparando">

                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <button type="submit" class="order-action-btn btn-prepare">
                                <i class="fa-solid fa-box"></i>
                                Preparar pedido
                            </button>

                        </form>

                    <?php else: ?>

                        <div class="order-action-disabled">

                            <i class="fa-solid fa-lock"></i>

                            <span>
                                El pedido no puede prepararse hasta que
                                el pago sea aprobado.
                            </span>

                        </div>

                    <?php endif; ?>


                <?php elseif ($pedido['estado_pedido'] === 'Preparando'): ?>

                    <form method="POST" action="admin.php?accion=actualizarEstadoPedido">

                        <input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>">

                        <input type="hidden" name="nuevo_estado" value="Enviado">

                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <button type="submit" class="order-action-btn btn-shipped">
                            <i class="fa-solid fa-truck"></i>
                            Marcar como enviado
                        </button>

                    </form>


                <?php elseif ($pedido['estado_pedido'] === 'Enviado'): ?>

                    <form method="POST" action="admin.php?accion=actualizarEstadoPedido">

                        <input type="hidden" name="pedido_id" value="<?= (int) $pedido['id'] ?>">

                        <input type="hidden" name="nuevo_estado" value="Entregado">

                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <button type="submit" class="order-action-btn btn-delivered">
                            <i class="fa-solid fa-circle-check"></i>
                            Marcar como entregado
                        </button>

                    </form>


                <?php elseif ($pedido['estado_pedido'] === 'Entregado'): ?>

                    <div class="order-completed-message">

                        <i class="fa-solid fa-circle-check"></i>

                        <div>

                            <strong>
                                Pedido completado
                            </strong>

                            <span>
                                Este pedido ya fue marcado como entregado.
                            </span>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>


<?php
require_once 'layouts/footer.php';
?>