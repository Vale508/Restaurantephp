<?php
require_once __DIR__ . '/../Config/conexion.php';

class Plato {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Registrar plato
    public function registrarPlato($Nombre, $Descripcion, $Precio, $Categoria = null, $Imagen = null, $Id_Menu = null, $Disponible = 1) {
        $columns = ['Nombre' => $Nombre, 'Descripcion' => $Descripcion, 'Precio' => $Precio];
        if ($this->columnExists('Imagen')) {
            $columns['Imagen'] = $Imagen;
        }


        $menuId = null;
        if ($Categoria !== null && $Categoria !== '') {
            if (is_numeric($Categoria)) {
                $menuId = (int)$Categoria;
            } else {
                if ($this->tableExists('menu')) {
                    $menuId = $this->getOrCreateMenu($Categoria);
                }
            }
        }

        if ($this->columnExists('Id_Menu') && $menuId !== null) {
            $columns['Id_Menu'] = $menuId;
        }


        if ($this->columnExists('Categoria')) {
            $columns['Categoria'] = $Categoria;
        } elseif ($this->columnExists('CategoriaTexto')) {
            $columns['CategoriaTexto'] = $Categoria;
        }

        if ($this->columnExists('Disponible')) {
            $columns['Disponible'] = ($Disponible ? 1 : 0);
        }

        $colNames = implode(', ', array_keys($columns));
        $placeholders = rtrim(str_repeat('?, ', count($columns)), ', ');
        $sql = "INSERT INTO producto ({$colNames}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($columns));
    }

    // Listar platos
    public function listarPlatos($soloDisponibles = false) {
        try {
            if ($this->columnExists('Id_Menu') && $this->tableExists('menu')) {
                $sql = "SELECT p.*, m.Nombre AS CategoriaNombre FROM producto p LEFT JOIN menu m ON p.Id_Menu = m.Id_Menu";
                if ($soloDisponibles && $this->columnExists('Disponible')) {
                    $sql .= " WHERE p.Disponible = 1";
                }
                $stmt = $this->db->query($sql);
                return $stmt->fetchAll();
            }
        } catch (PDOException $e) {
        }

        $sql = "SELECT * FROM producto";
        if ($soloDisponibles && $this->columnExists('Disponible')) {
            $sql .= " WHERE Disponible = 1";
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Obtener plato por ID 
    public function obtenerPlatoPorId($id) {
        try {
            if ($this->columnExists('Id_Menu') && $this->tableExists('menu')) {
                $sql = "SELECT p.*, m.Nombre AS CategoriaNombre FROM producto p LEFT JOIN menu m ON p.Id_Menu = m.Id_Menu WHERE p.Id_Producto = :id LIMIT 1";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $id]);
                return $stmt->fetch();
            }
        } catch (PDOException $e) {
        }

        $sql = "SELECT * FROM producto WHERE Id_Producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Editar plato
    public function editarPlato($id, $Nombre, $Descripcion, $Precio, $Categoria = null, $Imagen = null, $Id_Menu = null, $Disponible = 1) {
        $fields = ['Nombre' => $Nombre, 'Descripcion' => $Descripcion, 'Precio' => $Precio];
        if ($this->columnExists('Imagen')) {
            $fields['Imagen'] = $Imagen;
        }

        $menuId = null;
        if ($Categoria !== null && $Categoria !== '') {
            if (is_numeric($Categoria)) {
                $menuId = (int)$Categoria;
            } else {
                if ($this->tableExists('menu')) {
                    $menuId = $this->getOrCreateMenu($Categoria);
                }
            }
        }
        if ($this->columnExists('Id_Menu') && $menuId !== null) {
            $fields['Id_Menu'] = $menuId;
        }
        if ($this->columnExists('Disponible')) {
            $fields['Disponible'] = ($Disponible ? 1 : 0);
        }

        $setParts = [];
        $params = [];
        foreach ($fields as $col => $val) {
            $setParts[] = "{$col} = :{$col}";
            $params[":{$col}"] = $val;
        }
        $params[':id'] = $id;

        $sql = "UPDATE producto SET " . implode(', ', $setParts) . " WHERE Id_Producto = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    // Eliminar plato
    public function eliminarPlato($id) {
        try {
            $refStmt = $this->db->prepare("SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = 'producto' AND REFERENCED_COLUMN_NAME = 'Id_Producto' AND TABLE_SCHEMA = DATABASE()");
            $refStmt->execute();
            $refs = $refStmt->fetchAll();
            $blocking = [];
            foreach ($refs as $r) {
                $table = $r['TABLE_NAME'];
                $col = $r['COLUMN_NAME'];
                $countStmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM {$table} WHERE {$col} = :id");
                $countStmt->execute([':id' => $id]);
                $row = $countStmt->fetch();
                $cnt = isset($row['cnt']) ? (int)$row['cnt'] : 0;
                if ($cnt > 0) {
                    $blocking[] = [ 'table' => $table, 'count' => $cnt ];
                }
            }

            if (!empty($blocking)) {
                $parts = array_map(function($b){ return "{$b['count']} en {$b['table']}"; }, $blocking);
                throw new Exception("No se puede eliminar el producto porque está referenciado: " . implode(', ', $parts));
            }

            $sql = "DELETE FROM producto WHERE Id_Producto = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    // Buscar platos por nombre
    public function buscarPlatos($nombre) {
        try {
            if ($this->columnExists('Id_Menu') && $this->tableExists('menu')) {
                $sql = "SELECT p.*, m.Nombre AS CategoriaNombre FROM producto p LEFT JOIN menu m ON p.Id_Menu = m.Id_Menu WHERE p.Nombre LIKE :nombre";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':nombre' => "%$nombre%"]);
                return $stmt->fetchAll();
            }
        } catch (PDOException $e) {
        }

        $sql = "SELECT * FROM producto WHERE Nombre LIKE :nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nombre' => "%$nombre%"]);
        return $stmt->fetchAll();
    }

    private function columnExists($columnName) {
        try {
            $stmt = $this->db->prepare("SHOW COLUMNS FROM producto LIKE :col");
            $stmt->execute([':col' => $columnName]);
            $row = $stmt->fetch();
            return (bool)$row;
        } catch (Exception $e) {
            return false;
        }
    }

    private function tableExists($tableName) {
        try {
            $stmt = $this->db->prepare("SHOW TABLES LIKE :t");
            $stmt->execute([':t' => $tableName]);
            $row = $stmt->fetch();
            return (bool)$row;
        } catch (Exception $e) {
            return false;
        }
    }

    // obtener o crear menú por nombre
    private function getOrCreateMenu($nombre) {
        try {
            $sql = "SELECT Id_Menu FROM menu WHERE Nombre = :nombre LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':nombre' => $nombre]);
            $row = $stmt->fetch();
            if ($row && isset($row['Id_Menu'])) return $row['Id_Menu'];

            $ins = $this->db->prepare("INSERT INTO menu (Nombre) VALUES (?)");
            $ins->execute([$nombre]);
            return (int)$this->db->lastInsertId();
        } catch (Exception $e) {
            return null;
        }
    }
}

?>