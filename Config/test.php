<?php
require_once "conexion.php";

try {
    $db = Database::connect();
    echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
