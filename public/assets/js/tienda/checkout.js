document.addEventListener("DOMContentLoaded", () => {

    const productosContainer =
        document.getElementById("checkoutProducts");

    const subtotalElement =
        document.getElementById("checkoutSubtotal");

    const totalElement =
        document.getElementById("checkoutTotal");

    const countElement =
        document.getElementById("checkoutItemsCount");

    const continuePayment =
        document.getElementById("continuePayment");


    /*======================================
    OBTENER CARRITO
    ======================================*/

    const carrito =
        JSON.parse(localStorage.getItem("carrito")) || [];


    /*======================================
    SI ESTÁ VACÍO
    ======================================*/

    if (carrito.length === 0) {

        productosContainer.innerHTML = `

            <div class="checkout-empty">

                <i class="fa-solid fa-cart-shopping"></i>

                <p>
                    No hay productos en tu carrito.
                </p>

            </div>

        `;

        if (continuePayment) {

            continuePayment.disabled = true;

        }

        return;

    }


    /*======================================
    MOSTRAR PRODUCTOS
    ======================================*/

    let subtotal = 0;

    let cantidadProductos = 0;


    productosContainer.innerHTML = "";


    carrito.forEach(producto => {

        const precio =
            Number(producto.precio) || 0;

        const cantidad =
            Number(producto.cantidad) || 1;

        const totalProducto =
            precio * cantidad;

        subtotal += totalProducto;

        cantidadProductos += cantidad;


        productosContainer.innerHTML += `

            <div class="checkout-product">

                <div class="checkout-product-image">

                    <img
                        src="assets/img/productos/${producto.imagen}"
                        alt="${producto.nombre}">

                </div>


                <div class="checkout-product-info">

                    <h3>
                        ${producto.nombre}
                    </h3>

                    <p>
                        REF: ${producto.referencia || "N/A"}
                    </p>

                    <p>
                        Talla: ${producto.talla || "N/A"}
                        · Cantidad: ${cantidad}
                    </p>

                </div>


                <div class="checkout-product-price">

                    $${totalProducto.toLocaleString("es-CO")}

                </div>

            </div>

        `;

    });


    /*======================================
    RESUMEN
    ======================================*/

    subtotalElement.textContent =
        "$" + subtotal.toLocaleString("es-CO");


    totalElement.textContent =
        "$" + subtotal.toLocaleString("es-CO");


    countElement.textContent =
        cantidadProductos === 1
            ? "1 producto"
            : `${cantidadProductos} productos`;


    /*======================================
    CONTINUAR AL PAGO
    ======================================*/

    continuePayment.addEventListener("click", () => {

        const nombre =
            document.getElementById("nombre").value.trim();

        const telefono =
            document.getElementById("telefono").value.trim();

        const correo =
            document.getElementById("correo").value.trim();

        const departamento =
            document.getElementById("departamento").value;

        const ciudad =
            document.getElementById("ciudad").value.trim();

        const direccion =
            document.getElementById("direccion").value.trim();

        const barrio =
            document.getElementById("barrio").value.trim();


        /*==================================
        VALIDACIÓN
        ==================================*/

        if (
            !nombre ||
            !telefono ||
            !correo ||
            !departamento ||
            !ciudad ||
            !direccion ||
            !barrio
        ) {

            alert(
                "Por favor completa todos los campos obligatorios."
            );

            return;

        }


        const metodoPago =
            document.querySelector(
                'input[name="metodo_pago"]:checked'
            );


        if (!metodoPago) {

            alert(
                "Selecciona un método de pago para continuar."
            );

            return;

        }


        /*==================================
        DATOS DEL CHECKOUT
        ==================================*/

        const datosCheckout = {

            cliente: {

                nombre: nombre,

                telefono: telefono,

                correo: correo

            },

            envio: {

                departamento:
                    departamento,

                ciudad:
                    ciudad,

                direccion:
                    direccion,

                barrio:
                    barrio,

                codigo_postal:
                    document
                        .getElementById("codigo_postal")
                        .value
                        .trim(),

                indicaciones:
                    document
                        .getElementById("indicaciones")
                        .value
                        .trim()

            },

            metodo_pago:
                metodoPago.value,

            productos:
                carrito,

            subtotal:
                subtotal,

            total:
                subtotal

        };


        localStorage.setItem(

            "checkout",

            JSON.stringify(datosCheckout)

        );


        console.log(
            "Datos del checkout:",
            datosCheckout
        );


        /*
         * Por ahora mostramos una confirmación.
         *
         * Después conectaremos este paso
         * con el sistema real de pago.
         */

        fetch("procesar-checkout.php", {

            method: "POST",

            headers: {

                "Content-Type": "application/json"

            },

            body: JSON.stringify(datosCheckout)

        })
            .then(response => {

                return response.json();

            })
            .then(data => {

                if (data.success) {

                    console.log(
                        "Pedido registrado:",
                        data
                    );


                    /*
                     * Guardamos temporalmente
                     * el número del pedido.
                     */

                    localStorage.setItem(
                        "pedido_id",
                        data.pedido_id
                    );


                    /*
                     * Por ahora iremos a una página
                     * de confirmación.
                     */

                    window.location.href =
                        "pedido-confirmado.php";

                } else {

                    alert(
                        data.message ||
                        "No fue posible registrar el pedido."
                    );

                    console.error(data);

                }

            })
            .catch(error => {

                console.error(
                    "Error:",
                    error
                );

                alert(
                    "Ocurrió un error al procesar tu pedido. Intenta nuevamente."
                );

            });

    });

});