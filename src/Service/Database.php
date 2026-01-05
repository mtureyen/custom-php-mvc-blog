<?php
declare(strict_types=1);

namespace App\Service;

use PDO;
use PDOException;

/**
 * Service responsible for managing the database connection.
 * * Uses PHP Data Objects (PDO) to provide a secure and consistent interface
 * for interacting with the MySQL database.
 */
class Database
{
    /**
     * @var PDO|null Holds the active database connection instance.
     */
    private ?PDO $pdo = null;

    /**
     * Establishes and retrieves the database connection.
     * * Checks if a connection already exists to avoid overhead.
     * * Reads configuration from environment variables (Docker friendly),
     * falling back to default values if not set.
     *
     * @return PDO|null The active PDO connection instance.
     */
    public function getConnection(): ?PDO
    {
        if ($this->pdo === null) {
            // Retrieve credentials from environment variables (set in docker-compose)
            // Fallback values are provided for local development/testing
            $host = $_ENV['DB_HOST'] ?? 'db'; // Note: 'db' is the service name in Docker
            $db   = $_ENV['DB_NAME'] ?? 'blog_db';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? 'root';

            try {
                // Construct the Data Source Name (DSN)
                $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
                
                // Create a new PDO instance
                $this->pdo = new PDO($dsn, $user, $pass);
                
                // Configure PDO options:
                // 1. Throw exceptions on errors (easier debugging)
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // 2. Fetch results as associative arrays by default
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
            } catch (PDOException $e) {
                // Halt execution if the connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }
        
        return $this->pdo;
    }
}