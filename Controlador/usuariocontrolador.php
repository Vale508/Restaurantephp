<?php
require_once "../Config/conexion.php";
require_once "../Modelo/usuario.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class UsuarioController {
    private $modelusuario;

    public function __construct() {
        $this->modelusuario = new Usuario();
        session_start(); 
    }

    public function validarusu() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $this->modelusuario->login($_POST['email'], $_POST['password']);
        if($usuario){
            $_SESSION['usuario'] = $usuario;

            // 🔹 Redirección según tipo de usuario
            if ($usuario['Tipo_Usuario'] == 'Administrador') {
                header("Location: ../Views/HTML/Perfil.php");
            } else {
                header("Location: ../Views/Index.html"); 
            }
            exit;
        } else {
            $_SESSION['mensaje'] = "Credencial no válida.";
            header("Location: ../Views/HTML/Login.php");
            exit;
        }
    } else {
        header("Location: ../Views/HTML/Login.php");
        exit;
    }
}

    public function cerrarsesion() {
        session_destroy();
        header("Location: ../Views/HTML/Login.php");
        exit;
    }

    public function mostrarPerfil() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: ../Views/HTML/Login.php");
            exit;
        }

        $usuario = $_SESSION['usuario'];
        $usuarios = $this->modelusuario->listarusuarios();
        include "../Views/HTML/Perfil.php";
    }

    // 🔹 Registrar usuario
    public function registrar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $Tipo_Usuario = $_POST['Tipo_Usuario'];
            $Nombre       = $_POST['Nombre'];
            $Documento    = $_POST['Documento'];
            $Telefono     = $_POST['Telefono'];
            $Correo       = $_POST['Correo'];
            $Contraseña   = $_POST['Contraseña'];
            $Direccion    = $_POST['Direccion'];

            $resultado = $this->modelusuario->crearusuarios($Tipo_Usuario, $Nombre, $Documento, $Telefono, $Correo, $Contraseña, $Direccion);

            // Validar si hay (correo duplicado)
            if (isset($resultado['error'])) {
                $_SESSION['error'] = $resultado['error'];
            } else {
                $_SESSION['mensaje'] = "Usuario registrado correctamente.";
            }

            header("Location: ../Views/HTML/Perfil.php");
            exit;
        }
    }

    // Editar usuario
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
            $id           = $_POST['id'];
            $Tipo_Usuario = $_POST['Tipo_Usuario'];
            $Nombre       = $_POST['Nombre'];
            $Documento    = $_POST['Documento'];
            $Telefono     = $_POST['Telefono'];
            $Correo       = $_POST['Correo'];
            $Direccion    = $_POST['Direccion'];

            // Validar que el correo no esté en uso por otro usuario
            $usuarioExistente = $this->modelusuario->obtenerUsuario($Correo);
            if ($usuarioExistente && $usuarioExistente['ID_Usuario'] != $id) {
                $_SESSION['error'] = "El correo ya está registrado por otro usuario.";
                header("Location: ../Views/HTML/Perfil.php");
                exit;
            }

            $this->modelusuario->editarUsuario($id, $Tipo_Usuario, $Nombre, $Documento, $Telefono, $Correo, $Direccion);

            if ($id == $_SESSION['usuario']['Id_Usuario']) {
                $_SESSION['usuario'] = $this->modelusuario->obtenerUsuarioPorId($id);
            }

            $_SESSION['mensaje'] = "Usuario editado correctamente.";
            header("Location: ../Views/HTML/Perfil.php");
            exit;
        }
    }

    // Eliminar usuario
    public function eliminar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
            $id = $_POST['id'];
            $this->modelusuario->eliminarUsuario($id);

            if ($id == $_SESSION['usuario']['Id_Usuario']) {
                session_destroy();
                header("Location: ../Views/HTML/Login.php");
                exit;
            }

            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            header("Location: ../Views/HTML/Perfil.php");
            exit;
        }
    }
}


$objeto = new UsuarioController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Correo']) && isset($_POST['Contraseña']) && isset($_POST['Nombre'])) {
        $objeto->registrar();
    } elseif (isset($_POST['email']) && isset($_POST['password'])) {
        $objeto->validarusu();
    } elseif (isset($_POST['editar'])) {
        $objeto->editar();
    } elseif (isset($_POST['eliminar'])) {
        $objeto->eliminar();
    } elseif (isset($_POST['cerrar'])) {
        $objeto->cerrarsesion();
    } else {
        header("Location: ../Views/HTML/Login.php");
        exit;
    }
}
?>