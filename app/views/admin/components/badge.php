<?php

$texto = $texto ?? "";
$tipo = $tipo ?? "primary";

?>

<span class="badge badge-<?php echo $tipo; ?>">

    <?php echo $texto; ?>

</span>