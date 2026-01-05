<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Post;

/**
 * Controller responsible for the application's homepage.
 * * Serves as the default landing page logic.
 */
class HomeController
{
    /**
     * Renders the homepage view.
     * * Instantiates the Post model to retrieve all available blog entries.
     * * Passes the data to the 'home.php' template for display.
     * * Note: Access to $_SESSION is available in the view (initialized in index.php).
     *
     * @return void
     */
    public function index(): void
    {
        // Fetch all posts from the database
        $postModel = new Post();
        $posts = $postModel->findAll();
        
        // Render the view
        require_once __DIR__ . '/../../templates/home.php';
    }
}