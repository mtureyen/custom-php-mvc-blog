<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Post;

/**
 * PostService
 *
 * Handles the business logic regarding blog posts.
 * Responsibilities include:
 * - Retrieving posts and formatting data for views (dates, previews).
 * - Validating user input for new posts.
 * - Managing file uploads (images) securely.
 */
class PostService
{
    /**
     * @var Post The injected Post model (Data Access Object).
     */
    private Post $postModel;

    /**
     * Constructor Injection.
     *
     * The service receives the specific Data Access Object (Model) it needs.
     * By injecting the Model instance instead of creating it internally, we achieve:
     * 1. Loose Coupling: The Service doesn't need to know how the DB connection is built.
     * 2. Testability: Ideal for unit testing with mocked Models.
     *
     * @param Post $postModel The data access instance for posts.
     */
    public function __construct(Post $postModel)
    {
        $this->postModel = $postModel;
    }

    /**
     * Retrieves all blog posts and prepares them for display.
     * * Fetches raw data from the database.
     * * Formats the 'created_at' timestamp into a readable date.
     * * Generates a short preview text from the content.
     *
     * @return array An array of posts, enriched with 'display_date' and 'preview_content'.
     */
    public function getAllPosts(): array
    {
        // 1. Fetch raw data from DB
        $posts = $this->postModel->findAll();

        // 2. Prepare data for the template
        foreach ($posts as &$post) {
            
            // Logic: Format date (Day.Month.Year)
            $post['display_date'] = date('d.m.Y', strtotime($post['created_at']));

            // Logic: Generate preview text (truncate to 200 chars)
            $post['preview_content'] = mb_strimwidth($post['content'], 0, 200, "...");
        }

        return $posts;
    }

    /**
     * Fetches a single post by its ID.
     * * Formats the date for the detail view if the post exists.
     *
     * @param int $id The ID of the post.
     * @return array|null The post data array or null if not found.
     */
    public function getPostById(int $id): ?array
    {
        $post = $this->postModel->find($id);
        
        if ($post) {
            // Prepare date for display (Day.Month.Year Hour:Minute)
            $post['display_date'] = date('d.m.Y H:i', strtotime($post['created_at']));
        }

        return $post;
    }
    
    /**
     * Handles the creation of a new post including file upload.
     * * Validates title and content.
     * * Validates and processes the optional image upload (size, type, storage).
     * * Persists data to the database.
     *
     * @param int $userId The ID of the author.
     * @param string $title The post title.
     * @param string $content The post content.
     * @param array|null $file The $_FILES['image'] array, or null.
     * @return string|null Returns null on success, or a translation key string on error.
     */
    public function createPost(int $userId, string $title, string $content, ?array $file): ?string
    {
        // 1. Validation
        if (empty($title) || empty($content)) {
            return trans('err_fill_fields'); 
        }

        $imagePath = null;

        // 2. Image Processing
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            
            // Check size (Max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                return trans('err_img_too_big'); 
            }

            // Check file type (Allow-list)
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                return trans('err_img_type');
            }

            // Generate unique name and define target directory
            $newName = uniqid() . "." . $ext;
            $targetDir = __DIR__ . '/../../public/uploads/';
            
            // Create directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }

            // Move file
            if (move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
                $imagePath = 'uploads/' . $newName;
            } else {
                return trans('err_img_save');
            }
        } elseif (isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            // Usually happens if PHP upload_max_filesize is exceeded
            return trans('err_server_limit');
        }

        // 3. Save via Model
        $success = $this->postModel->create($userId, $title, $content, $imagePath);

        if (!$success) {
            return trans('err_db');
        }

        // Success
        return null;
    }
}