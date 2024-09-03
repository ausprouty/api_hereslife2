<?php
namespace App\Models\People;

use App\Services\DatabaseService;
use PDO;

class AdministratorModel {
    private $databaseService;
    private $id;
    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $salt;

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
        $query = "INSERT INTO hl_administrators (first_name, last_name, username, password)   
                  VALUES (:first_name, :last_name, :username, :password)";
        $params = [
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':username' => $data['username'],
            ':password' =>  password_hash($data['password'], PASSWORD_DEFAULT) // Hash the password
           
        ];
        $this->databaseService->executeUpdate($query, $params);
        $this->id = $this->databaseService->getLastInsertId();
    }
   
    // Update administrator details
    public function update() {
        $query = "UPDATE hl_administrators 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    username = :username, 
                    password = :password 
                WHERE id = :id";
        
        $params = [
            ':id' => $this->id,
            ':first_name' => $this->first_name,
            ':last_name' => $this->last_name,
            ':username' => $this->username,
            ':password' => password_hash($this->password, PASSWORD_DEFAULT) // Hash the password
        ];
        
        return $this->databaseService->executeQuery($query, $params);
    }
    

    // Verify user credentials
    public function verify($username, $password) {
        $query = "SELECT * FROM hl_administrators WHERE username = :username";
        $params = [
            ':username' => $username
        ];
        $results =  $this->databaseService->executeQuery($query, $params);
        $user = $results->fetch(PDO::FETCH_ASSOC);
        writeLog('AdministratorModel-verify-79', $user);
        writeLog('AdministratorModel-verify-80', $password);

        if ($user && password_verify($password, $user['password'])) {
            // Populate object with user data
            $this->id = $user['id'];
            $this->first_name = $user['first_name'];
            $this->last_name = $user['last_name'];
            $this->username = $user['username'];
            $this->password = $user['password'];
            writeLog('AdministratorModel-verify-89', TRUE);
            return $this->id;
        }
        else{
            writeLog('AdministratorModel-verify-93', FALSE); 
            return 'FALSE';   

        }
    }
    public function getId() {
        return $this->id;
    }
}
