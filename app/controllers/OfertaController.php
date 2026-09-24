<?php

require_once '../app/models/Promocion.php';

class OfertaController
{
    private $promocion;

    public function __construct()
    {
        $this->promocion = new Promocion();
    }

    public function index()
    {
        $promociones = $this->promocion->obtenerActivas();

        $productosPorPromocion = [];

        foreach ($promociones as $promocion) {

            $productos =
                $this->promocion->obtenerProductos(
                    $promocion['id']
                );

            foreach ($productos as &$producto) {

                $producto['precio_original'] =
                    (float) $producto['precio'];

                $producto['precio_promocional'] =
                    $this->promocion->calcularPrecioPromocional(
                        $producto['precio_original'],
                        $promocion['tipo_descuento'],
                        $promocion['descuento']
                    );
            }

            unset($producto);

            $productosPorPromocion[$promocion['id']] =
                $productos;
        }

        require_once '../app/views/tienda/ofertas.php';
    }
}