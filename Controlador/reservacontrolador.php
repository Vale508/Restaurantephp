<?php
require_once __DIR__ . "/../Modelo/Reservas.php";

class ReservaControlador {
    private $modelReserva;

    public function __construct() {
        $this->modelReserva = new Reserva();
    }

    public function ListarReservas() {
        return $this->modelReserva->ListarReservas();
    }

    public function ActualizarEstadoReserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_reserva'];
            $estado = $_POST['estado'];
            $this->modelReserva->ActualizarEstadoReserva($id, $estado);
            header("Location: ../Views/HTML/Reservas.php");
        }
    }

    // Crear una nueva reserva
    public function CrearReserva() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero_personas = $_POST['numero_personas'];
            $id_usuario = $_POST['id_usuario'];
            $id_mesa = $_POST['id_mesa'];
            $fecha_reserva = $_POST['fecha_reserva'];
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 'Pendiente';

            $this->modelReserva->CrearReserva($numero_personas, $id_usuario, $id_mesa, $fecha_reserva, $estado);
            $_SESSION['mensaje'] = "Reserva creada correctamente.";
            header("Location: ../Views/HTML/Reservas.php");
            exit;
        }
    }
}

$reservaController = new ReservaControlador();
if (isset($_POST['accion']) && $_POST['accion'] == 'actualizar_reserva') {
    $reservaController->ActualizarEstadoReserva();
} elseif (isset($_POST['accion']) && $_POST['accion'] == 'crear_reserva') {
    session_start();
    $reservaController->CrearReserva();
}
?>
