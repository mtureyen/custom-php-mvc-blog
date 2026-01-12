<?php
declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;

/**
 * Represents a user entity.
 * * Handles database operations regarding user management, such as 
 * registration and data retrieval for authentication.
 */
class User
{
    /**
     * @var PDO The injected database connection instance.
     */
    private PDO $conn;

    /**
     * Constructor with Dependency Injection.
     *
     * Receives a pre-configured PDO instance.
     * By injecting the connection, we ensure that the User model participates
     * in the same transaction context as other models and avoids redundant connections.
     *
     * @param PDO $pdo The active PDO connection.
     */
    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    /**
     * Creates a new user in the database.
     * * Handles potential errors internally (e.g., if the username already exists).
     *
     * @param string $username The desired username.
     * @param string $passwordHash The securely hashed password (do not pass plain text!).
     * @return bool Returns true if the user was created successfully, false otherwise.
     */
    public function create(string $username, string $passwordHash): bool
    {
        $sql = "INSERT INTO users (username, password_hash) VALUES (:name, :pass)";
        $stmt = $this->conn->prepare($sql);

        try {
            $stmt->execute([
                ':name' => $username,
                ':pass' => $passwordHash
            ]);
            return true;
        } catch (PDOException $e) {
            // Returns false if execution fails (e.g., duplicate username constraint)
            return false;
        }
    }

    /**
     * Finds a user record by their username.
     * * Commonly used during the login process to verify credentials.
     *
     * @param string $username The username to search for.
     * @return array|null Returns the user data as an associative array, or null if the user does not exist.
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':username' => $username]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}