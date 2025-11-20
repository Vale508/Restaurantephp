<?php
require_once 'conexion.php';

$db = Database::connect();

try {
    // Obtener estructura de la tabla producto
    $sql = "DESCRIBE producto";
    $stmt = $db->query($sql);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Estructura de la tabla 'producto':</h2>";
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // También mostrar un producto de ejemplo
    $sql2 = "SELECT * FROM producto LIMIT 1";
    $stmt2 = $db->query($sql2);
    $product = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Ejemplo de producto:</h2>";
    echo "<pre>";
    print_r($product);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
