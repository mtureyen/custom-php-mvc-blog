<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Comment;

/**
 * CommentService
 *
 * Handles the business logic for the comment section.
 * This includes retrieving comments, formatting them for the view (e.g., dates),
 * and validating new comments before saving them.
 */
class CommentService
{
    /**
     * @var Comment The database model for comment operations.
     */
    private Comment $commentModel;

    /**
     * Initializes the service and its dependencies.
     */
    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    /**
     * Retrieves all comments for a specific post.
     * * Fetches raw data from the model.
     * * Formats the creation date into a readable string ('display_date')
     * so the view doesn't have to perform logic.
     *
     * @param int $postId The ID of the post.
     * @return array An array of associative arrays representing comments.
     */
    public function getCommentsForPost(int $postId): array
    {
        $comments = $this->commentModel->findAllByPostId($postId);

        // Iterate through comments to format the date for the view
        foreach ($comments as &$comment) {
            $comment['display_date'] = date('d.m.Y H:i', strtotime($comment['created_at']));
        }

        return $comments;
    }

    /**
     * Adds a new comment to a post.
     * * Trims and validates the input content.
     * * Persists the comment to the database via the Model.
     *
     * @param int $postId The ID of the post being commented on.
     * @param int $userId The ID of the author.
     * @param string $content The text content of the comment.
     * @return bool Returns true if the comment was successfully created, false otherwise.
     */
    public function addComment(int $postId, int $userId, string $content): bool
    {
        $content = trim($content);
        
        // Basic validation: Content must not be empty
        if ($postId <= 0 || empty($content)) {
            return false;
        }

        // Call Model to save data
        return $this->commentModel->create($postId, $userId, $content);
    }
}