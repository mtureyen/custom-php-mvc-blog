<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\User;

/**
 * UserService
 *
 * Manages user-related business logic, including authentication
 * and registration. It handles input validation, password hashing,
 * and interaction with the User model.
 */
class UserService
{
    /**
     * @var User The injected User model (Data Access Object).
     */
    private User $userModel;

    /**
     * Constructor Injection.
     *
     * The service receives the fully instantiated User Model.
     * This promotes a clean separation of concerns:
     * - The Service handles "How to hash passwords" and "When to allow login".
     * - The Model handles "How to select/insert users in the database".
     *
     * @param User $userModel The data access instance for users.
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * Authenticates a user based on username and password.
     * * Retrieves the user record by username.
     * * Verifies the provided password against the stored hash.
     *
     * @param string $username The username to check.
     * @param string $password The plain text password.
     * @return array|null Returns the user data array if valid, or null if invalid.
     */
    public function authenticate(string $username, string $password): ?array
    {
        // Login logic: Retrieve user and verify password
        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }

    /**
     * Registers a new user in the system.
     * * Validates the username (regex, length) and password matching.
     * * Checks if the username is already taken.
     * * Hashes the password securely.
     * * Creates the user record in the database.
     *
     * @param string $username The desired username.
     * @param string $password The password.
     * @param string $repeat The repeated password for confirmation.
     * @return string|null Returns null on success, or a translation key string on error.
     */
    public function registerUser(string $username, string $password, string $repeat): ?string
    {
        $username = trim($username);

        // Validation using translation keys
        if (!preg_match('#^[a-zA-Z0-9_]+$#', $username)) {
            return trans('err_username_chars');
        }
        if (strlen($username) < 3 || strlen($username) > 18) {
            return trans('err_username_length');
        }
        if (strlen($password) < 8) {
            return trans('err_pw_too_short');
        }
        if ($password !== $repeat) {
            return trans('err_pw_mismatch');
        }

        // Check if username is already taken
        if ($this->userModel->findByUsername($username)) {
            return trans('err_user_taken');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Save to database
        $success = $this->userModel->create($username, $hashedPassword);

        // Return translated DB error on failure
        return $success ? null : trans('err_db');
    }
}