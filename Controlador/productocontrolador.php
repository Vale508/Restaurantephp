<?php
require_once __DIR__ . '/../Modelo/Productos.php';

class ProductoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Plato();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // LISTAR PRODUCTOS
    public function listarProductos($soloDisponibles = false) {
        return $this->modelo->listarPlatos($soloDisponibles);
    }

    // REGISTRAR PLATO
    public function registrarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
            $nombre = $_POST['Nombre'];
            $descripcion = $_POST['Descripcion'];
            $precio = $_POST['Precio'];
            $categoria = $_POST['Categoria'];

            $imagen = isset($_POST['Imagen']) ? trim($_POST['Imagen']) : "";
            $disponible = isset($_POST['Disponible']) ? (int)$_POST['Disponible'] : 1;

            try {
                $this->modelo->registrarPlato($nombre, $descripcion, $precio, $categoria, $imagen, null, $disponible);
                $_SESSION['mensaje'] = "Plato registrado correctamente.";
            } catch (Exception $e) {
                $msg = $e->getMessage();
                if (stripos($msg, 'duplicate') !== false || stripos($msg, 'duplicada') !== false) {
                    $_SESSION['mensaje'] = "No se pudo registrar el plato: clave duplicada en la base de datos.";
                } else {
                    $_SESSION['mensaje'] = "Error al registrar el plato: " . $msg;
                }
            }
            $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'crudplatos.php';
            header("Location: ../Views/HTML/" . $redirect);
            exit;
        }
    }

    // EDITAR PLATO
    public function editarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
            $id = $_POST['id'];
            $nombre = $_POST['Nombre'];
            $descripcion = $_POST['Descripcion'];
            $precio = $_POST['Precio'];
            $categoria = isset($_POST['Categoria']) ? trim($_POST['Categoria']) : '';
            if ($categoria === '' && isset($_POST['CategoriaActual'])) {
                $categoria = $_POST['CategoriaActual'];
            }
            $imagen = isset($_POST['ImagenActual']) ? trim($_POST['ImagenActual']) : '';
            if (isset($_POST['Imagen']) && trim($_POST['Imagen']) !== '') {
                $imagen = trim($_POST['Imagen']);
            }
            $disponible = isset($_POST['Disponible']) ? (int)$_POST['Disponible'] : 1;

            $this->modelo->editarPlato($id, $nombre, $descripcion, $precio, $categoria, $imagen, null, $disponible);

            $_SESSION['mensaje'] = "Plato editado correctamente.";
            $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'crudplatos.php';
            header("Location: ../Views/HTML/" . $redirect);
            exit;
        }
    }

    // ELIMINAR PLATO
    public function eliminarPlato() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $id = $_POST['id'];
            try {
                $this->modelo->eliminarPlato($id);
                $_SESSION['mensaje'] = "Plato eliminado correctamente.";
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "No se pudo eliminar el plato: " . $e->getMessage();
            }
            $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'crudplatos.php';
            header("Location: ../Views/HTML/" . $redirect);
            exit;
        }
    }

    // BUSCAR PLATOS
    public function buscarPlatos($nombre) {
        return $this->modelo->buscarPlatos($nombre);
    }

    // PROCESAR SOLICITUDES
    public function procesar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['registrar'])) {
                $this->registrarPlato();
            } elseif (isset($_POST['editar'])) {
                $this->editarPlato();
            } elseif (isset($_POST['eliminar'])) {
                $this->eliminarPlato();
            }
        }

        if (isset($_GET['buscar'])) {
            return $this->buscarPlatos($_GET['buscar']);
        }
    }
}

if (basename($_SERVER['PHP_SELF']) === 'productocontrolador.php') {
    $controlador = new ProductoControlador();
    $controlador->procesar();
}
?>