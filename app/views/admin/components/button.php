<?php

$href = $href ?? "#";
$texto = $texto ?? "Botón";
$icono = $icono ?? "";
$tipo = $tipo ?? "a";

?>

<?php if($tipo == "button"): ?>

<button class="btn btn-primary">

    <?php if($icono): ?>
        <i class="<?= $icono ?>"></i>
    <?php endif; ?>

    <?= $texto ?>

</button>

<?php else: ?>

<a href="<?= $href ?>" class="btn btn-primary">

    <?php if($icono): ?>
        <i class="<?= $icono ?>"></i>
    <?php endif; ?>

    <?= $texto ?>

</a>

<?php endif; ?>