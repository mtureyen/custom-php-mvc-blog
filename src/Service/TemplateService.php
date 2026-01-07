<?php
declare(strict_types=1);

namespace App\Service;

/**
 * TemplateService
 *
 * Responsible for rendering HTML views and managing UI feedback.
 * It acts as a bridge between the Controller logic and the Presentation layer (HTML),
 * handling variable extraction and error message passing.
 */
class TemplateService
{
    /**
     * @var string The absolute path to the directory containing template files.
     */
    private string $templatePath;

    /**
     * @var array A collection of error messages to be displayed in the view.
     */
    private array $errors = [];

    /**
     * Initializes the service.
     * * Sets the template directory path relative to the service location.
     */
    public function __construct()
    {
        // Define the base path for templates relative to this file
        $this->templatePath = __DIR__ . '/../../templates/';
    }

    /**
     * Renders a specific template file.
     * * Constructs the full path to the view file.
     * * Extracts the provided data array into local variables for the template.
     * * Injects the collected errors into the view scope.
     * * Includes the file to output the HTML.
     *
     * @param string $viewName The name of the template file (without '.php').
     * @param array $data Associative array of data to pass to the view (key => variable).
     * @return void
     */
    public function render(string $viewName, array $data = []): void
    {
        $file = $this->templatePath . $viewName . '.php';

        if (!file_exists($file)) {
            // Stops execution if the view file is missing
            die("Template not found: " . $file);
        }

        // Extract array keys into local variables (e.g., ['post' => $p] becomes $post)
        extract($data);

        // Pass the collected errors array to the view scope
        $errors = $this->errors;

        require_once $file;
    }

    /**
     * Registers an error message for display.
     * * The message will be available as part of the $errors array
     * when render() is called.
     *
     * @param string $message The error message (or translation key) to display.
     * @return void
     */
    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }
}