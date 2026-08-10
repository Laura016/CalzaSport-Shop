<?php

require_once '../app/views/tienda/layouts/header.php';

?>

<main class="order-success">

    <div class="success-container">

        <div class="success-card">

            <div class="success-icon">

                <i class="fa-solid fa-check"></i>

            </div>

            <span class="success-tag">
                PEDIDO RECIBIDO
            </span>

            <h1>
                ¡Gracias por tu compra!
            </h1>

            <p class="success-message">

                Hemos recibido correctamente tu pedido.
                Te contactaremos para continuar con el proceso
                de pago y coordinar la entrega.

            </p>

            <div class="order-number">

                <span>
                    Número de pedido
                </span>

                <strong id="pedidoNumero">
                    #0000
                </strong>

            </div>

            <div class="success-info">

                <div class="success-info-item">

                    <i class="fa-solid fa-shield-heart"></i>

                    <div>

                        <strong>
                            Compra segura
                        </strong>

                        <span>
                            Tu pedido ha sido registrado correctamente.
                        </span>

                    </div>

                </div>

                <div class="success-info-item">

                    <i class="fa-solid fa-truck-fast"></i>

                    <div>

                        <strong>
                            Envío a tu destino
                        </strong>

                        <span>
                            Coordinaremos contigo la entrega de tu pedido.
                        </span>

                    </div>

                </div>

            </div>

            <a
                href="catalogo.php"
                class="success-button">

                <i class="fa-solid fa-bag-shopping"></i>

                Seguir comprando

            </a>

            <a
                href="index.php"
                class="success-home">

                Volver al inicio

            </a>

        </div>

    </div>

</main>


<script>

document.addEventListener("DOMContentLoaded", () => {

    const pedidoId =
        localStorage.getItem("pedido_id");

    const numero =
        document.getElementById("pedidoNumero");

    if (pedidoId) {

        numero.textContent =
            "#" + String(pedidoId).padStart(4, "0");

    }

});

</script>


<?php

require_once '../app/views/tienda/layouts/footer.php';

?>