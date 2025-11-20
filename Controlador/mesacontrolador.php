<?php
require_once __DIR__ . "/../Modelo/Mesa.php";

class MesaControlador {
    private $modelMesa;

    public function __construct() {
        $this->modelMesa = new Mesa();
    }

    public function RegistrarMesa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = $_POST['numero_mesa'];
            $capacidad = $_POST['capacidad'];
            $ubicacion = $_POST['ubicacion'];
            $disponible = $_POST['disponible'];

            $this->modelMesa->RegistrarMesa($numero, $capacidad, $ubicacion, $disponible);
            header("Location: ../Views/HTML/Reservas.php");
        }
    }

    public function ActualizarMesa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_mesa'];
            $disponible = $_POST['disponible'];
            $this->modelMesa->ActualizarEstado($id, $disponible);
            header("Location: ../Views/HTML/Reservas.php");
        }
    }

    public function EliminarMesa() {
        if (isset($_GET['id'])) {
            $this->modelMesa->EliminarMesa($_GET['id']);
            header("Location: ../Views/HTML/Reservas.php");
        }
    }

    public function ListarMesas() {
        return $this->modelMesa->ListarMesas();
    }
}

// Ejecución directa desde formularios
$mesaController = new MesaControlador();
if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'registrar_mesa') $mesaController->RegistrarMesa();
    if ($_POST['accion'] == 'actualizar_mesa') $mesaController->ActualizarMesa();
}
if (isset($_GET['eliminar_mesa'])) $mesaController->EliminarMesa();
?>
