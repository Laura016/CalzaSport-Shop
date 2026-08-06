document.addEventListener("DOMContentLoaded", () => {

    const botones = document.querySelectorAll(".agregar-carrito");

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    botones.forEach(boton => {

        boton.addEventListener("click", () => {

            const producto = {

                id: boton.dataset.id,
                nombre: boton.dataset.nombre,
                precio: Number(boton.dataset.precio),
                imagen: boton.dataset.imagen,
                cantidad: 1

            };

            const existente = carrito.find(item => item.id === producto.id);

            if (existente) {

                existente.cantidad++;

            } else {

                carrito.push(producto);

            }

            localStorage.setItem("carrito", JSON.stringify(carrito));

            actualizarContador();

        });

    });

    function actualizarContador() {

    const contadores = document.querySelectorAll(".cart-count");

    const total = carrito.reduce((suma, item) => {

        return suma + item.cantidad;

    }, 0);

    contadores.forEach(contador => {

        contador.textContent = total;

    });

}

    actualizarContador();

});