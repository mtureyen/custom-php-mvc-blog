<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Post;
use App\Model\Comment;

/**
 * Controller responsible for managing blog posts.
 * * Handles the creation of posts (including file uploads),
 * displaying individual entries, and processing user comments.
 */
class PostController
{
    /**
     * Handles the creation of a new blog post.
     * * Enforces authentication: Redirects guests to the login page.
     * * Processes the form submission: Validates title/content and handles image uploads.
     * * Validates images by size (max 5MB) and type (jpg, png, gif, webp).
     * * Saves the post and redirects to the homepage upon success.
     *
     * @return void
     */
    public function create(): void
    {
        // 1. Security Check
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        $error = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            $imagePath = null;

            // 2. Handle Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                
                $tmpName = $_FILES['image']['tmp_name'];
                $name = basename($_FILES['image']['name']);
                $size = $_FILES['image']['size']; // Size in bytes

                // --- LIMIT CHECK ---
                // 5 MB = 5 * 1024 * 1024 Bytes
                $maxSize = 5 * 1024 * 1024; 

                if ($size > $maxSize) {
                    $error = trans('err_img_too_big');
                }
                
                // Proceed only if no error occurred so far
                if (!$error) {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                    if (in_array($ext, $allowed)) {
                        // Generate unique name to prevent overwriting
                        $newName = uniqid() . "." . $ext;
                        $targetDir = __DIR__ . '/../../public/uploads/';
                        $targetFile = $targetDir . $newName;

                        if (move_uploaded_file($tmpName, $targetFile)) {
                            $imagePath = 'uploads/' . $newName; 
                        } else {
                            $error = trans('err_img_save');
                        }
                    } else {
                        $error = trans('err_img_type');
                    }
                }
            }
            // Handle server-side upload errors (e.g. exceeding php.ini limits)
            elseif (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE) {
                $error = trans('err_server_limit');
            }

            // 3. Validate Text Fields & Save
            if (empty($title) || empty($content)) {
                // Only overwrite error if none exists yet
                if (!$error) {
                    $error = trans('err_fill_fields');
                }
            }
            elseif (!$error) {
                $postModel = new Post();
                // Pass null for imagePath if no image was uploaded
                $postModel->create((int)$_SESSION['user_id'], $title, $content, $imagePath);
                
                header("Location: /");
                exit;
            }
        }

        require_once __DIR__ . '/../../templates/post/create.php';
    }


    /**
     * Displays a single blog post and its associated comments.
     * * Retrieves the post ID from the URL parameters.
     * * Fetches the post details and all comments from the database.
     * * Renders the 'show' template.
     *
     * @return void
     */
    public function show(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /");
            exit;
        }

        // 1. Load Post
        $postModel = new Post();
        $post = $postModel->find((int)$id);

        if (!$post) {
            echo "Post not found!"; // Could be replaced with a 404 template later
            return;
        }

        // 2. Load Comments
        $commentModel = new Comment();
        $comments = $commentModel->findAllByPostId((int)$id);

        // Render View (Variables $post and $comments are available in the template)
        require_once __DIR__ . '/../../templates/post/show.php';
    }

    /**
     * Handles the submission of a new comment.
     * * Enforces authentication.
     * * Validates the input and saves the comment via the Comment model.
     * * Redirects the user back to the detailed post view.
     *
     * @return void
     */
    public function addComment(): void
    {
        // Security: Only logged-in users!
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $postId = (int)($_POST['post_id'] ?? 0);
            $content = trim($_POST['content'] ?? '');

            if ($postId > 0 && !empty($content)) {
                $commentModel = new Comment();
                $commentModel->create($postId, (int)$_SESSION['user_id'], $content);
            }
            
            // Redirect back to the post
            header("Location: /post/show?id=" . $postId);
            exit;
        }
    }
}