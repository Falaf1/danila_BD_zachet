<?php
// config/database.php
class Database {
    private $host = "localhost";
    private $db_name = "concert_ticket_system";
    private $username = "root";
    private $password = "";  // По умолчанию в XAMPP пароль пустой
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $exception) {
            die(json_encode([
                "success" => false,
                "message" => "Ошибка подключения к базе данных: " . $exception->getMessage()
            ]));
        }
        
        return $this->conn;
    }
}
?>