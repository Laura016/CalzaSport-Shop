<?php

require_once __DIR__ . '/../models/Pedido.php';

class CheckoutController
{
    private $pedido;

    public function __construct()
    {
        $this->pedido = new Pedido();
    }


    public function registrarPedido($datos)
    {
        return $this->pedido->crearPedido($datos);
    }
}