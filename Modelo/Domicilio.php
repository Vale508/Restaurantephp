<?php
require_once __DIR__ . '/../Config/conexion.php';

class Domicilio {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Listar domicilios/pedidos filtra por Fecha
    public function listarDomicilios($desde = null, $hasta = null) {
        $params = [];
        $sql = "SELECT p.*, 
                u.Nombre AS ClienteNombre, 
                u.Telefono AS ClienteTelefono, 
                u.Direccion AS ClienteDireccion,
                m.Numero_Mesa";
        

        $hasDireccion = $this->columnExistsOnTable('pedido', 'DireccionEntrega');
        $hasTelefono = $this->columnExistsOnTable('pedido', 'TelefonoEntrega');
        $hasEntregado = $this->columnExistsOnTable('pedido', 'Entregado');
        

        if ($hasDireccion) {
            $sql .= ", p.DireccionEntrega";
        }
        if ($hasTelefono) {
            $sql .= ", p.TelefonoEntrega";
        }
        if ($hasEntregado) {
            $sql .= ", p.Entregado";
        }

        $sql .= " FROM pedido p 
                  LEFT JOIN usuario u ON p.Id_Usuario = u.Id_Usuario
                  LEFT JOIN mesa m ON p.Id_Mesa = m.Id_Mesa";
        
        $conds = [];
        
        // Filtrar por domicilios
        $conds[] = "p.Id_Mesa = 5";
        
        if ($desde) {
            $conds[] = "p.Fecha >= :desde";
            $params[':desde'] = $desde;
        }
        if ($hasta) {
            $conds[] = "p.Fecha <= :hasta";
            $params[':hasta'] = $hasta;
        }
        
        if (!empty($conds)) {
            $sql .= ' WHERE ' . implode(' AND ', $conds);
        }
        
        $sql .= " ORDER BY p.Fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($resultados as &$pedido) {
            if (!isset($pedido['DireccionEntrega']) || empty($pedido['DireccionEntrega'])) {
                $pedido['DireccionEntrega'] = $pedido['ClienteDireccion'] ?? '';
            }
            if (!isset($pedido['TelefonoEntrega']) || empty($pedido['TelefonoEntrega'])) {
                $pedido['TelefonoEntrega'] = $pedido['ClienteTelefono'] ?? '';
            }
        }
        
        return $resultados;
    }

    public function obtenerPedido($id) {
        $sql = "SELECT p.*, 
                u.Nombre AS ClienteNombre, 
                u.Telefono AS ClienteTelefono, 
                u.Direccion AS ClienteDireccion";
        
        if ($this->columnExistsOnTable('pedido', 'DireccionEntrega')) {
            $sql .= ", p.DireccionEntrega";
        }
        if ($this->columnExistsOnTable('pedido', 'TelefonoEntrega')) {
            $sql .= ", p.TelefonoEntrega";
        }
        
        $sql .= " FROM pedido p 
                  LEFT JOIN usuario u ON p.Id_Usuario = u.Id_Usuario 
                  WHERE p.Id_Pedido = :id 
                  LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($pedido) {
            if (!isset($pedido['DireccionEntrega']) || empty($pedido['DireccionEntrega'])) {
                $pedido['DireccionEntrega'] = $pedido['ClienteDireccion'] ?? '';
            }
            if (!isset($pedido['TelefonoEntrega']) || empty($pedido['TelefonoEntrega'])) {
                $pedido['TelefonoEntrega'] = $pedido['ClienteTelefono'] ?? '';
            }
        }
        
        return $pedido;
    }

    public function actualizarContactoEntrega($id, $direccion, $telefono) {
        $fields = [];
        $params = [':id' => $id];
        
        if ($this->columnExistsOnTable('pedido', 'DireccionEntrega')) {
            $fields[] = 'DireccionEntrega = :direccion';
            $params[':direccion'] = $direccion;
        }
        if ($this->columnExistsOnTable('pedido', 'TelefonoEntrega')) {
            $fields[] = 'TelefonoEntrega = :telefono';
            $params[':telefono'] = $telefono;
        }

        if (empty($fields)) {
            $pedido = $this->obtenerPedido($id);
            if ($pedido && !empty($pedido['Id_Usuario'])) {
                $sql = "UPDATE usuario SET Direccion = :direccion, Telefono = :telefono WHERE Id_Usuario = :uid";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    ':direccion' => $direccion, 
                    ':telefono' => $telefono, 
                    ':uid' => $pedido['Id_Usuario']
                ]);
            }
            return false;
        }

        $sql = "UPDATE pedido SET " . implode(', ', $fields) . " WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function marcarEntregado($id) {
        if ($this->columnExistsOnTable('pedido', 'Entregado')) {
            $sql = "UPDATE pedido SET Entregado = 1 WHERE Id_Pedido = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        }

        $sql = "UPDATE pedido SET Estado = 'Entregado' WHERE Id_Pedido = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function columnExistsOnTable($table, $column) {
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM {$table} LIKE :col");
            $stmt->execute([':col' => $column]);
            $row = $stmt->fetch();
            return (bool)$row;
        } catch (Exception $e) {
            return false;
        }
    }
}

?>