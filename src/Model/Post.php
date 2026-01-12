<?php
declare(strict_types=1);

namespace App\Model;

use PDO;

/**
 * Represents a blog post entity.
 * * Handles database operations for blog entries, including creation,
 * retrieval of all posts, and fetching single post details.
 */
class Post
{
    /**
     * @var PDO The injected database connection instance.
     */
    private PDO $conn;

    /**
     * Constructor with Dependency Injection.
     *
     * Accepts a pre-configured PDO instance instead of creating a new one.
     * This allows the Service Layer to manage the database connection lifecycle
     * and ensures that all models share the same transaction context.
     *
     * @param PDO $pdo The active PDO connection.
     */
    public function __construct(PDO $pdo)
    {
        $this->conn = $pdo;
    }

    /**
     * Creates a new blog post in the database.
     *
     * @param int $userId The ID of the author (user).
     * @param string $title The headline of the post.
     * @param string $content The main body text.
     * @param string|null $imageUrl (Optional) Path to an uploaded image, or null if none.
     * @return bool Returns true if the post was successfully created.
     */
    public function create(int $userId, string $title, string $content, ?string $imageUrl = null): bool
    {
        $sql = "INSERT INTO posts (user_id, title, content, image_url) VALUES (:uid, :title, :content, :img)";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':uid'     => $userId,
            ':title'   => $title,
            ':content' => $content,
            ':img'     => $imageUrl
        ]);
    }

    /**
     * Retrieves all blog posts from the database.
     * * Includes the author's username via a JOIN operation.
     * Results are ordered by creation date in descending order (newest first).
     *
     * @return array An array of associative arrays containing post data and usernames.
     */
    public function findAll(): array
    {
        $sql = "SELECT posts.*, users.username 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY posts.created_at DESC";

        $stmt = $this->conn->query($sql);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Finds a specific blog post by its ID.
     * * Includes the author's username via a JOIN operation.
     *
     * @param int $id The unique ID of the post.
     * @return array|null Returns the post data as an associative array, or null if not found.
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT posts.*, users.username 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                WHERE posts.id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
}