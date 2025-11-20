<?php
// Llamar a la conexión
require_once "conexion.php";

try {
    // Instanciar la clase para la conexión
    $db = Database::connect();
    $email = "      om";

    // Consultar si ese usuario se encuentra registrado
    $consul = $db->prepare("SELECT * FROM usuario WHERE correo = :correo");
    $consul->execute([":correo" => $email]);

    // Registrar los datos de usuario y contraseña
    if (!$consul->fetch()) {
        $pass = password_hash("admin123", PASSWORD_BCRYPT);
        
        // Crear el SQL de INSERT
        $sql = "INSERT INTO usuario (Tipo_Usuario, Nombre, Documento, Telefono, Correo, Contrasena, Direccion) 
                VALUES ('Administrador', 'Carlos', '1234', '12345', :email, :clave, 'calle 23')";
        
        // Preparar y ejecutar el INSERT
        $consult = $db->prepare($sql);
        $consult->execute([":email" => $email, ":clave" => $pass]);
        
        echo "Usuario administrador creado";
    } else {
        echo "El administrador ya existe";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
