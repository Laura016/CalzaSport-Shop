<?php
require_once '../app/views/admin/layouts/header.php';
?>

<div class="admin-content">

    <div class="page-header">

        <div>
            <h1>Promociones</h1>
            <p>
                Administra las promociones y descuentos de CalzaSport.
            </p>
        </div>

        <div>
            <a
                href="admin.php?accion=nuevaPromocion"
                class="btn-primary"
            >
                <i class="fa-solid fa-plus"></i>
                Nueva promoción
            </a>
        </div>

    </div>


    <div class="table-card">

        <div class="table-responsive">

            <table
                id="tablaPromociones"
                class="admin-table"
            >

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Promoción</th>

                        <th>Descuento</th>

                        <th>Inicio</th>

                        <th>Finalización</th>

                        <th>Estado</th>

                        <th>Acciones</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!empty($promociones)): ?>

                        <?php foreach ($promociones as $promocion): ?>

                            <tr>

                                <td>
                                    #<?= (int) $promocion['id'] ?>
                                </td>


                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $promocion['nombre']
                                        ) ?>
                                    </strong>

                                    <br>

                                    <small>
                                        <?= htmlspecialchars(
                                            $promocion['titulo']
                                        ) ?>
                                    </small>

                                </td>


                                <td>

                                    <?php if (
                                        $promocion['tipo_descuento']
                                        === 'porcentaje'
                                    ): ?>

                                        <?= number_format(
                                            $promocion['descuento'],
                                            0
                                        ) ?>%

                                    <?php else: ?>

                                        $<?= number_format(
                                            $promocion['descuento'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                    <?php endif; ?>

                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $promocion['fecha_inicio']
                                    ) ?>
                                </td>


                                <td>
                                    <?= htmlspecialchars(
                                        $promocion['fecha_fin']
                                    ) ?>
                                </td>


                                <td>

                                    <?php if (
                                        $promocion['estado']
                                        === 'Activa'
                                    ): ?>

                                        <span class="status-badge status-active">
                                            Activa
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge status-inactive">
                                            Inactiva
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="admin.php?accion=editarPromocion&id=<?= (int) $promocion['id'] ?>"
                                            class="btn-action btn-edit"
                                            title="Editar promoción"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                        </a>


                                        <a
                                            href="admin.php?accion=eliminarPromocion&id=<?= (int) $promocion['id'] ?>"
                                            class="btn-action btn-delete"
                                            title="Eliminar promoción"
                                            onclick="return confirm('¿Estás segura de eliminar esta promoción?');"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <i class="fa-solid fa-tags"></i>

                                <p>
                                    Aún no tienes promociones creadas.
                                </p>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>