document.addEventListener("DOMContentLoaded", () => {

    /*======================================
    SELECCIÓN DE TALLAS
    ======================================*/

    const tarjetas = document.querySelectorAll(".product-card");

    tarjetas.forEach(tarjeta => {

        const tallas = tarjeta.querySelectorAll(".size-option");

        tallas.forEach(talla => {

            talla.addEventListener("click", () => {

                /* Quitar selección anterior */

                tallas.forEach(item => {

                    item.classList.remove("selected");

                });


                /* Seleccionar nueva talla */

                talla.classList.add("selected");


                /* Guardar talla seleccionada */

                tarjeta.dataset.tallaSeleccionada =
                    talla.dataset.talla;

            });

        });

    });


    /*======================================
    BOTONES AGREGAR AL CARRITO
    ======================================*/

    const botones = document.querySelectorAll(".agregar-carrito");

    let carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];


    botones.forEach(boton => {

        boton.addEventListener("click", () => {

            const tarjeta =
                boton.closest(".product-card");


            if (!tarjeta) return;


            /*==================================
            COMPROBAR TALLA
            ==================================*/

            const tallaSeleccionada =
                tarjeta.dataset.tallaSeleccionada;


            if (!tallaSeleccionada) {

                mostrarMensajeTalla();

                return;

            }


            /*==================================
            DATOS DEL PRODUCTO
            ==================================*/

            const producto = {

                id: boton.dataset.id,

                nombre: boton.dataset.nombre,

                referencia: boton.dataset.referencia,

                precio: Number(boton.dataset.precio),

                imagen: boton.dataset.imagen,

                talla: tallaSeleccionada,

                cantidad: 1

            };


            /*==================================
            BUSCAR PRODUCTO EXISTENTE
            ==================================*/

            const existente = carrito.find(item =>

                item.id === producto.id &&

                item.talla === producto.talla

            );


            if (existente) {

                existente.cantidad++;

            } else {

                carrito.push(producto);

            }


            /*==================================
            GUARDAR
            ==================================*/

            localStorage.setItem(

                "carrito",

                JSON.stringify(carrito)

            );


            actualizarContador();


            /*==================================
            MENSAJE
            ==================================*/

            mostrarMensajeAgregado();

        });

    });


    /*======================================
    CONTADOR
    ======================================*/

    function actualizarContador() {

        const contadores =
            document.querySelectorAll(".cart-count");


        const total = carrito.reduce(

            (suma, item) => {

                return suma + item.cantidad;

            },

            0

        );


        contadores.forEach(contador => {

            contador.textContent = total;

        });


        /* Contador del botón flotante */

        const flotantes =
            document.querySelectorAll(".cart-counter");


        flotantes.forEach(contador => {

            contador.textContent = total;

        });

    }


    /*======================================
    MENSAJE: FALTA TALLA
    ======================================*/

    function mostrarMensajeTalla() {

        mostrarNotificacion(

            "Selecciona una talla antes de agregar el producto.",

            "error"

        );

    }


    /*======================================
    MENSAJE: PRODUCTO AGREGADO
    ======================================*/

    function mostrarMensajeAgregado() {

        mostrarNotificacion(

            "Producto agregado al carrito.",

            "success"

        );

    }


    /*======================================
    NOTIFICACIÓN
    ======================================*/

    function mostrarNotificacion(mensaje, tipo) {

        const anterior =
            document.querySelector(".cart-notification");


        if (anterior) {

            anterior.remove();

        }


        const notificacion =
            document.createElement("div");


        notificacion.className =
            `cart-notification ${tipo}`;


        notificacion.innerHTML = `

            <i class="fa-solid ${
                tipo === "success"
                    ? "fa-circle-check"
                    : "fa-circle-exclamation"
            }"></i>

            <span>${mensaje}</span>

        `;


        document.body.appendChild(notificacion);


        setTimeout(() => {

            notificacion.classList.add("show");

        }, 10);


        setTimeout(() => {

            notificacion.classList.remove("show");


            setTimeout(() => {

                notificacion.remove();

            }, 300);

        }, 2500);

    }


    /*======================================
    INICIALIZAR CONTADOR
    ======================================*/

    actualizarContador();

});