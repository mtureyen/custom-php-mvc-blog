<?php
declare(strict_types=1);

namespace App\Model;

use App\Service\Database;
use PDO;

/**
 * Represents a comment entity.
 * * Handles all database operations related to comments, such as 
 * creating new entries and retrieving discussion threads for posts.
 */
class Comment
{
    /**
     * @var PDO The active database connection instance.
     */
    private PDO $conn;

    /**
     * Initializes the Comment model.
     * Establishes a connection to the database.
     */
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Creates and saves a new comment in the database.
     *
     * @param int $postId The ID of the post being commented on.
     * @param int $userId The ID of the user who wrote the comment.
     * @param string $content The actual text content of the comment.
     * @return bool Returns true if the comment was successfully saved, false otherwise.
     */
    public function create(int $postId, int $userId, string $content): bool
    {
        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (:pid, :uid, :content)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':pid' => $postId,
            ':uid' => $userId,
            ':content' => $content
        ]);
    }

    /**
     * Retrieves all comments associated with a specific post.
     * * Includes the username of the comment author via a JOIN operation.
     * The results are ordered by creation date (newest first).
     *
     * @param int $postId The ID of the post.
     * @return array An array of associative arrays containing comment data and usernames.
     */
    public function findAllByPostId(int $postId): array
    {
        $sql = "SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE comments.post_id = :pid 
                ORDER BY comments.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':pid' => $postId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}