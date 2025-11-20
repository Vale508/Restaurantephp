<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
      <li class="nav-item">
        <a class="nav-link" style="font-size: 25px;" href="./Login.php">Perfil</a>
      </li>
    </ul>
  </div>
</div>
</nav>

        <!-- Columna del formulario -->
<div id="login" class="container mt-5">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <!-- Columna de la imagen -->
        <div class="col-md-6 text-center">
            <img src="../Img/comidas.jpg" alt="Imagen de Login" class="img-fluid rounded-3 shadow-lg">
        </div>

        <!-- Columna del formulario -->
        <div class="col-md-6" action="infor.php">
            <h2 class="text-center text-warning mb-4">Iniciar Sesión</h2>
            <form action="../../Controlador/usuariocontrolador.php" method="POST" class="p-4 shadow-lg rounded-3 bg-light">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control form-control-lg" id="email" placeholder="Ingrese su correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn w-100 py-2 mt-3 rounded-pill shadow" style="background-color: #FFA500; color: white;font-size: 20px;">Iniciar sesión</button>
            </form>
        </div>
    </div>
</div>

<?php if ($usuario['Tipo_Usuario'] === 'Administrador'): ?>
                           <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo Usuario</th>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td><?= htmlspecialchars($usuario['Id_Usuario']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Tipo_Usuario']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Nombre']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Documento']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Telefono']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Correo']) ?></td>
                                        <td><?= htmlspecialchars($usuario['Direccion']) ?></td>
                                    </tr>

                            </tbody>
                        </table>
                <?php endif; ?>
                 <td colspan="2">
                                    <h3>Opciones de Administrador</h3>
                                    <ul>
                                        <li><a href="../HTML/RegistroUsuarios.php">Registrar Usuarios</a></li>
                                    </ul>
                                </td>
-->
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