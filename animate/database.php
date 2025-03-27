<?php
class Database {
    private $host = "localhost";
    private $db_name = "animate";
    private $username = "root";
    private $password = "";
    public $conn;
    private $queries = [];

    public function __construct() {
        $this->loadQueries();
    }

    private function loadQueries() {
        $sqlFile = __DIR__ . '/queries.sql';
        if (file_exists($sqlFile)) {
            $content = file_get_contents($sqlFile);
            $queries = explode(';', $content);
            foreach ($queries as $query) {
                $query = trim($query);
                if (!empty($query)) {
                    $comment = '';
                    if (preg_match('/--\s*(.+)/', $query, $matches)) {
                        $comment = trim($matches[1]);
                        $query = preg_replace('/--.*$/', '', $query);
                    }
                    $this->queries[$comment] = trim($query);
                }
            }
        }
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }

    public function getQuery($name) {
        return $this->queries[$name] ?? null;
    }
}
?> 