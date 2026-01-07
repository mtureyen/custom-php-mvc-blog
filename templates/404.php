<?php
/**
 * Template: 404 Error View
 *
 * Renders a user-friendly error page when a requested route or resource is not found.
 *
 * Features:
 * - Centered "Card" layout consistent with Login/Register pages.
 * - Displays a clear 404 status code and large icon.
 * - Provides a translated error message.
 * - Includes a styled "Back to Home" button using the primary color palette.
 *
 * Dependencies:
 * - Uses the global trans() helper for text content.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - <?= trans('page_not_found') ?></title>
    <style>
        :root {
            /* Color Palette */
            --primary: #696FC7;
            --primary-hover: #565BB5;
            --secondary: #A7AAE1;
            
            --bg-color: #F5F6FA;
            --card-bg: #FFFFFF;
            --text-main: #2D3748;
            --text-muted: #718096;
            --danger: #E53E3E;
        }

        /* General Layout: Center content */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }
        
        /* Card Container */
        .error-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 450px;
            padding: 3rem 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        /* Typography */
        h1 { 
            font-size: 5rem; 
            margin: 0; 
            color: var(--primary); 
            line-height: 1;
            font-weight: 800;
        }
        
        p { 
            font-size: 1.1rem; 
            color: var(--text-muted); 
            margin-bottom: 2rem; 
            margin-top: 1rem;
        }
        
        /* Button Styles */
        .btn { 
            padding: 0.8rem 1.5rem; 
            background-color: var(--primary); 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            font-size: 1rem; 
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(105, 111, 199, 0.25);
        }
        
        .btn:hover { 
            background-color: var(--primary-hover); 
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(105, 111, 199, 0.3);
        }

        /* Icon Styling */
        .icon { 
            font-size: 4rem; 
            margin-bottom: 0.5rem; 
            display: block; 
            animation: float 3s ease-in-out infinite;
        }

        /* Simple animation for the icon */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <div class="error-card">
        <span class="icon">😕</span>
        
        <h1>404</h1>
        
        <p>
            <?= trans('msg_page_not_found') ?>
        </p>

        <a href="/" class="btn">
            &larr; <?= trans('back_home') ?>
        </a>
    </div>

</body>
</html>