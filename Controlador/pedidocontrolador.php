<?php
require_once __DIR__ . "/../Modelo/Pedido.php";
require_once __DIR__ . "/../Modelo/PedidoDetalle.php";

class PedidoControlador {
    private $pedido;
    private $detalle;

    public function __construct() {
        $this->pedido = new Pedido();
        $this->detalle = new PedidoDetalle();
    }

    public function listarPedidos() {
        return $this->pedido->listarPedidos();
    }

    public function filtrarPorFecha($inicio, $fin) {
        return $this->pedido->filtrarPorFecha($inicio . " 00:00:00", $fin . " 23:59:59");
    }

    public function registrarPedido() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $tipoPedido = $_POST["tipoPedido"] ?? "mesa";
            $productos = $_POST["platos"];
            $cantidades = $_POST["cantidades"];
            $precios = $_POST["precios"];

            if ($tipoPedido === "mesa") {
                $idMesa = $_POST["Id_Mesa"];

                if ($this->pedido->mesaOcupada($idMesa)) {
                    echo "Error: La mesa ya tiene un pedido activo.";
                    exit;
                }

                $idPedido = $this->pedido->crearPedido($idMesa);
            } else {
                $direccion = $_POST["direccionEntrega"] ?? "";
                $telefono = $_POST["telefonoEntrega"] ?? "";
                $idPedido = $this->pedido->crearPedidoDomicilio($direccion, $telefono);
            }

            for ($i = 0; $i < count($productos); $i++) {
                $this->detalle->agregarDetalle(
                    $idPedido,
                    $productos[$i],
                    $cantidades[$i],
                    $precios[$i]
                );
            }

            $this->pedido->actualizarTotal($idPedido);

            header("Location: ../Views/HTML/Pedidos.php");
        }
    }

    public function cambiarEstado() {
        $this->pedido->actualizarEstado($_POST["Id_Pedido"], $_POST["Estado"]);
        header("Location: ../Views/HTML/Pedidos.php");
    }
}

$controller = new PedidoControlador();

if (isset($_POST["accion"])) {
    if ($_POST["accion"] === "registrar") $controller->registrarPedido();
    if ($_POST["accion"] === "estado") $controller->cambiarEstado();
}
