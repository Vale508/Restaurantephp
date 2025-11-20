<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: Login.php");
    exit;
}

require_once __DIR__ . '/../../Config/conexion.php';
require_once __DIR__ . '/../../Modelo/usuario.php';

$modelo = new Usuario();

// Verifica si se pasó un ID
if (!isset($_GET['id'])) {
    header("Location: perfil.php");
    exit;
}

$id = $_GET['id'];

// Obtiene los datos del usuario
$usuario = $modelo->obtenerUsuarioPorId($id);
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

<div class="container my-5">
    <div class="card">
        <div class="card-header bg-dark text-white">
            Editar Usuario
        </div>
        <div class="card-body">
            <form action="../../Controlador/usuariocontrolador.php" method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['Id_Usuario']); ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="Nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['Nombre']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Documento</label>
                    <input type="text" name="Documento" class="form-control" value="<?php echo htmlspecialchars($usuario['Documento']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="Telefono" class="form-control" value="<?php echo htmlspecialchars($usuario['Telefono']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="Correo" class="form-control" value="<?php echo htmlspecialchars($usuario['Correo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="Direccion" class="form-control" value="<?php echo htmlspecialchars($usuario['Direccion']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo de Usuario</label>
                    <select name="Tipo_Usuario" class="form-select" required>
                        <option value="Administrador" <?php if($usuario['Tipo_Usuario'] === 'Administrador') echo 'selected'; ?>>Administrador</option>
                        <option value="Usuario" <?php if($usuario['Tipo_Usuario'] === 'Usuario') echo 'selected'; ?>>Usuario</option>
                    </select>
                </div>

                <button type="submit" name="editar" class="btn btn-warning">Guardar Cambios</button>
                <a href="perfil.php" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>