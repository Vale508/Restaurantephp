<?php
require_once __DIR__ . "/../Config/conexion.php";

class PedidoDetalle {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function agregarDetalle($idPedido, $idProducto, $cantidad, $precio) {
        $subtotal = $cantidad * $precio;

        $sql = "INSERT INTO pedido_detalle (Id_Pedido, Id_Producto, Cantidad, Precio, Subtotal)
                VALUES (:pedido, :producto, :cantidad, :precio, :subtotal)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":pedido", $idPedido);
        $stmt->bindParam(":producto", $idProducto);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":subtotal", $subtotal);
        return $stmt->execute();
    }

    public function listarDetalles($idPedido) {
        $sql = "SELECT pd.*, p.Nombre 
                FROM pedido_detalle pd
                INNER JOIN producto p ON p.Id_Producto = pd.Id_Producto
                WHERE pd.Id_Pedido = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $idPedido);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
