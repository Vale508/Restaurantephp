<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Controlador/productocontrolador.php';
require_once __DIR__ . '/../../Modelo/Productos.php';

if (!isset($platos)) {
    if (class_exists('ProductoControlador')) {
        $prodCtrl = new ProductoControlador();
        if (isset($_GET['buscar']) && trim($_GET['buscar']) !== '') {
            $platos = $prodCtrl->buscarPlatos(trim($_GET['buscar']));
        } else {
            $platos = $prodCtrl->listarProductos();
        }
    } else {
        $modelo = new Plato();
        if (isset($_GET['buscar']) && trim($_GET['buscar']) !== '') {
            $platos = $modelo->buscarPlatos(trim($_GET['buscar']));
        } else {
            $platos = $modelo->listarPlatos();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Platos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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
                <a href="reservas.php" class="btn btn-primary btn-lg">Reservas</a>
                <a href="pedidos.php" class="btn btn-warning btn-lg text-white">Pedidos</a>
                <a href="domicilios.php" class="btn btn-info btn-lg text-white">Domicilios</a>
            </div>
        </div>
<div class="container mt-5">
    <h1 class="mb-4">Gestión de Platos</h1>

    <!-- Mensajes -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success"><?= $_SESSION['mensaje']; ?></div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- Formulario para registrar plato -->
    <h3>Registrar Plato</h3>
    <form action="../../Controlador/productocontrolador.php" method="POST" class="mb-4">
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="Nombre" class="form-control" required>
            
        </div>
        <div class="mb-3">
            <label>Descripción:</label>
            <textarea name="Descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Disponible:</label>
            <select name="Disponible" class="form-control" required>
                <option value="1">Disponible</option>
                <option value="0">No disponible</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Precio:</label>
            <input type="number" name="Precio" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label>Categoría:</label>
            <select name="Categoria" class="form-control" required>
                <option value="">Seleccione...</option>
                <option value="entradas">Entradas</option>
                <option value="platos fuertes">Platos fuertes</option>
                <option value="postres">Postres</option>
                <option value="bebidas">Bebidas</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Imagen (URL):</label>
            <input type="text" name="Imagen" class="form-control" placeholder="url" required>
        </div>
        <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
    </form>

    <!-- Tabla de platos -->
    <h3>Lista de Platos</h3>
    
    <!-- Buscador de platos -->
    <div class="mb-3">
        <form method="GET" action="crudplatos.php" class="d-flex gap-2">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre...">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </form>
    </div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Disponible</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($platos as $plato): ?>
            <tr>
                <td><?= isset($plato['Id_Producto']) ? $plato['Id_Producto'] : ''; ?></td>
                <td><?= isset($plato['Nombre']) ? htmlspecialchars($plato['Nombre']) : ''; ?></td>
                <td><?= isset($plato['Descripcion']) ? htmlspecialchars($plato['Descripcion']) : ''; ?></td>
                <td>
                    <?php
                        // Buscar la columna Disponible en el array
                        $disponible = null;
                        foreach ($plato as $key => $value) {
                            if (strtolower($key) === 'disponible') {
                                $disponible = $value;
                                break;
                            }
                        }
                        
                        if ($disponible === null) {
                            echo '<span class="text-muted">-</span>';
                        } elseif ($disponible == 1 || $disponible === '1' || strtolower($disponible) === 'sí' || strtolower($disponible) === 'si') {
                            echo '<span class="badge bg-success">Disponible</span>';
                        } elseif ($disponible == 0 || $disponible === '0' || strtolower($disponible) === 'no') {
                            echo '<span class="badge bg-danger">No disponible</span>';
                        } else {
                            echo '<span class="text-muted">-</span>';
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (isset($plato['CategoriaNombre']) && $plato['CategoriaNombre'] !== null) {
                            echo htmlspecialchars($plato['CategoriaNombre']);
                        } elseif (isset($plato['Categoria']) && $plato['Categoria'] !== null && $plato['Categoria'] !== '') {
                            echo htmlspecialchars($plato['Categoria']);
                        } elseif (isset($plato['CategoriaTexto']) && $plato['CategoriaTexto'] !== null && $plato['CategoriaTexto'] !== '') {
                            echo htmlspecialchars($plato['CategoriaTexto']);
                        } elseif (isset($plato['Id_Menu']) && $plato['Id_Menu'] !== null) {
                            echo htmlspecialchars($plato['Id_Menu']);
                        } else {
                            echo '<span class="text-muted">-</span>';
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if (isset($plato['Precio']) && $plato['Precio'] !== '') {
                            $raw = preg_replace('/[^\d\.,-]+/', '', $plato['Precio']);
                            $normalized = str_replace(['.', ','], ['', '.'], $raw);
                            $num = floatval($normalized);
                            echo '$ ' . number_format($num, 0, ',', '.');
                        }
                    ?>
                </td>
                <td>
                    <?php
                        $img = isset($plato['Imagen']) ? $plato['Imagen'] : (isset($plato['imagen']) ? $plato['imagen'] : '');
                        if (!empty($img)) {
                            if (preg_match('#^https?://#i', $img) || strpos($img, '/') === 0) {
                                $src = $img;
                            } else {
                                $basename = basename($img);
                                $pathsToTry = [
                                    __DIR__ . '/../Img/' . $img,
                                    __DIR__ . '/../Img/Productos/' . $img,
                                    __DIR__ . '/../Img/' . $basename,
                                    __DIR__ . '/../Img/Productos/' . $basename,
                                ];
                                $found = false;
                                foreach ($pathsToTry as $p) {
                                    $rp = realpath($p);
                                    if ($rp && file_exists($rp)) { $found = $rp; break; }
                                }
                                if ($found) {
                                    $rel = str_replace('\\', '/', $found);
                                    $pos = stripos($rel, '/views/');
                                    if ($pos !== false) {
                                        $webPath = substr($rel, $pos + 1);
                                        $src = '../' . $webPath;
                                    } else {
                                        $src = '../Img/' . $basename;
                                    }
                                } else {
                                    $src = '../' . ltrim($img, './');
                                }
                            }
                            $src = str_replace('\\', '/', $src);
                            echo '<img src="' . htmlspecialchars($src) . '" width="80" height="80" alt="Imagen del producto">';
                        } else {
                            echo '<span class="text-muted">Sin imagen</span>';
                        }
                    ?>
                </td>
                <td>
                    <?php $pid = isset($plato['Id_Producto']) ? $plato['Id_Producto'] : ''; ?>
                    
                    <!-- Botón editar -->
                    <a href="editar_producto.php?id=<?= $pid; ?>" class="btn btn-sm btn-info">Editar</a>

                    <!-- Formulario eliminar con confirmación -->
                    <form action="../../Controlador/productocontrolador.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que deseas eliminar este plato?');">
                        <input type="hidden" name="id" value="<?= $pid; ?>">
                        <input type="hidden" name="redirect" value="crudplatos.php">
                        <button type="submit" name="eliminar" class="btn btn-sm btn-danger ms-3">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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
</body>
</html>