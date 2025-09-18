<?php
require_once "../../conexion.php";
require_once "../Modelo/usuario.php";
class UsuarioController{
    private $modelusuario;

    private function __construct()
    {
        $this->modelusuario = new Usuario();
    }
    public function validarusu(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

            $usuario = $this->modelusuario->login($_POST['email'],$_POST['password']);
            if($usuario){
                session_start();
                $_SESSION['usuario']=$usuario;
                header("Location: ../../view/perfil.html");
            }else{
                echo "credencial no valida";
                header("Location: ../../view");
            }
        }else{
            header("Location: ../../view");
        }
    }
    public function cerrarsesion(){

    }
}
?>