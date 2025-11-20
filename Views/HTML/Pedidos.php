<?php
require_once __DIR__ . "/../../Controlador/pedidocontrolador.php";
require_once __DIR__ . "/../../Controlador/mesacontrolador.php";
require_once __DIR__ . "/../../Controlador/productocontrolador.php";

$pedidoController = new PedidoControlador();
$mesaController = new MesaControlador();
$productoController = new ProductoControlador();

$mesas = $mesaController->listarMesas();
$productos = $productoController->listarProductos();
$pedidos = $pedidoController->listarPedidos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
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
                <a href="reservas.php" class="btn btn-warning btn-lg text-white">Reservas</a>
                <a href="domicilios.php" class="btn btn-info btn-lg text-white">Domicilios</a>
            </div>
        </div>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script>
        function actualizarPrecio(select){
            let precio = select.options[select.selectedIndex].dataset.precio;
            select.parentElement.querySelector(".precioOculto").value = precio;
        }

        function toggleTipoPedido() {
            let tipoPedido = document.getElementById("tipoPedido").value;
            let mesaField = document.getElementById("mesaField");
            let domicilioField = document.getElementById("domicilioField");
            
            if (tipoPedido === "mesa") {
                mesaField.style.display = "block";
                domicilioField.style.display = "none";
                document.getElementById("Id_Mesa").required = true;
                document.getElementById("direccionEntrega").required = false;
                document.getElementById("telefonoEntrega").required = false;
            } else {
                mesaField.style.display = "none";
                domicilioField.style.display = "block";
                document.getElementById("Id_Mesa").required = false;
                document.getElementById("direccionEntrega").required = true;
                document.getElementById("telefonoEntrega").required = true;
            }
        }

        function agregarPlato() {
            let tabla = document.getElementById("tablaPlatos");
            let fila = tabla.insertRow();

            fila.innerHTML = `
                <td>
                    <select name="platos[]" class="form-select" onchange="actualizarPrecio(this)" required>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['Id_Producto'] ?>" data-precio="<?= $p['Precio'] ?>">
                                <?= $p['Nombre'] ?> - $<?= $p['Precio'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" class="precioOculto" name="precios[]" value="<?= $productos[0]['Precio'] ?>">
                </td>
                <td>
                    <input type="number" class="form-control" name="cantidades[]" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">X</button>
                </td>
            `;
        }

        function eliminarFila(btn) {
            btn.closest("tr").remove();
        }
    </script>
</head>

<body class="bg-light">

<div class="container mt-4">

    <h2 class="mb-4 text-center">Registrar Pedido</h2>

    <!-- FORMULARIO DE PEDIDO -->
    <div class="card shadow p-4 mb-5">
        <form action="../../Controlador/pedidocontrolador.php" method="POST">

            <input type="hidden" name="accion" value="registrar">

            <div class="mb-3">
                <label class="form-label">Tipo de Pedido</label>
                <select id="tipoPedido" name="tipoPedido" class="form-select" onchange="toggleTipoPedido()" required>
                    <option value="mesa">En Mesa</option>
                    <option value="domicilio">A Domicilio</option>
                </select>
            </div>

            <div id="mesaField" class="mb-3">
                <label class="form-label">Mesa</label>
                <select id="Id_Mesa" name="Id_Mesa" class="form-select">
                    <option value="">Seleccione</option>
                    <?php foreach ($mesas as $m): ?>
                        <option value="<?= $m['Id_Mesa'] ?>">
                            Mesa <?= $m['Numero_Mesa'] ?> (Cap: <?= $m['Capacidad'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Campos para Domicilio -->
            <div id="domicilioField" style="display: none;">
                <div class="mb-3">
                    <label class="form-label">Dirección de Entrega</label>
                    <input type="text" id="direccionEntrega" name="direccionEntrega" class="form-control" placeholder="Ingrese la dirección de entrega">
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono de Entrega</label>
                    <input type="tel" id="telefonoEntrega" name="telefonoEntrega" class="form-control" placeholder="Ingrese el teléfono de entrega">
                </div>
            </div>

            <label class="form-label">Platos del Pedido</label>
            <table class="table" id="tablaPlatos">
                <tr>
                    <td>
                        <select name="platos[]" class="form-select" onchange="actualizarPrecio(this)" required>
                            <?php foreach ($productos as $p): ?>
                                <option value="<?= $p['Id_Producto'] ?>" data-precio="<?= $p['Precio'] ?>">
                                    <?= $p['Nombre'] ?> - $<?= $p['Precio'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <input type="hidden" class="precioOculto" name="precios[]"
                               value="<?= $productos[0]['Precio'] ?>">
                    </td>

                    <td>
                        <input type="number" name="cantidades[]" class="form-control" min="1" required>
                    </td>

                    <td>
                        <button type="button" onclick="agregarPlato()" class="btn btn-success btn-sm">+</button>
                    </td>
                </tr>
            </table>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Registrar Pedido</button>
            </div>

        </form>
    </div>

    <!-- LISTADO DE PEDIDOS -->
    <h2 class="mb-3 text-center">Pedidos Registrados</h2>

    <div class="card shadow p-4">
        <div class="table-responsive">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Mesa</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Detalle</th>
                    <th>Cambiar Estado</th>
                </tr>
                </thead>

                <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach ($pedidos as $p): ?>
                        <tr>
                            <td><?= $p['Id_Pedido'] ?></td>
                            <td>Mesa <?= $p['Id_Mesa'] ?></td>
                            <td>$<?= number_format($p['Total'], 0, ',', '.') ?></td>
                            <td><?= ucfirst($p['Estado']) ?></td>
                            <td><?= $p['Fecha'] ?></td>

                            <td>
                                <a href="Detallepedido.php?id=<?= $p['Id_Pedido'] ?>" class="btn btn-info btn-sm">
                                    Ver
                                </a>


                            </td>

                            <td>
                                <form action="../../Controlador/pedidocontrolador.php" method="POST" class="d-flex">
                                    <input type="hidden" name="accion" value="estado">
                                    <input type="hidden" name="Id_Pedido" value="<?= $p['Id_Pedido'] ?>">

                                    <select name="Estado" class="form-select form-select-sm me-2">
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="En preparación">En preparación</option>
                                        <option value="Entregado">Entregado</option>
                                        <option value="Cancelado">Cancelado</option>
                                    </select>

                                    <button class="btn btn-warning btn-sm" type="submit">Cambiar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No hay pedidos registrados</td>
                    </tr>
                <?php endif; ?>
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
</body>
</html>
