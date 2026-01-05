<?php
declare(strict_types=1);

/**
 * Translates a given key into the currently selected language.
 *
 * This function utilizes a static cache mechanism to ensure the translation file
 * is loaded only once per request lifecycle, optimizing performance.
 *
 * @param string $key The unique identifier for the translation string (e.g., 'nav_home').
 * @return string The translated text, or the original key if no translation is found.
 */
function trans(string $key): string
{
    // 1. Determine current language (default: 'de')
    // Uses the session value set by the language switcher, defaults to German.
    $lang = $_SESSION['lang'] ?? 'de';

    // 2. Load translation file (Statically cached)
    // The 'static' keyword ensures this array persists between function calls
    // within the same request, preventing unnecessary file reads.
    static $translations = [];
    
    if (!isset($translations[$lang])) {
        $path = __DIR__ . "/Lang/$lang.php";
        
        if (file_exists($path)) {
            $translations[$lang] = require $path;
        } else {
            // Fallback: Empty array if language file is missing
            $translations[$lang] = []; 
        }
    }

    // 3. Return translated text (or the key as fallback)
    return $translations[$lang][$key] ?? $key;
}