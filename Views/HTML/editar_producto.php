<?php
require_once "../../Controlador/productocontrolador.php";

if(!isset($_GET['id'])){
    header("Location: crudplatos.php");
    exit;
}

$id = $_GET['id'];
$plato = null;
if (class_exists('ProductoControlador')) {
    require_once __DIR__ . '/../../Modelo/Productos.php';
    $modelo = new Plato();
    $plato = $modelo->obtenerPlatoPorId($id);
} else {
    require_once __DIR__ . '/../../Modelo/Productos.php';
    $modelo = new Plato();
    $plato = $modelo->obtenerPlatoPorId($id);
}

if(!$plato){
    header("Location: crudplatos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Plato</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Editar Plato</h1>

    <form action="../../Controlador/productocontrolador.php" method="POST">
        <input type="hidden" name="id" value="<?= isset($plato['Id_Producto']) ? htmlspecialchars($plato['Id_Producto']) : ''; ?>">
        <input type="hidden" name="ImagenActual" value="<?= isset($plato['Imagen']) ? htmlspecialchars($plato['Imagen']) : ''; ?>">
        <input type="hidden" name="CategoriaActual" value="<?= isset($plato['Id_Menu']) ? htmlspecialchars($plato['Id_Menu']) : ''; ?>">

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="Nombre" class="form-control" value="<?= isset($plato['Nombre']) ? htmlspecialchars($plato['Nombre']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Descripción:</label>
            <textarea name="Descripcion" class="form-control" required><?= isset($plato['Descripcion']) ? htmlspecialchars($plato['Descripcion']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Disponible:</label>
            <select name="Disponible" class="form-control" required>
                <option value="1" <?= (isset($plato['Disponible']) && $plato['Disponible'] == 1) ? 'selected' : ''; ?>>Disponible</option>
                <option value="0" <?= (isset($plato['Disponible']) && $plato['Disponible'] == 0) ? 'selected' : ''; ?>>No disponible</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Precio:</label>
            <input type="number" name="Precio" class="form-control" step="0.01" value="<?= isset($plato['Precio']) ? htmlspecialchars($plato['Precio']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label>Categoría:</label>
            <?php
                $currentCat = '';
                if (isset($plato['CategoriaNombre']) && $plato['CategoriaNombre'] !== null && $plato['CategoriaNombre'] !== '') {
                    $currentCat = $plato['CategoriaNombre'];
                } elseif (isset($plato['Categoria']) && $plato['Categoria'] !== null && $plato['Categoria'] !== '') {
                    $currentCat = $plato['Categoria'];
                } elseif (isset($plato['CategoriaTexto']) && $plato['CategoriaTexto'] !== null && $plato['CategoriaTexto'] !== '') {
                    $currentCat = $plato['CategoriaTexto'];
                } elseif (isset($plato['Id_Menu']) && $plato['Id_Menu'] !== null) {
                    $currentCat = (string)$plato['Id_Menu'];
                }
            ?>
            <select name="Categoria" class="form-control">
                <option value="">Seleccione...</option>
                <option value="entradas" <?= ($currentCat === 'entradas') ? 'selected' : ''; ?>>Entradas</option>
                <option value="platos fuertes" <?= ($currentCat === 'platos fuertes') ? 'selected' : ''; ?>>Platos fuertes</option>
                <option value="postres" <?= ($currentCat === 'postres') ? 'selected' : ''; ?>>Postres</option>
                <option value="bebidas" <?= ($currentCat === 'bebidas') ? 'selected' : ''; ?>>Bebidas</option>
            </select>
            <small class="text-muted">Si desea mantener la categoría actual, no cambie esta selección.</small>
        </div>
        <div class="mb-3">
            <label>Imagen (URL):</label>
            <input type="text" name="Imagen" class="form-control" placeholder="https://... o ruta relativa" value="<?= isset($plato['Imagen']) ? htmlspecialchars($plato['Imagen']) : ''; ?>" required>
            <?php if($plato['Imagen']): ?>
                <?php
                    $img = $plato['Imagen'];
                    $src = (strpos($img, 'http') === 0) ? $img : '../' . $img;
                ?>
                <img src="<?= $src; ?>" width="80" class="mt-2" alt="Imagen actual">
            <?php endif; ?>
        </div>
        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
        <a href="crudplatos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
