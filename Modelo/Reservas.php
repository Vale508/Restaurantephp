    <?php
    require_once __DIR__ . "/../Config/conexion.php";

    class Reserva {
        private $db;
        public function __construct() {
            $this->db = Database::connect();
        }

        public function ListarReservas() {
            $sql = "SELECT r.*, m.Numero_Mesa, m.Ubicacion 
                    FROM reserva r INNER JOIN mesa m ON r.Id_Mesa = m.Id_Mesa";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function ActualizarEstadoReserva($id, $estado) {
            $sql = "UPDATE reserva SET Estado = :estado WHERE Id_Reserva = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        // 🔹 Crear una nueva reserva
        public function CrearReserva($numero_personas, $id_usuario, $id_mesa, $fecha_reserva, $estado = 'Pendiente') {
            $sql = "INSERT INTO reserva (Numero_Personas, Id_Usuario, Id_Mesa, Fecha_Reserva, Estado)
                    VALUES (:numero_personas, :id_usuario, :id_mesa, :fecha_reserva, :estado)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':numero_personas', $numero_personas);
            $stmt->bindParam(':id_usuario', $id_usuario);
            $stmt->bindParam(':id_mesa', $id_mesa);
            $stmt->bindParam(':fecha_reserva', $fecha_reserva);
            $stmt->bindParam(':estado', $estado);
            return $stmt->execute();
        }
    }
    ?>
