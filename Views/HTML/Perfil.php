<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: Login.php");
    exit;
}

require_once __DIR__ . '/../../Config/conexion.php';
require_once __DIR__ . '/../../Modelo/usuario.php';

$modelo = new Usuario();
$usuarios = $modelo->listarusuarios();
$usuario_sesion = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Usuarios Registrados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
    <div class="container my-4">
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="crudplatos.php" class="btn btn-primary btn-lg">Platos</a>
        <a href="reservas.php" class="btn btn-success btn-lg">Reservas</a>
        <a href="pedidos.php" class="btn btn-warning btn-lg text-white">Pedidos</a>
        <a href="domicilios.php" class="btn btn-info btn-lg text-white">Domicilios</a>
    </div>
</div>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['mensaje']; 
                unset($_SESSION['mensaje']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Información del usuario -->
    <div class="card mb-4" style="background: #181817ff; color: white;">
        <div class="card-body">
            <h4 class="card-title">Bienvenid@, <?php echo htmlspecialchars($usuario_sesion['Nombre']); ?></h4>
            <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario_sesion['Correo']); ?></p>
        </div>
    </div>

    <!-- Formulario de registro de usuario -->
    <div class="card mb-4">
        <div class="card-header bg-info text-black">Registrar Nuevo Usuario</div>
        <div class="card-body">
            <form action="../../Controlador/usuariocontrolador.php" method="POST">
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="Nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="col">
                        <input type="text" name="Documento" class="form-control" placeholder="Documento" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="Telefono" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="col">
                        <input type="email" name="Correo" class="form-control" placeholder="Correo" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <input type="password" name="Contraseña" class="form-control" placeholder="Contraseña" required>
                    </div>
                    <div class="col">
                        <input type="text" name="Direccion" class="form-control" placeholder="Dirección" required>
                    </div>
                </div>
                <div class="mb-3">
                    <select name="Tipo_Usuario" class="form-select" required>
                        <option value="" disabled selected>Tipo de Usuario</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Usuario">Usuario</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success" name="Registrar">Registrar Usuario</button>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <h4 class="mb-3">Usuarios Registrados</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle" style="background-color: #fff8e1;">
            <thead style="background-color: #000000ff; color: white;">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['Id_Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($u['Tipo_Usuario']); ?></td>
                        <td><?php echo htmlspecialchars($u['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($u['Documento']); ?></td>
                        <td><?php echo htmlspecialchars($u['Telefono']); ?></td>
                        <td><?php echo htmlspecialchars($u['Correo']); ?></td>
                        <td><?php echo htmlspecialchars($u['Direccion']); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo $u['Id_Usuario']; ?>" class="btn btn-sm btn-info ms-2">Editar</a>
                            <form action="../../Controlador/usuariocontrolador.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $u['Id_Usuario']; ?>">
                                <button type="submit" name="eliminar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este usuario?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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