<?php
require_once __DIR__ . '/../Modelo/Domicilio.php';
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['Tipo_Usuario'] ?? '') !== 'Administrador') {
    header('Location: ../Views/HTML/Login.php');
    exit;
}

$modelo = new Domicilio();

// Editar contacto de entrega
if (isset($_POST['editar_contacto'])) {
    $id = $_POST['id'] ?? null;
    $direccion = isset($_POST['DireccionEntrega']) ? trim($_POST['DireccionEntrega']) : '';
    $telefono = isset($_POST['TelefonoEntrega']) ? trim($_POST['TelefonoEntrega']) : '';
    if (empty($id) || $direccion === '' || $telefono === '') {
        $_SESSION['mensaje'] = 'Dirección y teléfono son obligatorios.';
        $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'domicilios.php';
        header('Location: ../Views/HTML/' . $redirect);
        exit;
    }
    $ok = $modelo->actualizarContactoEntrega($id, $direccion, $telefono);
    $_SESSION['mensaje'] = $ok ? 'Contacto de entrega actualizado.' : 'No se pudo actualizar el contacto.';
    $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'domicilios.php';
    header('Location: ../Views/HTML/' . $redirect);
    exit;
}

// Marcar entregado
if (isset($_POST['marcar_entregado'])) {
    $id = $_POST['id'];
    $ok = $modelo->marcarEntregado($id);
    $_SESSION['mensaje'] = $ok ? 'Pedido marcado como entregado.' : 'No se pudo marcar como entregado.';
    $redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : 'domicilios.php';
    header('Location: ../Views/HTML/' . $redirect);
    exit;
}

// Exportar CSV (GET)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
    $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;
    $data = $modelo->listarDomicilios($desde, $hasta);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=domicilios_' . date('Ymd_His') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Id_Pedido','Fecha','Hora','Estado','Cliente','Telefono','Direccion','DireccionEntrega','TelefonoEntrega','Entregado']);
    foreach ($data as $row) {
        fputcsv($out, [
            $row['Id_Pedido'] ?? '',
            $row['Fecha'] ?? '',
            $row['Hora'] ?? '',
            $row['Estado'] ?? '',
            $row['ClienteNombre'] ?? '',
            $row['ClienteTelefono'] ?? '',
            $row['ClienteDireccion'] ?? '',
            $row['DireccionEntrega'] ?? '',
            $row['TelefonoEntrega'] ?? '',
            $row['Entregado'] ?? ''
        ]);
    }
    fclose($out);
    exit;
}

?>
