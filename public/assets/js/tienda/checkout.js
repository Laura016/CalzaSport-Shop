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

    console.log("CARRITO ACTUAL:", carrito);


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

    continuePayment.addEventListener("click", async () => {

        /*==================================
        EVITAR DOBLE CLIC
        ==================================*/

        continuePayment.disabled = true;

        continuePayment.querySelector("span").textContent =
            "Preparando pago...";


        /*==================================
        DATOS DEL CLIENTE
        ==================================*/

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

            continuePayment.disabled = false;

            continuePayment.querySelector("span").textContent =
                "Continuar al pago";

            return;

        }


        /*==================================
        MÉTODO DE PAGO
        ==================================*/

        const metodoPago =
            document.querySelector(
                'input[name="metodo_pago"]:checked'
            );


        if (!metodoPago) {

            alert(
                "Selecciona un método de pago para continuar."
            );

            continuePayment.disabled = false;

            continuePayment.querySelector("span").textContent =
                "Continuar al pago";

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


        /*==================================
        GUARDAR CHECKOUT LOCALMENTE
        ==================================*/

        localStorage.setItem(

            "checkout",

            JSON.stringify(datosCheckout)

        );


        console.log(
            "DATOS DEL CHECKOUT:",
            datosCheckout
        );


        /*==================================
        ENVIAR AL SERVIDOR
        ==================================*/

        try {

            const response =
                await fetch(
                    "procesar-checkout.php",
                    {

                        method: "POST",

                        headers: {

                            "Content-Type":
                                "application/json"

                        },

                        body:
                            JSON.stringify(
                                datosCheckout
                            )

                    }
                );


            const data =
                await response.json();


            console.log(
                "RESPUESTA DEL SERVIDOR:",
                data
            );


            /*==================================
            ERROR DEL SERVIDOR
            ==================================*/

            if (!data.success) {

                throw new Error(
                    data.error ||
                    data.message ||
                    "No fue posible crear el pedido."
                );

            }


            /*==================================
            GUARDAR PEDIDO
            ==================================*/

            localStorage.setItem(

                "pedido_id",

                data.pedido_id

            );


            /*==================================
            GUARDAR DATOS WOMPI
            ==================================*/

            localStorage.setItem(

                "wompi_pago",

                JSON.stringify(
                    data.wompi
                )

            );


            console.log(
                "DATOS WOMPI:",
                data.wompi
            );


            /*==================================
            VALIDAR RESPUESTA WOMPI
            ==================================*/

            if (
                !data.wompi ||
                !data.wompi.public_key ||
                !data.wompi.reference ||
                !data.wompi.amount_in_cents ||
                !data.wompi.signature_integrity
            ) {

                throw new Error(
                    "La información necesaria para iniciar el pago no fue recibida correctamente."
                );

            }


            /*==================================
INICIAR CHECKOUT WOMPI
==================================*/

            console.log(
                "Iniciando checkout de Wompi..."
            );


            /*
             * IMPORTANTE:
             *
             * El carrito NO se elimina aquí.
             *
             * Solo se eliminará cuando Wompi
             * confirme que el pago fue aprobado.
             */


            /*==================================
            VERIFICAR QUE WOMPI ESTÉ CARGADO
            ==================================*/

            if (typeof WidgetCheckout === "undefined") {

                throw new Error(
                    "No se pudo cargar el sistema de pagos de Wompi."
                );

            }


            /*==================================
            CREAR CHECKOUT WOMPI
            ==================================*/

            const checkout = new WidgetCheckout({

                currency:
                    data.wompi.currency || "COP",

                amountInCents:
                    data.wompi.amount_in_cents,

                reference:
                    data.wompi.reference,

                publicKey:
                    data.wompi.public_key,

                signature: {

                    integrity:
                        data.wompi.signature_integrity

                }

            });


            /*==================================
            ABRIR CHECKOUT
            ==================================*/

            checkout.open(function (result) {

                console.log(
                    "RESPUESTA DE WOMPI:",
                    result
                );

            });


        } catch (error) {

            console.error(
                "ERROR AL PROCESAR EL PAGO:",
                error
            );


            alert(
                error.message ||
                "Ocurrió un error al preparar el pago."
            );


            continuePayment.disabled = false;

            continuePayment.querySelector("span").textContent =
                "Continuar al pago";

        }

    });

});