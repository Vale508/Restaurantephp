<?php
require_once __DIR__ . '/../Config/conexion.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    //Obtener un usuario por correo
    public function obtenerUsuario($email) {
        $sql = "SELECT * FROM usuario WHERE Correo = :correo LIMIT 1";
        $consul = $this->db->prepare($sql);
        $consul->execute([":correo" => $email]);
        return $consul->fetch();
    }

    //Login de usuario
    public function login($email, $pass) {
        $usuario = $this->obtenerUsuario($email);
        if ($usuario && password_verify($pass, $usuario['Contrasena'])) {
            return $usuario;
        }
        return false;
    }

    // Listar todos los usuarios
    public function listarusuarios() {
        $stmt = $this->db->query("SELECT * FROM usuario");
        return $stmt->fetchAll();
    }

    // Crear un nuevo usuario
    public function crearusuarios($Tipo_Usuario, $Nombre, $Documento, $Telefono, $Correo, $Contrasena, $Direccion) {
        // 🔹 Validar que el correo no exista
        $usuarioExistente = $this->obtenerUsuario($Correo);
        if ($usuarioExistente) {
            return ['error' => 'El correo ya está registrado.'];
        }

        $hash = password_hash($Contrasena, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuario (Tipo_Usuario, Nombre, Documento, Telefono, Correo, Contrasena, Direccion)
                VALUES (:Tipo_Usuario, :Nombre, :Documento, :Telefono, :Correo, :Contrasena, :Direccion)";
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':Tipo_Usuario' => $Tipo_Usuario,
            ':Nombre'       => $Nombre,
            ':Documento'    => $Documento,
            ':Telefono'     => $Telefono,
            ':Correo'       => $Correo,
            ':Contrasena'   => $hash,
            ':Direccion'    => $Direccion
        ]);
        
        return ['success' => true];
    }

    // Obtener un usuario por su ID (para editar)
    public function obtenerUsuarioPorId($id) {
        $sql = "SELECT * FROM usuario WHERE ID_Usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    //Editar usuario (sin cambiar contraseña)
    public function editarUsuario($id, $Tipo_Usuario, $Nombre, $Documento, $Telefono, $Correo, $Direccion) {
        $sql = "UPDATE usuario 
                SET Tipo_Usuario = :Tipo_Usuario, 
                    Nombre = :Nombre, 
                    Documento = :Documento, 
                    Telefono = :Telefono, 
                    Correo = :Correo, 
                    Direccion = :Direccion 
                WHERE ID_Usuario = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':Tipo_Usuario' => $Tipo_Usuario,
            ':Nombre'       => $Nombre,
            ':Documento'    => $Documento,
            ':Telefono'     => $Telefono,
            ':Correo'       => $Correo,
            ':Direccion'    => $Direccion,
            ':id'           => $id
        ]);
    }

    //Eliminar usuario
    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuario WHERE ID_Usuario = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
?>
