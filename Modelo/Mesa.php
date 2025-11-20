<?php
require_once __DIR__ . "/../Config/conexion.php";

class Mesa {
    private $db;
    public function __construct() {
        $this->db = Database::connect();
    }

    public function RegistrarMesa($numero, $capacidad, $ubicacion, $disponible) {
        $sql = "INSERT INTO mesa (Numero_Mesa, Capacidad, Ubicacion, Disponible)
                VALUES (:numero, :capacidad, :ubicacion, :disponible)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':capacidad', $capacidad);
        $stmt->bindParam(':ubicacion', $ubicacion);
        $stmt->bindParam(':disponible', $disponible);
        return $stmt->execute();
    }

    public function ListarMesas() {
        $sql = "SELECT * FROM mesa";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ActualizarEstado($id, $disponible) {
        $sql = "UPDATE mesa SET Disponible = :disponible WHERE Id_Mesa = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':disponible', $disponible);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function EliminarMesa($id) {
        $sql = "DELETE FROM mesa WHERE Id_Mesa = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
