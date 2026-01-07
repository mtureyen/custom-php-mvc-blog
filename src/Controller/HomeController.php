<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\PostService;
use App\Service\TemplateService;

/**
 * HomeController
 *
 * Manages the display of the application's landing page.
 * It serves as an orchestrator between the PostService (for data retrieval)
 * and the TemplateService (for rendering the view).
 */
class HomeController
{
    /**
     * @var PostService Handles business logic related to blog posts.
     */
    private PostService $postService;

    /**
     * @var TemplateService Handles the rendering of HTML views.
     */
    private TemplateService $templateService;

    /**
     * Initializes the controller and its dependencies.
     * * Instantiates the services required for the homepage logic.
     */
    public function __construct()
    {
        // Initialize required services
        $this->postService = new PostService();
        $this->templateService = new TemplateService();
    }

    /**
     * Renders the default homepage.
     * * Retrieves the list of all blog posts from the PostService.
     * * Passes the post data to the 'home' template for display.
     *
     * @return void
     */
    public function index(): void
    {
        // 1. Fetch data
        // Calls the service method which internally handles the Model query
        $posts = $this->postService->getAllPosts();
        
        // 2. Render view
        // Passes the $posts array to the 'home.php' template
        $this->templateService->render('home', [
            'posts' => $posts,
            'pageTitle' => trans('blog_title') 
        ]);
    }
}