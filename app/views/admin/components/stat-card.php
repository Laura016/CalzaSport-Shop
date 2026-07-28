<?php

$titulo = $titulo ?? "Título";
$valor = $valor ?? 0;
$icono = $icono ?? "fa-solid fa-chart-column";
$color = $color ?? "primary";

?>

<div class="inventory-card <?= $color ?>">

    <div class="card-icon">

        <i class="<?= $icono ?>"></i>

    </div>

    <div class="card-info">

        <h3><?= $titulo ?></h3>

        <span><?= $valor ?></span>

    </div>

</div>