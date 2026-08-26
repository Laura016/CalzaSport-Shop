<?php

require_once '../app/views/tienda/layouts/header.php';

?>

<main class="checkout-page">

    <!--======================================
    ENCABEZADO
    ======================================-->

    <section class="checkout-header">

        <span class="checkout-tag">
            FINALIZAR COMPRA
        </span>

        <h1>
            Completa tu pedido
        </h1>

        <p>
            Ingresa tus datos para que podamos llevar tu pedido hasta ti.
        </p>

    </section>


    <!--======================================
    CHECKOUT
    ======================================-->

    <section class="checkout-container">


        <!--==================================
        FORMULARIO
        ==================================-->

        <div class="checkout-form">


            <!--==================================
            DATOS PERSONALES
            ==================================-->

            <div class="checkout-card">

                <div class="checkout-card-header">

                    <div class="checkout-icon">

                        <i class="fa-solid fa-user"></i>

                    </div>

                    <div>

                        <h2>
                            Información personal
                        </h2>

                        <p>
                            Datos de contacto para tu pedido.
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <div class="form-group">

                        <label for="nombre">

                            Nombre completo
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Ej. Laura Muñetón"
                            autocomplete="name"
                            required>

                    </div>


                    <div class="form-group">

                        <label for="telefono">

                            Teléfono
                            <span>*</span>

                        </label>

                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            placeholder="Ej. 300 123 4567"
                            autocomplete="tel"
                            required>

                    </div>


                    <div class="form-group form-full">

                        <label for="correo">

                            Correo electrónico
                            <span>*</span>

                        </label>

                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            placeholder="Ej. correo@email.com"
                            autocomplete="email"
                            required>

                    </div>

                </div>

            </div>


            <!--==================================
            DIRECCIÓN DE ENVÍO
            ==================================-->

            <div class="checkout-card">

                <div class="checkout-card-header">

                    <div class="checkout-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <div>

                        <h2>
                            Dirección de entrega
                        </h2>

                        <p>
                            ¿Dónde quieres recibir tu pedido?
                        </p>

                    </div>

                </div>


                <div class="form-grid">


                    <div class="form-group">

                        <label for="departamento">

                            Departamento
                            <span>*</span>

                        </label>

                        <select
                            id="departamento"
                            name="departamento"
                            required>

                            <option value="">
                                Selecciona un departamento
                            </option>

                            <option value="Antioquia">
                                Antioquia
                            </option>

                            <option value="Bogotá D.C.">
                                Bogotá D.C.
                            </option>

                            <option value="Atlántico">
                                Atlántico
                            </option>

                            <option value="Bolívar">
                                Bolívar
                            </option>

                            <option value="Caldas">
                                Caldas
                            </option>

                            <option value="Cundinamarca">
                                Cundinamarca
                            </option>

                            <option value="Risaralda">
                                Risaralda
                            </option>

                            <option value="Santander">
                                Santander
                            </option>

                            <option value="Valle del Cauca">
                                Valle del Cauca
                            </option>

                            <option value="Otro">
                                Otro
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="ciudad">

                            Ciudad
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="ciudad"
                            name="ciudad"
                            placeholder="Ej. Medellín"
                            autocomplete="address-level2"
                            required>

                    </div>


                    <div class="form-group form-full">

                        <label for="direccion">

                            Dirección
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="direccion"
                            name="direccion"
                            placeholder="Ej. Calle 10 # 20-30"
                            autocomplete="street-address"
                            required>

                    </div>


                    <div class="form-group">

                        <label for="barrio">

                            Barrio
                            <span>*</span>

                        </label>

                        <input
                            type="text"
                            id="barrio"
                            name="barrio"
                            placeholder="Ej. El Poblado"
                            required>

                    </div>


                    <div class="form-group">

                        <label for="codigo_postal">

                            Código postal

                            <small>
                                Opcional
                            </small>

                        </label>

                        <input
                            type="text"
                            id="codigo_postal"
                            name="codigo_postal"
                            placeholder="Ej. 050021"
                            autocomplete="postal-code">

                    </div>


                    <div class="form-group form-full">

                        <label for="indicaciones">

                            Indicaciones para la entrega

                            <small>
                                Opcional
                            </small>

                        </label>

                        <textarea
                            id="indicaciones"
                            name="indicaciones"
                            rows="4"
                            placeholder="Ej. Dejar en portería, apartamento 302..."></textarea>

                    </div>

                </div>

            </div>


            <!--==================================
            MÉTODO DE PAGO
            ==================================-->

            <div class="checkout-card">

                <div class="checkout-card-header">

                    <div class="checkout-icon">

                        <i class="fa-solid fa-credit-card"></i>

                    </div>

                    <div>

                        <h2>
                            Método de pago
                        </h2>

                        <p>
                            Selecciona cómo quieres pagar tu pedido.
                        </p>

                    </div>

                </div>


                <div class="payment-options">


                    <label class="payment-option">

                        <input
                            type="radio"
                            name="metodo_pago"
                            value="nequi">

                        <div class="payment-option-content">

                            <div class="payment-option-logo">

                                <img
                                    src="assets/img/pagos/nequi.png"
                                    alt="Nequi">

                            </div>

                            <div>

                                <strong>
                                    Nequi
                                </strong>

                                <span>
                                    Pago mediante Nequi
                                </span>

                            </div>

                        </div>

                        <i class="fa-solid fa-circle-check payment-check"></i>

                    </label>


                    <label class="payment-option">

                        <input
                            type="radio"
                            name="metodo_pago"
                            value="bancolombia">

                        <div class="payment-option-content">

                            <div class="payment-option-logo">

                                <img
                                    src="assets/img/pagos/bancolombia.png"
                                    alt="Bancolombia">

                            </div>

                            <div>

                                <strong>
                                    Bancolombia
                                </strong>

                                <span>
                                    Transferencia bancaria
                                </span>

                            </div>

                        </div>

                        <i class="fa-solid fa-circle-check payment-check"></i>

                    </label>


                    <label class="payment-option">

                        <input
                            type="radio"
                            name="metodo_pago"
                            value="pse">

                        <div class="payment-option-content">

                            <div class="payment-option-logo">

                                <img
                                    src="assets/img/pagos/pse.png"
                                    alt="PSE">

                            </div>

                            <div>

                                <strong>
                                    PSE
                                </strong>

                                <span>
                                    Pago seguro en línea
                                </span>

                            </div>

                        </div>

                        <i class="fa-solid fa-circle-check payment-check"></i>

                    </label>


                    <label class="payment-option">

                        <input
                            type="radio"
                            name="metodo_pago"
                            value="tarjeta">

                        <div class="payment-option-content">

                            <div class="payment-option-logo card-logos">

                                <img
                                    src="assets/img/pagos/visa.png"
                                    alt="Visa">

                                <img
                                    src="assets/img/pagos/mastercard.png"
                                    alt="Mastercard">

                            </div>

                            <div>

                                <strong>
                                    Tarjeta
                                </strong>

                                <span>
                                    Visa o Mastercard
                                </span>

                            </div>

                        </div>

                        <i class="fa-solid fa-circle-check payment-check"></i>

                    </label>


                </div>

            </div>

        </div>


        <!--==================================
        RESUMEN
        ==================================-->

        <aside class="checkout-summary">

            <div class="checkout-summary-header">

                <h2>
                    Tu pedido
                </h2>

                <span id="checkoutItemsCount">
                    0 productos
                </span>

            </div>


            <div
                id="checkoutProducts"
                class="checkout-products">

            </div>


            <div class="checkout-summary-lines">

                <div class="summary-line">

                    <span>
                        Subtotal
                    </span>

                    <strong id="checkoutSubtotal">
                        $0
                    </strong>

                </div>


                <div class="summary-line">

                    <span>
                        Envío
                    </span>

                    <strong>
                        A calcular
                    </strong>

                </div>

            </div>


            <div class="checkout-total">

                <span>
                    Total
                </span>

                <strong id="checkoutTotal">
                    $0
                </strong>

            </div>


            <button
                type="button"
                id="continuePayment"
                class="checkout-submit">

                <span>
                    Continuar al pago
                </span>

                <i class="fa-solid fa-arrow-right"></i>

            </button>


            <a
                href="carrito.php"
                class="back-cart">

                <i class="fa-solid fa-arrow-left"></i>

                Volver al carrito

            </a>


            <div class="secure-checkout">

                <i class="fa-solid fa-shield-halved"></i>

                <div>

                    <strong>
                        Compra segura
                    </strong>

                    <span>
                        Protegemos tus datos durante el proceso.
                    </span>

                </div>

            </div>

        </aside>

    </section>

</main>


<script src="https://checkout.wompi.co/widget.js"></script>

<script src="assets/js/tienda/checkout.js"></script>


<?php

require_once '../app/views/tienda/layouts/footer.php';

?>