<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\PostService;
use App\Service\CommentService;
use App\Service\TemplateService;

/**
 * PostController
 *
 * Manages the lifecycle of blog posts, including creation, display,
 * and comment handling. It acts as an orchestrator between the HTTP request,
 * the domain services (Post/Comment), and the view rendering.
 */
class PostController
{
    /**
     * @var PostService Handles business logic for posts (validation, upload, storage).
     */
    private PostService $postService;

    /**
     * @var CommentService Handles business logic for comments.
     */
    private CommentService $commentService;

    /**
     * @var TemplateService Handles view rendering and error management.
     */
    private TemplateService $templateService;

    /**
     * Initializes the controller and its dependencies.
     * * Instantiates the necessary services for post and comment management.
     */
    public function __construct()
    {
        $this->postService = new PostService();
        $this->commentService = new CommentService();
        $this->templateService = new TemplateService();
    }

    /**
     * Handles the creation workflow for a new blog post.
     * * Checks if the user is logged in.
     * * Processes the POST request containing title, content, and file upload.
     * * Delegates validation and creation to PostService.
     * * Redirects to home on success or displays errors on failure.
     *
     * @return void
     */
    public function create(): void
    {
        // 1. Auth Check
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        // 2. Process Form (POST)
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $file = $_FILES['image'] ?? null;

            // Call Service: Create the post
            // Returns NULL on success, or an error string on failure.
            $error = $this->postService->createPost((int)$_SESSION['user_id'], $title, $content, $file);

            if ($error === null) {
                // Success! Redirect to home
                header("Location: /");
                exit;
            } else {
                // Pass error to TemplateService for display
                $this->templateService->addError($error);
            }
        }

        // 3. Render View (Errors are passed automatically)
        $this->templateService->render('post/create');
    }

    /**
     * Displays a single blog post and its associated comments.
     * * Retrieves the post ID from the query parameters.
     * * Fetches post details via PostService and comments via CommentService.
     * * Renders the detail view or handles "Not Found" errors.
     *
     * @return void
     */
    public function show(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 0) {
            header("Location: /");
            exit;
        }

        // Fetch Post
        $post = $this->postService->getPostById($id);
        
        if (!$post) {
            // 1. Set HTTP Status Code to 404
            http_response_code(404);
            
            // 2. Render the custom 404 template
            $this->templateService->render('404'); 
            return;
        }

        // Fetch Comments
        $comments = $this->commentService->getCommentsForPost($id);

        // Render View with data
        $this->templateService->render('post/show', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Handles the submission of a new comment.
     * * Verifies user authentication.
     * * Delegates the saving logic to CommentService.
     * * Redirects the user back to the specific post page.
     *
     * @return void
     */
    public function addComment(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $postId = (int)($_POST['post_id'] ?? 0);
            $content = $_POST['content'] ?? '';

            // Delegate to Service
            $this->commentService->addComment($postId, (int)$_SESSION['user_id'], $content);
            
            // Redirect back to the post
            header("Location: /post/show?id=" . $postId);
            exit;
        }
    }
}