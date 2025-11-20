<?php
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['Tipo_Usuario'] ?? '') !== 'Administrador') {
    header("Location: Login.php");
    exit;
}

require_once __DIR__ . '/../../Modelo/Domicilio.php';

$modelo = new Domicilio();
$platos = [];
$desde = isset($_GET['desde']) ? trim($_GET['desde']) : null;
$hasta = isset($_GET['hasta']) ? trim($_GET['hasta']) : null;
$platos = $modelo->listarDomicilios($desde, $hasta);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domicilios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
<!-- Enlaces de navegación -->
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item">
        <a class="nav-link" style="font-size: 25px;" href="../Index.html">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"style="font-size: 25px;" href="../Html/Menu.html">Menú</a>
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
<div>
        <div class="container my-4">
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="perfil.php" class="btn btn-success btn-lg">Perfil</a>
                <a href="crudplatos.php" class="btn btn-primary btn-lg">Platos</a>
                <a href="pedidos.php" class="btn btn-warning btn-lg text-white">Pedidos</a>
                <a href="reservas.php" class="btn btn-info btn-lg text-white">Reservas</a>
            </div>
        </div>


<div class="container my-4">
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['mensaje']); unset($_SESSION['mensaje']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Domicilios</h2>
        <div>
<a class="btn btn-outline-secondary" 
   href="../../Controlador/domiciliocontrolador.php?export=csv&desde=<?php echo urlencode($desde); ?>&hasta=<?php echo urlencode($hasta); ?>">
   Exportar CSV
</a>        </div>
    </div>

    <form class="row g-2 mb-3" method="GET" action="domicilios.php">
        <div class="col-auto">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control" value="<?php echo htmlspecialchars($desde ?? ''); ?>">
        </div>
        <div class="col-auto">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control" value="<?php echo htmlspecialchars($hasta ?? ''); ?>">
        </div>
        <div class="col-auto align-self-end">
            <button class="btn btn-primary" type="submit">Filtrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Dirección de Entrega</th>
                    <th>Teléfono de Entrega</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($platos as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['Id_Pedido']); ?></td>
                        <td><?php echo htmlspecialchars($p['Fecha'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($p['DireccionEntrega'] ?? $p['ClienteDireccion'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($p['TelefonoEntrega'] ?? $p['ClienteTelefono'] ?? '-'); ?></td>
                        <td>$<?php echo number_format($p['Total'] ?? 0, 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($p['Estado'] ?? 'Pendiente'); ?></td>
                        <td>                            
                            <button class="btn btn-sm btn-warning edit-contact-btn" 
                                data-id="<?php echo htmlspecialchars($p['Id_Pedido']); ?>"
                                data-direccion="<?php echo htmlspecialchars($p['DireccionEntrega'] ?? $p['ClienteDireccion'] ?? ''); ?>"
                                data-telefono="<?php echo htmlspecialchars($p['TelefonoEntrega'] ?? $p['ClienteTelefono'] ?? ''); ?>"
                                data-bs-toggle="modal" data-bs-target="#editContactModal">Editar</button>

                            <form action="../../Controlador/domiciliocontrolador.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($p['Id_Pedido']); ?>">
                                <input type="hidden" name="redirect" value="domicilios.php">
                                <button type="submit" name="marcar_entregado" class="btn btn-sm btn-success" onclick="return confirm('¿Marcar como entregado?');">Entregado</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="../../Controlador/domiciliocontrolador.php" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Contacto de Entrega</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="contact-id">
                <input type="hidden" name="redirect" value="domicilios.php">
                <div class="mb-3"><label>Dirección de entrega</label><input id="contact-direccion" name="DireccionEntrega" class="form-control" required></div>
                <div class="mb-3"><label>Teléfono de entrega</label><input id="contact-telefono" name="TelefonoEntrega" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="editar_contacto" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('edit-contact-btn')) {
        const btn = e.target;
        document.getElementById('contact-id').value = btn.getAttribute('data-id') || '';
        document.getElementById('contact-direccion').value = btn.getAttribute('data-direccion') || '';
        document.getElementById('contact-telefono').value = btn.getAttribute('data-telefono') || '';
    }
});
</script>
<!--Pie de pagina-->
<footer class="bg-dark text-white text-center p-4 mt-5">
    <div class="container">
    <div class="row">
    <!-- Primera columna -->
    <div class="col-md-4">
    <h5>Enlaces</h5>
    <ul class="list-unstyled">
    <li><a href="../Html/Index.html" class="text-white">Inicio</a></li>
    <li><a href="#" class="text-white">Menu</a></li>
    <li><a href="../Html/Nosotros.html" class="text-white">Nosotros</a></li>
    <li><a href="../Html/Contacto.html" class="text-white">Contacto</a></li>
    </ul>
    </div>
    <!-- Segundo columna -->
    <div class="col-md-4">
    <h5>Redes Sociales</h5>
    <ul class="list-unstyled">
    <li><img src="../Img/Face.webp" style="width: 40px;" alt=""><a href="#" class="text-white">Facebook</a></li>
    <li><img src="../Img/logowhatsapppng.webp" style="width: 40px;" alt=""><a href="#" class="text-white">WhatsApp</a></li>
    <li><img src="../Img/logoinstagram.png" style="width: 60px;" alt=""><a href="#" class="text-white">Instagram</a></li>
    </ul>
    </div>
    <!-- Tercer columna -->
    <div class="col-md-4">
    <h5>Contacto</h5>
    <p>Dirección: Calle 27 d sur # 27 c 51</p>
    <p>Email: Losmilsabores@gmail.com</p>
    <p>Telefono: 3202604788</p>
    </div>
    </div>
    </div>
    <div class="text-center mt-4">
    <p>&copy; 2025 Mi Sitio Web. Todos los derechos reservados.</p>
    </div>
    </footer>
</body>
</html>
