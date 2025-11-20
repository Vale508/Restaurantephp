<?php
require_once __DIR__ . "/../../Controlador/mesacontrolador.php";
require_once __DIR__ . "/../../Controlador/reservacontrolador.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mesaController = new MesaControlador();
$reservaController = new ReservaControlador();

$mesas = $mesaController->ListarMesas();
$reservas = $reservaController->ListarReservas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="domicilios.php" class="btn btn-info btn-lg text-white">Domicilios</a>
            </div>
        </div>

        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['mensaje']; 
                    unset($_SESSION['mensaje']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

<div class="container my-5">
  <h1 class="text-center mb-4 text-danger fw-bold">Gestión de Mesas y Reservas</h1>
  
  <!-- Formulario para crear mesa -->
  <div class="card mb-5 shadow">
    <div class="card-header bg-danger text-white text-center fs-4 fw-bold">Registrar Nueva Mesa</div>
    <div class="card-body">
      <form method="POST" action="../../Controlador/mesacontrolador.php" class="row g-3">
        <input type="hidden" name="accion" value="registrar_mesa">

        <div class="col-md-3">
          <label class="form-label fw-bold">Número de Mesa</label>
          <input type="number" name="numero_mesa" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Capacidad</label>
          <input type="number" name="capacidad" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Ubicación</label>
          <input type="text" name="ubicacion" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-bold">Disponible</label>
          <select name="disponible" class="form-select">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
          </select>
        </div>
        <div class="col-12 text-center">
          <button type="submit" class="btn btn-success px-4 mt-3">Registrar Mesa</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Listado de mesas -->
  <div class="card shadow mb-5">
    <div class="card-header bg-warning text-dark text-center fs-4 fw-bold">Listado de Mesas</div>
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Número</th>
            <th>Capacidad</th>
            <th>Ubicación</th>
            <th>Disponible</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($mesas as $m): ?>
          <tr>
            <td><?= $m['Id_Mesa'] ?></td>
            <td><?= $m['Numero_Mesa'] ?></td>
            <td><?= $m['Capacidad'] ?></td>
            <td><?= $m['Ubicacion'] ?></td>
            <td>
              <span class="badge <?= $m['Disponible']=='Sí'?'bg-success':'bg-danger' ?>">
                <?= $m['Disponible'] ?>
              </span>
            </td>
            <td>
              <form method="POST" action="../../Controlador/mesacontrolador.php" class="d-inline">
                <input type="hidden" name="accion" value="actualizar_mesa">
                <input type="hidden" name="id_mesa" value="<?= $m['Id_Mesa'] ?>">
                <select name="disponible" class="form-select form-select-sm d-inline w-auto">
                  <option value="Sí" <?= $m['Disponible']=='Sí'?'selected':'' ?>>Sí</option>
                  <option value="No" <?= $m['Disponible']=='No'?'selected':'' ?>>No</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
              </form>
              <a href="../../Controlador/mesacontrolador.php?eliminar_mesa=1&id=<?= $m['Id_Mesa'] ?>" 
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('¿Eliminar mesa?')">Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Listado de reservas  -->
  <div class="card shadow">
    <div class="card-header bg-danger text-white text-center fs-4 fw-bold">Listado de Reservas</div>
    <div class="card-body table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>N° Personas</th>
            <th>Estado</th>
            <th>Usuario</th>
            <th>Mesa</th>
            <th>Ubicación</th>
            <th>Fecha</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($reservas as $r): ?>
          <tr>
            <td><?= $r['Id_Reserva'] ?></td>
            <td><?= $r['Numero_Personas'] ?></td>
            <td>
              <span class="badge 
                <?php if($r['Estado']=='Pendiente') echo 'bg-warning text-dark';
                      elseif($r['Estado']=='Confirmada') echo 'bg-success';
                      else echo 'bg-secondary'; ?>">
                <?= $r['Estado'] ?>
              </span>
            </td>
            <td><?= $r['Id_Usuario'] ?></td>
            <td><?= $r['Numero_Mesa'] ?></td>
            <td><?= $r['Ubicacion'] ?></td>
            <td><?= $r['Fecha_Reserva'] ?></td>
            <td>
              <form method="POST" action="../../Controlador/reservacontrolador.php" class="d-flex gap-2">
                <input type="hidden" name="accion" value="actualizar_reserva">
                <input type="hidden" name="id_reserva" value="<?= $r['Id_Reserva'] ?>">
                <select name="estado" class="form-select form-select-sm">
                  <option value="Pendiente" <?= $r['Estado']=='Pendiente'?'selected':'' ?>>Pendiente</option>
                  <option value="Confirmada" <?= $r['Estado']=='Confirmada'?'selected':'' ?>>Confirmada</option>
                  <option value="Cancelada" <?= $r['Estado']=='Cancelada'?'selected':'' ?>>Cancelada</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Actualizar</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
