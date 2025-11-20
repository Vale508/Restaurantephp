<?php
require_once __DIR__ . "/../Modelo/PedidoDetalle.php";

class DetallePedidoControlador {

    public function ver() {

        if (!isset($_GET['id'])) {
            echo "No se recibió el ID del pedido.";
            return;
        }

        $idPedido = $_GET['id'];

        $detalle = new PedidoDetalle();
        $detalles = $detalle->listarDetalles($idPedido);

        require __DIR__ . "/../Views/HTML/Detallepedido.php";
    }
}

$controlador = new DetallePedidoControlador();

if (isset($_GET['accion']) && $_GET['accion'] == "ver") {
    $controlador->ver();
}
