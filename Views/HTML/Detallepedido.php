<?php
require_once __DIR__ . "/../../Modelo/PedidoDetalle.php";

if (!isset($_GET['id'])) {
    echo "No se recibió el ID del pedido";
    exit;
}

$idPedido = $_GET['id'];

$detalle = new PedidoDetalle();
$detalles = $detalle->listarDetalles($idPedido);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

<!-- Barra de Navegación -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" style="height: 120px;" href="#">
        <img src="../Img/logo 1.jpg" alt="Logo" width="100" height="120" class="me-2 rounded-pill">
        <span class="fs-4">El lugar de los mil sabores</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Enlaces -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" style="font-size: 25px;" href="../Index.html">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 25px;" href="../Html/Menu.html">Menú</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 25px;" href="../Html/Carrito.html">Carrito</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 25px;" href="../Html/Contacto.html">Contacto</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style="font-size: 25px;" href="../Html/Historial.html">Historial</a>
          </li>
        </ul>

        <div class="ms-auto">
            <form action="../../Controlador/usuariocontrolador.php" method="POST">
                <button class="btn btn-danger text-white" type="submit" name="cerrar">Cerrar sesión</button>
            </form>
        </div>
      </div>
    </div>
</nav>

<!-- Botones principales -->
<div class="container my-4">
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="perfil.php" class="btn btn-success btn-lg">Perfil</a>
        <a href="crudplatos.php" class="btn btn-primary btn-lg">Platos</a>
        <a href="pedidos.php" class="btn btn-warning btn-lg text-white">Pedidos</a>
        <a href="reservas.php" class="btn btn-info btn-lg text-white">Reservas</a>
    </div>
</div>

<!-- Contenido Detalle Pedido -->
<div class="container mt-4">

<h2 class="mb-3">Detalle del Pedido #<?= $idPedido ?></h2>

<a href="Pedidos.php" class="btn btn-secondary mb-3">Volver</a>

<?php if (empty($detalles)): ?>
    <div class="alert alert-warning">
        Este pedido no tiene detalles registrados.
    </div>
<?php else: ?>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($detalles as $d): ?>
        <tr>
            <td><?= $d['Nombre'] ?></td>
            <td><?= $d['Cantidad'] ?></td>
            <td>$<?= number_format($d['Precio'], 0, ',', '.') ?></td>
            <td>$<?= number_format($d['Subtotal'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
