<?php
require_once "../../conexion.php";
class Usuario{
    $db;

    private function __construct()
    {
        $this->db=Database::connect();
    }
    public function obtenerUsuario()
    {
        $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $consul = $this->db->prepare($sql);
        $consul->execute([":correo"=>$email]);

        return $consul->fetch();
    }
    public function login($email,$pass){
        $usuario = $this->obtenerUsuario($email);
        if($usuario && password_verify($pass,$usuario['Contraseña'])){
            return $usuario;
        } 
        return false;     
    }
    public function listarusaurios(){

    }
    public function crearusuarios(){

    }

}
?>