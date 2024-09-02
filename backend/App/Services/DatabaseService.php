<?php
namespace App\Services;

use PDO;
use Exception;

class DatabaseService{

    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    private $dbConnection;

    public function __construct($dummy = 'standard'){
        $configType = 'standard';
        if (!isset(DATABASES[$configType])) {
            writeLog('DatabaseService-17', 'Configuration type not found');
            throw new InvalidArgumentException("Configuration type '$configType' not found.");
        }
        $config = DATABASES[$configType];
        $this->host = $config['DB_HOST'] ?: 'localhost';
        $this->username = $config['DB_USERNAME'];
        $this->password = $config['DB_PASSWORD'];
        $this->database = $config['DB_DATABASE'];
        $this->port = $config['DB_PORT'] ?: 3306;
        $this->charset = $config['DB_CHARSET'];
        $this->collation = $config['DB_COLLATION'];
        $this->prefix = $config['PREFIX'];     
        $this->connect();
      }

    private function connect() {
      try {
          $dsn = "mysql:host={$this->host};port= {$this->port};dbname={$this->database};charset=utf8mb4";
          $this->dbConnection = new PDO($dsn, $this->username, $this->password);
          // Set PDO error mode to exception
          $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
          throw new Exception("Failed to connect to the database: " . $e->getMessage());
      }
    }

   /**
     * Executes a SQL query with optional parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params Optional parameters for prepared statement.
     * @return PDOStatement The PDOStatement object.
     * @throws Exception If query execution fails.
     */
    public function executeQuery(string $query, array $params = []) {
        try {
     
            $statement = $this->dbConnection->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            throw new Exception("Error executing the query: " . $e->getMessage());
        }
    }
/**
     * Executes a SQL query with optional parameters.
     *
     * @param string $query The SQL query to execute.
     * @param array $params Optional parameters for prepared statement.
     * @return rowCount The number of rows affected.
     * @throws Exception If query execution fails.
     */
 
    public function executeUpdate(string $query, array $params = []) {
        try {
            $statement = $this->dbConnection->prepare($query);
            $statement->execute($params);
            return $statement->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error executing the update: " . $e->getMessage());
        }
    }
    public function getLastInsertId() {
        return $this->dbConnection->lastInsertId();
    }
    

    /**
     * Closes the database connection.
     */
    public function closeConnection() {
        $this->dbConnection = null;
    }
}
