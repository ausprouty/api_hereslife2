<?php

namespace App\Services\Database;

use \PDO;
use PDOException;
use Exception;
use InvalidArgumentException;

/**
 * DatabaseService
 *
 * Purpose: Provides a layer for handling database connections and query execution using PDO.
 * 
 * Responsibilities:
 * - Establish a database connection
 * - Execute SQL queries and updates
 * - Retrieve the last inserted ID
 * - Close the database connection
 */
class DatabaseService {

    private $host;
    private $username;
    private $password;
    private $database;
    private $port;
    private $charset;
    private $collation;
    private $prefix;
    private $dbConnection;

    /**
     * Constructor that initializes database configuration and establishes a connection.
     *
     * @param string $configType The type of database configuration to use (default: 'standard').
     * @throws InvalidArgumentException If the specified configuration type is not found.
     * @throws Exception If the database connection fails.
     */
    public function __construct($configType = 'standard') {
        if (!isset(DATABASES[$configType])) {
            writeLog('DatabaseService-17', 'Configuration type not found');
            throw new InvalidArgumentException("Configuration type '$configType' not found.");
        }

        $config = DATABASES[$configType];
        $this->host = $config['DB_HOST'] ?? 'localhost';
        $this->username = $config['DB_USERNAME'];
        $this->password = $config['DB_PASSWORD'];
        $this->database = $config['DB_DATABASE'];
        $this->port = $config['DB_PORT'] ?? 3306;
        $this->charset = $config['DB_CHARSET'];
        $this->collation = $config['DB_COLLATION'];
        $this->prefix = $config['PREFIX'] ?? '';

        $this->connect();
    }

    /**
     * Establishes a connection to the database using PDO.
     *
     * @throws Exception If the connection to the database fails.
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset={$this->charset}";
            $this->dbConnection = new PDO($dsn, $this->username, $this->password);
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
     * @return \PDOStatement The PDOStatement object.
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
     * Executes a SQL update (INSERT, UPDATE, DELETE) with optional parameters.
     *
     * @param string $query The SQL update query to execute.
     * @param array $params Optional parameters for the prepared statement.
     * @return int The number of rows affected.
     * @throws Exception If the update query execution fails.
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

    /**
     * Retrieves the last inserted ID from the database.
     *
     * @return string The last inserted ID.
     */
    public function getLastInsertId(): string {
        return $this->dbConnection->lastInsertId();
    }

    /**
     * Closes the database connection.
     */
    public function closeConnection() {
        $this->dbConnection = null;
    }
}
