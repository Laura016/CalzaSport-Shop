document.addEventListener("DOMContentLoaded", () => {

    const contenedor = document.getElementById("cartItems");

    if (!contenedor) return;

    let carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];


    /*======================================
    PINTAR CARRITO
    ======================================*/

    pintarCarrito();


    function pintarCarrito() {

        if (carrito.length === 0) {

            contenedor.innerHTML = `

                <div class="empty-cart">

                    <i class="fa-solid fa-cart-shopping"></i>

                    <h2>Tu carrito está vacío</h2>

                    <p>
                        Agrega productos para comenzar tu compra.
                    </p>

                    <a
                        href="catalogo.php"
                        class="checkout-btn">

                        Ir al catálogo

                    </a>

                </div>

            `;

            actualizarResumen();
            actualizarContadores();

            return;

        }


        contenedor.innerHTML = "";


        carrito.forEach((producto, index) => {

            const talla =
                producto.talla || "No especificada";


            const precio =
                Number(producto.precio);


            contenedor.innerHTML += `

                <article class="cart-item">

                    <!--==================================
                    IMAGEN
                    ==================================-->

                    <div class="cart-image">

                        <img
                            src="assets/img/productos/${producto.imagen}"
                            alt="${producto.nombre}">

                    </div>


                    <!--==================================
                    INFORMACIÓN
                    ==================================-->

                    <div class="cart-info">

                        <h3>

                            ${producto.nombre}

                        </h3>


                        <!-- REFERENCIA -->

                        <span class="cart-reference">

                            REF:

                            <strong>

                                ${producto.referencia || "N/A"}

                            </strong>

                        </span>


                        <!-- TALLA -->

                        <span class="cart-size">

                            Talla:

                            <strong>

                                ${talla}

                            </strong>

                        </span>


                        <!-- PRECIO -->

                        <div class="cart-price">

                            $${precio.toLocaleString("es-CO")}

                        </div>


                        <!-- CANTIDAD -->

                        <div class="cart-quantity-box">

                            <span class="quantity-label">

                                Cantidad

                            </span>


                            <div class="cart-quantity">

                                <button
                                    class="menos"
                                    data-index="${index}"
                                    aria-label="Disminuir cantidad">

                                    <i class="fa-solid fa-minus"></i>

                                </button>


                                <span class="quantity-number">

                                    ${producto.cantidad}

                                </span>


                                <button
                                    class="mas"
                                    data-index="${index}"
                                    aria-label="Aumentar cantidad">

                                    <i class="fa-solid fa-plus"></i>

                                </button>

                            </div>

                        </div>

                    </div>


                    <!--==================================
                    ELIMINAR
                    ==================================-->

                    <button
                        class="delete-item"
                        data-index="${index}"
                        aria-label="Eliminar producto">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </article>

            `;

        });


        eventos();

        actualizarResumen();
        actualizarContadores();

    }


    /*======================================
    EVENTOS
    ======================================*/

    function eventos() {


        /* AUMENTAR */

        document.querySelectorAll(".mas").forEach(btn => {

            btn.addEventListener("click", () => {

                const index =
                    Number(btn.dataset.index);

                carrito[index].cantidad++;

                guardar();

            });

        });


        /* DISMINUIR */

        document.querySelectorAll(".menos").forEach(btn => {

            btn.addEventListener("click", () => {

                const index =
                    Number(btn.dataset.index);

                if (carrito[index].cantidad > 1) {

                    carrito[index].cantidad--;

                }

                guardar();

            });

        });


        /* ELIMINAR */

        document.querySelectorAll(".delete-item").forEach(btn => {

            btn.addEventListener("click", () => {

                const index =
                    Number(btn.dataset.index);

                carrito.splice(index, 1);

                guardar();

            });

        });

    }


    /*======================================
    GUARDAR
    ======================================*/

    function guardar() {

        localStorage.setItem(
            "carrito",
            JSON.stringify(carrito)
        );

        pintarCarrito();

    }


    /*======================================
    RESUMEN
    ======================================*/

    function actualizarResumen() {

        let subtotal = 0;


        carrito.forEach(producto => {

            subtotal +=
                Number(producto.precio) *
                Number(producto.cantidad);

        });


        const subtotalElemento =
            document.getElementById("subtotal");


        const totalElemento =
            document.getElementById("total");


        if (subtotalElemento) {

            subtotalElemento.textContent =
                "$" + subtotal.toLocaleString("es-CO");

        }


        if (totalElemento) {

            totalElemento.textContent =
                "$" + subtotal.toLocaleString("es-CO");

        }

    }


    /*======================================
    CONTADORES
    ======================================*/

    function actualizarContadores() {

        const total = carrito.reduce(

            (suma, producto) => {

                return suma + Number(producto.cantidad);

            },

            0

        );


        document.querySelectorAll(".cart-count")
            .forEach(contador => {

                contador.textContent = total;

            });


        document.querySelectorAll(".cart-counter")
            .forEach(contador => {

                contador.textContent = total;

            });

    }

});