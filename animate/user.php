<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = $this->conn->getQuery("Get all users");
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = $this->conn->getQuery("Get user by ID");
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->password = $row['password'];
    }

    public function create() {
        $query = $this->conn->getQuery("Create new user");
        $stmt = $this->conn->getConnection()->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->password);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = $this->conn->getQuery("Update user");
        $stmt = $this->conn->getConnection()->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $stmt->bindParam(1, $this->username);
        $stmt->bindParam(2, $this->email);
        $stmt->bindParam(3, $this->password);
        $stmt->bindParam(4, $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = $this->conn->getQuery("Delete user");
        $stmt = $this->conn->getConnection()->prepare($query);
        $stmt->bindParam(1, $this->id);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 