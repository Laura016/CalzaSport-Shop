<button
    class="btn-producto agregar-carrito"
    data-id="<?= $producto['id']; ?>"
    data-nombre="<?= htmlspecialchars($producto['nombre']); ?>"
    data-precio="<?= $producto['precio']; ?>"
    data-imagen="<?= $producto['imagen']; ?>">

    <i class="fa-solid fa-cart-shopping"></i>

    Agregar al carrito

</button>