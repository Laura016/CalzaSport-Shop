document.addEventListener("DOMContentLoaded", () => {

    const buscador = document.getElementById("buscarProducto");

    if (!buscador) return;

    buscador.addEventListener("keyup", filtrar);

    function filtrar() {

        const texto = buscador.value.toLowerCase();

        document.querySelectorAll(".product-card").forEach(card => {

            const nombre = card.dataset.nombre || "";

            const referencia = card.dataset.referencia || "";

            if (
                nombre.includes(texto) ||
                referencia.includes(texto)
            ) {

                card.style.display = "";

            } else {

                card.style.display = "none";

            }

        });

    }

});