<?php
namespace App\Models\People;

use App\Services\DatabaseService;
use PDO;

class AdministratorModel {
    private $databaseService;
    public $id;
    public $first_name;
    public $last_name;
    public $username;
    public $password;
    public $salt;

    // Use dependency injection to pass the DatabaseService instance
    public function __construct(DatabaseService $databaseService) {  
        $this->databaseService = $databaseService;
    }
    // At this time we only allow one administrator
    public function exists() {
        $query = "SELECT count(*) as cnt FROM hl_administrators";
        $result = $this->databaseService->executeQuery($query);
        $data = $result->fetch(PDO::FETCH_ASSOC);
        // Assuming executeQuery returns a result set
        if ($data) {
            if ($data['cnt'] >0){
                return 'TRUE';
            };  // Adjust based on your database service's return structure  
        }
        return 'FALSE';  // In case the query fails or returns no result
     
    }

    // Create a new administrator
    public function create($data) {
        $salt = $this->generateSalt();
        $hashedPassword = $this->hashPassword($data['password'], $salt);
        $query = "INSERT INTO hl_administrators (first_name, last_name, username, password, salt)   
                  VALUES (:first_name, :last_name, :username, :password, :salt)";
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':username' => $data['username'],
            ':password' => $hashedPassword,
            ':salt' => $salt // Hash the password
        ];
        return $this->databaseService->executeUpdate($query, $params);
    }
   
    // Update administrator details
    public function update() {
        $sql = "UPDATE hl_administrators SET first_name = :first_name, last_name = :last_name, username = :username, password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_DEFAULT)); // Hash the password

        return $stmt->execute();
    }

    // Verify user credentials
    public function verify($username, $password) {
        $sql = "SELECT * FROM hl_administrators WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Populate object with user data
            $this->id = $user['id'];
            $this->first_name = $user['first_name'];
            $this->last_name = $user['last_name'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            $this->time_created = $user['time_created'];
            return true;
        }

        return false;
    }
     // Generate a random salt
     private function generateSalt($length = 16) {
        return bin2hex(random_bytes($length));
    }

    // Hash a password with a given salt
    private function hashPassword($password, $salt) {
        return hash('sha256', $password . $salt);
    }

    // Verify a password against a hash and salt
    private function verifyPassword($password, $hashedPassword, $salt) {
        return hash('sha256', $password . $salt) === $hashedPassword;
    }
}
