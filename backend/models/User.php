<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (email, password, role, status) 
                  VALUES (:email, :password, :role, :status)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $email = htmlspecialchars(strip_tags($data['email']));
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $role = htmlspecialchars(strip_tags($data['role']));
        $status = isset($data['status']) ? htmlspecialchars(strip_tags($data['status'])) : 'active';

        // Bind
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":status", $status);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT id, email, password, role, status FROM " . $this->table_name . " 
                  WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($password, $row['password']) && $row['status'] == 'active') {
            return $row;
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT id, email, role, status, created_at FROM " . $this->table_name . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($email) {
        $query = "SELECT id, email, role, status, created_at FROM " . $this->table_name . " 
                  WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " SET ";
        $updates = [];
        $params = [];

        if(isset($data['email'])) {
            $updates[] = "email = :email";
            $params[':email'] = htmlspecialchars(strip_tags($data['email']));
        }
        if(isset($data['password'])) {
            $updates[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if(isset($data['role'])) {
            $updates[] = "role = :role";
            $params[':role'] = htmlspecialchars(strip_tags($data['role']));
        }
        if(isset($data['status'])) {
            $updates[] = "status = :status";
            $params[':status'] = htmlspecialchars(strip_tags($data['status']));
        }

        if(empty($updates)) {
            return false;
        }

        $query .= implode(", ", $updates) . " WHERE id = :id";
        $params[':id'] = $id;

        $stmt = $this->conn->prepare($query);

        foreach($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function getAll($limit = 50, $offset = 0, $role = null) {
        $query = "SELECT id, email, role, status, created_at FROM " . $this->table_name;
        
        if($role) {
            $query .= " WHERE role = :role";
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        
        if($role) {
            $stmt->bindParam(":role", $role);
        }
        
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count($role = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        
        if($role) {
            $query .= " WHERE role = :role";
        }

        $stmt = $this->conn->prepare($query);
        
        if($role) {
            $stmt->bindParam(":role", $role);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'];
    }
}
?>
