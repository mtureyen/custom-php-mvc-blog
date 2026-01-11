<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\UserService;
use App\Service\TemplateService;

/**
 * AuthController
 *
 * Handles all user authentication processes including login, registration,
 * and logout. It acts as the coordinator between the HTTP request,
 * the UserService (business logic), and the TemplateService (view rendering).
 */
class AuthController
{
    /**
     * @var UserService Handles user-related business logic (auth, creation).
     */
    private UserService $userService;

    /**
     * @var TemplateService Handles view rendering and error management.
     */
    private TemplateService $templateService;

    /**
     * Initializes the controller with its required dependencies.
     *
     * @param UserService $userService Service for user authentication and management.
     * @param TemplateService $templateService Service for rendering views.
     */
    public function __construct(UserService $userService, TemplateService $templateService)
    {
        $this->userService = $userService;
        $this->templateService = $templateService;
    }

    /**
     * Handles the user login process.
     * * Checks for a POST request with credentials. If valid, initializes the 
     * user session. If invalid, adds an error message to the template.
     * * @return void
     */
    public function login(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? '';
            $password = $_POST["password"] ?? '';

            // Ask Service: Is these credentials valid?
            $user = $this->userService->authenticate($username, $password);

            if ($user) {
                // Login successful -> Populate session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: /"); 
                exit;
            } else {
                // Display error via TemplateService
                // (Uses the trans() function or translation key)
                $this->templateService->addError(trans('err_login_failed'));
            }
        }
        
        // Render the login view
        $this->templateService->render('login');
    }

    /**
     * Handles the user registration process.
     * * Processes the registration form. Delegates validation and creation logic
     * to the UserService. Redirects to login on success or shows errors on failure.
     * * @return void
     */
    public function register(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"] ?? '';
            $password = $_POST["password"] ?? '';
            $repeat   = $_POST["password_repeat"] ?? '';

            // Call Service: Attempt to register the user
            // Returns NULL on success, or an error key (e.g., 'err_pw_mismatch')
            $errorKey = $this->userService->registerUser($username, $password, $repeat);

            if ($errorKey === null) {
                // Success!
                header("Location: /login");
                exit;
            } else {
                // Translate error key and add to template
                $this->templateService->addError(trans($errorKey));
            }
        }

        // Render the registration view
        $this->templateService->render('register');
    }

    /**
     * Handles the user logout process.
     * * Clears the session array, deletes the session cookie, and destroys 
     * the session on the server. Redirects the user to the homepage.
     * * @return void
     */
    public function logout(): void
    {
        // Clear session data
        $_SESSION = [];

        // Delete the session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();
        
        // Redirect to homepage
        header("Location: /");
        exit;
    }
}