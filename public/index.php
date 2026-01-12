<?php
declare(strict_types=1);

/**
 * Application Entry Point (Front Controller).
 *
 * This script acts as the "Composition Root" of the application.
 * It strictly implements Dependency Injection (IoC) to ensure loose coupling.
 *
 * Execution Flow:
 * 1. Bootstrap (Autoloading & Helpers).
 * 2. Database (Create single connection).
 * 3. Wiring (Inject DB -> Models -> Services).
 * 4. Dispatching (Inject Services -> Controllers).
 */

session_start();

// Import Controllers
use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\PostController;

// Import Services
use App\Service\Database;
use App\Service\UserService;
use App\Service\PostService;
use App\Service\CommentService;
use App\Service\TemplateService;

// 1. Load Composer autoloader (automatically loads classes in src/ and vendor/)
require_once __DIR__ . '/../vendor/autoload.php';

// Load global helper functions (e.g., translation service)
require_once __DIR__ . '/../src/helpers.php';

// 2. Initalize Database
// We establish the database connection ONCE. This instance is then passed
// down the chain, ensuring efficiency and a single source of truth.
$database = new Database();
$pdo = $database->getConnection();


// 3. Data access layer (Models)
// Models are instantiated here and receive the active PDO connection.
// They are responsible for raw SQL operations.
$userModel    = new \App\Model\User($pdo);
$postModel    = new \App\Model\Post($pdo);
$commentModel = new \App\Model\Comment($pdo);

// 4. Business logic layer (Services)
// Pure Dependency Injection:
// Services receive their specific Models via constructor injection.
// They contain the business logic and remain independent of the database driver.
$templateService = new TemplateService();
$userService     = new UserService($userModel);
$postService     = new PostService($postModel);
$commentService  = new CommentService($commentModel);

// 5. Parse the Request URI
// Extract the path (e.g., "/login") while ignoring query parameters (e.g., "?id=1")
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 6. Routing Logic (Dispatcher)
// Match the URI to a controller action and inject required services.
switch ($requestUri) {
    // --- Homepage ---
    case '/':
    case '/index.php':
        $controller = new HomeController($postService, $templateService);
        $controller->index();
        break;

    // --- Authentication ---
    case '/login':
        $controller = new AuthController($userService, $templateService);
        $controller->login();
        break;

    case '/register':
        $controller = new AuthController($userService, $templateService);
        $controller->register();
        break;

    case '/logout':
        $controller = new AuthController($userService, $templateService);
        $controller->logout();
        break;

    // --- Blog Post Operations ---
    case '/post/create':
        $controller = new PostController($postService, $commentService, $templateService);
        $controller->create();
        break;

    case '/post/show':
        $controller = new PostController($postService, $commentService, $templateService);
        $controller->show();
        break;

    case '/comment/add':
        $controller = new PostController($postService, $commentService, $templateService);
        $controller->addComment();
        break;

    // --- Utility: Language Switcher ---
    case '/language':
        // Retrieve language code from URL query (e.g., ?code=en)
        $code = $_GET['code'] ?? 'de';
        
        // Security: Validate against allowed languages to prevent session injection
        if (in_array($code, ['de', 'en'])) {
            $_SESSION['lang'] = $code;
        }
        
        // Redirect user back to the page they came from (or home if unknown)
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
        
    // --- 404 Not Found ---
    default:
        // Set HTTP response code and render 404 view via TemplateService
        http_response_code(404);
        // Loading 404.php template
        $templateService->render('404');
        break;
}