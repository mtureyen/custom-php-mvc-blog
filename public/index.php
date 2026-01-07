<?php
declare(strict_types=1);

/**
 * Application Entry Point (Front Controller).
 * * This script handles all incoming HTTP requests. It is responsible for:
 * 1. Bootstrapping the application (Starting sessions, loading dependencies).
 * 2. Parsing the incoming request URL.
 * 3. Routing the request to the appropriate Controller and Method.
 */

session_start();

use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\PostController;

// 1. Load Composer autoloader (automatically loads classes in src/ and vendor/)
require_once __DIR__ . '/../vendor/autoload.php';

// Load global helper functions (e.g., translation service)
require_once __DIR__ . '/../src/helpers.php';

// 2. Parse the Request URI
// Extract the path (e.g., "/login") while ignoring query parameters (e.g., "?id=1")
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 3. Routing Logic (Dispatcher)
// Routes the cleaned URI to the corresponding controller action.
switch ($requestUri) {
    // --- Homepage ---
    case '/':
    case '/index.php':
        $controller = new HomeController();
        $controller->index();
        break;

    // --- Authentication ---
    case '/login':
        $controller = new AuthController();
        $controller->login();
        break;

    case '/register':
        $controller = new AuthController();
        $controller->register();
        break;

    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // --- Blog Post Operations ---
    case '/post/create':
        $controller = new PostController();
        $controller->create();
        break;

    case '/post/show':
        $controller = new PostController();
        $controller->show();
        break;

    case '/comment/add':
        $controller = new PostController();
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
        // Set HTTP response code and show error message
        http_response_code(404);
        // Loading 404.php template
        require __DIR__ . '/../templates/404.php';
        break;
}