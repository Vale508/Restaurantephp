<?php
require_once 'conexion.php';

$db = Database::connect();

try {
    echo "<h2>Verificando estructura de tabla 'pedido':</h2>";
    
    // Obtener estructura actual
    $sql = "DESCRIBE pedido";
    $stmt = $db->query($sql);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    echo "<h2>Intentando agregar columnas faltantes:</h2>";
    
    // Verificar si DireccionEntrega existe
    $tieneDir = false;
    $tieneTel = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'DireccionEntrega') $tieneDir = true;
        if ($col['Field'] === 'TelefonoEntrega') $tieneTel = true;
    }
    
    if (!$tieneDir) {
        echo "Agregando columna DireccionEntrega...<br>";
        $db->exec("ALTER TABLE pedido ADD COLUMN DireccionEntrega VARCHAR(255) NULL AFTER Id_Mesa");
        echo "✓ DireccionEntrega agregada<br>";
    } else {
        echo "✓ DireccionEntrega ya existe<br>";
    }
    
    if (!$tieneTel) {
        echo "Agregando columna TelefonoEntrega...<br>";
        $db->exec("ALTER TABLE pedido ADD COLUMN TelefonoEntrega VARCHAR(20) NULL AFTER DireccionEntrega");
        echo "✓ TelefonoEntrega agregada<br>";
    } else {
        echo "✓ TelefonoEntrega ya existe<br>";
    }
    
    echo "<h2>Estructura actualizada:</h2>";
    $stmt = $db->query("DESCRIBE pedido");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    echo "<h2>Proceso completado</h2>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "<br>Trace: " . $e->getTraceAsString();
}
?>
