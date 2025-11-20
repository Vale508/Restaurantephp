<?php

class Database {
    private static $host = "localhost";
    private static $dbname = "restaurantenuevo";
    private static $username = "adminis";
    private static $password = "12345";
    private static $charset = "utf8mb4";
    private static $pdo = null;

    private function __construct() {}

    public static function connect() {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$pdo = new PDO($dsn, self::$username, self::$password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
