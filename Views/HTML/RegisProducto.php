<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="../../Controlador/productocontrolador.php" id="platoForm">
    <div class="mb-3">
        <label for="nombreplato" class="form-label">Nombre del Producto</label>
        <input type="text" class="form-control" id="nombreplato" name="nombreplato" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label for="precio" class="form-label">Precio</label>
        <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
    </div>

    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen del Plato</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
    </div>

    <div class="mb-3">
        <label for="disponible" class="form-label">Disponibilidad</label>
        <select class="form-control" id="disponible" name="disponible">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
        </select>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar Plato</button>
    </div>
</form>

</body>
</html>