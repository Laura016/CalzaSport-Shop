<?php

$href = $href ?? "#";
$texto = $texto ?? "Botón";
$icono = $icono ?? "";
$tipo = $tipo ?? "a";

/* variantes:
primary
success
danger
warning
secondary
*/

$color = $color ?? "primary";

?>

<?php if ($tipo == "button"): ?>

    <button class="btn btn-<?= $color ?>">

        <?php if ($icono): ?>

            <i class="<?= $icono ?>"></i>

        <?php endif; ?>

        <span><?= $texto ?></span>

    </button>

<?php else: ?>

    <a href="<?= $href ?>" class="btn btn-<?= $color ?>">

        <?php if ($icono): ?>

            <i class="<?= $icono ?>"></i>

        <?php endif; ?>

        <span><?= $texto ?></span>

    </a>

<?php endif; ?>