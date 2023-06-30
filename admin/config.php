<?php

class Database {
    private $host = "localhost";
    private $username = "id20848551_root";
    private $password = "2\=jAOCnKG\pE^E7";
    private $dbname = "id20848551_tienda_online";

    public function getConnection() {
        $conn = null;
        try {
            $conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectarse a la base de datos: " . $e->getMessage();
        }
        return $conn;
    }
}
?>
