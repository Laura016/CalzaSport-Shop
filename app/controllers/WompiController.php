<?php

require_once __DIR__ . '/../config/wompi.php';

class WompiController
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/wompi.php';
    }


    /**
     * =========================================================
     * GENERAR DATOS PARA WOMPI
     * =========================================================
     *
     * Genera:
     *
     * - referencia única
     * - monto en centavos
     * - firma de integridad
     * - llave pública
     *
     * El secreto de integridad permanece en el servidor.
     *
     */

    public function prepararPago($pedidoId, $total)
    {
        /*
         * Generamos una referencia única.
         *
         * El ID del pedido ayuda a relacionar
         * Wompi con nuestra base de datos.
         */

        $referencia =
            'CALZASPORT-' .
            $pedidoId . '-' .
            bin2hex(random_bytes(6));


        /*
         * Wompi recibe el valor en centavos.
         *
         * Ejemplo:
         *
         * $95.000 COP
         *
         * se envía como:
         *
         * 9500000
         */

        $montoCentavos =
            (int) round($total * 100);


        $moneda = 'COP';


        /*
         * Crear cadena de integridad.
         *
         * IMPORTANTE:
         *
         * El orden debe ser:
         *
         * referencia
         * monto
         * moneda
         * secreto
         */

        $cadenaFirma =
            $referencia .
            $montoCentavos .
            $moneda .
            $this->config['integrity_secret'];


        /*
         * Generar SHA256.
         */

        $firma =
            hash('sha256', $cadenaFirma);


        return [

            'success' => true,

            'reference' =>
                $referencia,

            'amount_in_cents' =>
                $montoCentavos,

            'currency' =>
                $moneda,

            'public_key' =>
                $this->config['public_key'],

            'signature' =>
                $firma

        ];
    }
}