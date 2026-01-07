<?php
/**
 * Template: Create Post
 *
 * Renders the HTML form for creating a new blog entry.
 *
 * Features:
 * - Card-based layout with modern styling using the project's color palette.
 * - Input fields for title and text content.
 * - File input for optional image uploads.
 * - Displays validation errors passed from the Controller/Service layer.
 *
 * Note: The form explicitly uses 'enctype="multipart/form-data"' to support file transfers.
 *
 * @var array $errors Array of validation error messages (strings) passed via TemplateService.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trans('create_heading') ?></title>
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
            --input-border: #E2E8F0;
        }

        /* General Layout */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 2rem 1rem; /* Added padding for smaller screens */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top with margin, better for tall forms */
            min-height: 100vh;
        }

        /* Card Container */
        .create-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 700px; /* Wider than login for better writing experience */
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            margin-top: 2rem;
        }

        /* Top Navigation Bar inside Card */
        .top-bar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 2rem; 
            border-bottom: 1px solid var(--input-border);
            padding-bottom: 1rem;
        }

        .back-link { 
            text-decoration: none; 
            color: var(--text-muted); 
            font-weight: 600; 
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary); }

        /* Language Switcher */
        .lang-switch a { 
            text-decoration: none; 
            color: var(--text-muted); 
            margin-left: 0.5rem; 
            font-size: 0.9rem; 
            font-weight: 600; 
        }
        .lang-switch a.active { 
            color: var(--primary); 
            text-decoration: underline; 
        }
        .lang-switch span { color: #ccc; }

        /* Headlines */
        h1 {
            color: var(--primary);
            font-size: 2rem;
            margin: 0 0 1.5rem 0;
        }

        /* Form Styling */
        form { display: flex; flex-direction: column; gap: 1.5rem; }

        label {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-main);
            margin-bottom: -1rem; /* Pull label closer to input */
            display: block;
        }

        input[type="text"], 
        textarea { 
            padding: 0.8rem; 
            font-size: 1rem; 
            border: 2px solid var(--input-border);
            border-radius: 8px;
            transition: all 0.2s ease;
            outline: none;
            color: var(--text-main);
            font-family: inherit;
            width: 100%;
            box-sizing: border-box;
        }

        textarea {
            min-height: 250px;
            resize: vertical;
            line-height: 1.6;
        }

        /* Focus State */
        input[type="text"]:focus,
        textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(105, 111, 199, 0.1);
        }

        /* File Input Styling */
        input[type="file"] {
            padding: 0.5rem;
            background: var(--bg-color);
            border-radius: 8px;
            border: 1px dashed var(--secondary);
            width: 100%;
            box-sizing: border-box;
            cursor: pointer;
        }

        /* Submit Button */
        button { 
            padding: 1rem; 
            background-color: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1.1rem; 
            font-weight: bold;
            transition: background 0.2s ease, transform 0.1s ease;
            margin-top: 1rem;
        }
        button:hover { 
            background-color: var(--primary-hover); 
            transform: translateY(-1px);
        }

        /* Error Box */
        .error-box {
            background-color: #FFF5F5;
            border-left: 4px solid var(--danger);
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        .error { 
            color: var(--danger); 
            font-weight: 600; 
            margin: 0;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="create-card">
        
        <div class="top-bar">
            <a href="/" class="back-link">← <?= trans('back_home') ?></a>

            <div class="lang-switch">
                <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
                <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
                <span>|</span>
                <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            </div>
        </div>

        <h1><?= trans('create_heading') ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    <p class="error">
                        <?= htmlspecialchars($e) ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/post/create" method="POST" enctype="multipart/form-data">
            
            <div>
                <label for="title"><?= trans('label_title') ?></label>
                <br>
                <input type="text" id="title" name="title" required placeholder="<?= trans('ph_title') ?>">
            </div>

            <div>
                <label for="content"><?= trans('label_content') ?></label>
                <br>
                <textarea id="content" name="content" required placeholder="<?= trans('ph_content') ?>"></textarea>
            </div>

            <div>
                <label for="image"><?= trans('label_image') ?></label>
                <br>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit"><?= trans('btn_publish') ?></button>
        </form>

    </div>

</body>
</html>