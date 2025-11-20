<?php
require_once __DIR__ . "/../Config/conexion.php";

class Pedido {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function crearPedido($idMesa) {
        $fecha = date('Y-m-d H:i:s');
        $sql = "INSERT INTO pedido (Id_Mesa, Fecha) VALUES (:mesa, :fecha)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":mesa", $idMesa);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function crearPedidoDomicilio($direccion, $telefono) {
        $mesaDomicilioId = $this->obtenerOCrearMesaDomicilio();
        $fecha = date('Y-m-d H:i:s');

        $columns = ['Id_Mesa', 'Estado', 'Fecha'];
        $values = [':mesa', ':estado', ':fecha'];
        $params = [':mesa' => $mesaDomicilioId, ':estado' => 'Pendiente', ':fecha' => $fecha];

        if ($this->columnExists('DireccionEntrega')) {
            $columns[] = 'DireccionEntrega';
            $values[] = ':direccion';
            $params[':direccion'] = $direccion;
        }

        if ($this->columnExists('TelefonoEntrega')) {
            $columns[] = 'TelefonoEntrega';
            $values[] = ':telefono';
            $params[':telefono'] = $telefono;
        }

        $sql = "INSERT INTO pedido (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $this->db->lastInsertId();
    }

    private function obtenerOCrearMesaDomicilio() {
        $sql = "SELECT Id_Mesa FROM mesa WHERE Numero_Mesa = 'DOMICILIO' LIMIT 1";
        $stmt = $this->db->query($sql);
        $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mesa) {
            return $mesa['Id_Mesa'];
        }

        $sql = "INSERT INTO mesa (Numero_Mesa, Capacidad, Ubicacion, Disponible) 
                VALUES ('DOMICILIO', 0, 'Domicilio', 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    private function columnExists($column) {
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM pedido LIKE ?");
            $stmt->execute([$column]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function listarPedidos() {
        $sql = "SELECT * FROM pedido ORDER BY Fecha DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrarPorFecha($inicio, $fin) {
        $sql = "SELECT * FROM pedido 
                WHERE Fecha BETWEEN :inicio AND :fin
                ORDER BY Fecha DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":inicio", $inicio);
        $stmt->bindParam(":fin", $fin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($idPedido, $estado) {
        $sql = "UPDATE pedido SET Estado = :estado WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":id", $idPedido);
        return $stmt->execute();
    }

    public function obtenerPedido($idPedido) {
        $sql = "SELECT * FROM pedido WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $idPedido);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function mesaOcupada($idMesa) {
        $sql = "SELECT * FROM pedido
                WHERE Id_Mesa = :mesa AND Estado IN ('Pendiente','En preparación')";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":mesa", $idMesa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarTotal($idPedido) {
        $sql = "UPDATE pedido 
                SET Total = (
                    SELECT SUM(Subtotal) 
                    FROM pedido_detalle 
                    WHERE Id_Pedido = :id
                ) 
                WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $idPedido);
        return $stmt->execute();
    }
}
