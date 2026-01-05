<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\User;

/**
 * Controller handling user authentication logic.
 * * Manages the registration, login, and logout processes, including
 * input validation, password hashing, and session management.
 */
class AuthController
{
    /**
     * Handles the user login process.
     * * Displays the login form (GET) or processes the login credentials (POST).
     * * Verifies the password using safe hashing algorithms and initializes the user session.
     *
     * @return void
     */
    public function login(): void
    {
        $error = null;
        
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST["password"];
            
            $userModel = new User();
            $user = $userModel->findByUsername($username);

            // Check: Does the user exist? AND Is the password correct?
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: /"); 
                exit;
            } else {
                $error = trans('err_login_failed');
            }
        }
        
        require_once __DIR__ . '/../../templates/login.php';
    }

    /**
     * Handles new user registration.
     * * Displays the registration form (GET) or processes new user data (POST).
     * * Performs strict validation on username (format/length) and password (length/match).
     * * Hashes the password before storing it in the database.
     *
     * @return void
     */
    public function register(): void
    {
        $error = null;

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            // trim() removes whitespace from start/end
            $username = trim($_POST["username"] ?? '');
            $password = $_POST["password"] ?? '';
            $passwordRepeat = $_POST["password_repeat"] ?? '';

            // 1. Validation: Allowed characters (Only a-z, 0-9 and underscores)
            if (!preg_match('#^[a-zA-Z0-9_]+$#', $username)) {
                $error = trans('err_username_chars');
            }
            // 2. Validation: Username length (min 3, max 18)
            elseif (strlen($username) < 3 || strlen($username) > 18) {
                $error = trans('err_username_length');
            }
            // 3. Validation: Password length (Minimum 8 characters)
            elseif (strlen($password) < 8) {
                $error = trans('err_pw_too_short');
            }
            // 4. Validation: Password confirmation match
            elseif ($password !== $passwordRepeat) {
                $error = trans('err_pw_mismatch');
            }
            else {
                // Validation passed -> Hash password and save
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $userModel = new User();

                $success = $userModel->create($username, $hashedPassword);

                if ($success) {
                    header("Location: /login");
                    exit;
                }
                else {
                    $error = trans('err_user_taken');
                }
            }
        }
        require_once __DIR__ . '/../../templates/register.php';
    }

    /**
     * Terminates the user session.
     * * Completely clears the session data and invalidates the session cookie
     * to ensure a secure logout.
     *
     * @return void
     */
    public function logout(): void
    {
        $_SESSION = [];

        // If cookies are used to propagate the session ID, delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        header("Location: /");
        exit;
    }
}