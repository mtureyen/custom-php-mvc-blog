<?php
/**
 * Template: Login View
 *
 * Renders the user authentication form.
 *
 * Features:
 * - Centered "Card" layout for a modern look.
 * - Standard Username/Password input fields with focus states.
 * - Displays validation errors passed from the Controller.
 * - Includes a language switcher and navigation links (Register/Home).
 *
 * Data Dependencies:
 * @var array $errors Array of validation error messages passed from TemplateService.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trans('heading_login') ?></title>
    <style>
        :root {
            /* Color Palette (Consistent with Homepage) */
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

        /* General Layout: Center the card vertically and horizontally */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        /* Card Container */
        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            margin: 1rem;
        }

        /* Header Section */
        h1 {
            color: var(--primary);
            font-size: 2rem;
            margin: 0 0 1.5rem 0;
            text-align: center;
        }

        /* Form Layout */
        form { 
            display: flex; 
            flex-direction: column; 
            gap: 1.2rem; 
        }

        /* Input Fields */
        label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-main);
            margin-bottom: -0.5rem;
            display: block;
        }

        input { 
            padding: 0.8rem; 
            font-size: 1rem; 
            border: 2px solid var(--input-border);
            border-radius: 8px;
            transition: all 0.2s ease;
            outline: none;
            color: var(--text-main);
        }

        /* Focus State: Use Primary Color */
        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(105, 111, 199, 0.1);
        }
        
        /* Primary Button */
        button { 
            padding: 0.9rem; 
            background-color: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: bold;
            transition: background 0.2s ease, transform 0.1s ease;
            margin-top: 0.5rem;
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
        
        /* Links & Navigation */
        .links { 
            margin-top: 2rem; 
            text-align: center;
            font-size: 0.95rem;
            color: var(--text-muted);
            border-top: 1px solid var(--input-border);
            padding-top: 1.5rem;
        }

        .links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline;
        }
        
        /* Top Bar inside Card (Language Switcher) */
        .top-bar { 
            display: flex; 
            justify-content: flex-end; 
            margin-bottom: 1rem; 
        }
        
        .lang-switch a { 
            text-decoration: none; 
            color: var(--text-muted); 
            margin-left: 0.5rem; 
            font-size: 0.85rem; 
            font-weight: 600;
        }
        .lang-switch a.active { 
            color: var(--primary); 
            text-decoration: underline; 
        }
        .lang-switch span { color: #ccc; }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="top-bar">
            <div class="lang-switch">
                <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
                <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
                <span>|</span>
                <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            </div>
        </div>

        <h1><?= trans('heading_login') ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    <p class="error">
                        <?= htmlspecialchars($e) ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <label for="username"><?= trans('label_username') ?></label>
            <input type="text" id="username" name="username" required placeholder="User123">

            <label for="password"><?= trans('label_password') ?></label>
            <input type="password" id="password" name="password" required placeholder="••••••••">

            <button type="submit"><?= trans('btn_login') ?></button>
        </form>

        <div class="links">
            <p>
                <?= trans('text_no_account') ?> 
                <a href="/register"><?= trans('link_register_here') ?></a>
            </p>
            
            <p style="margin-top: 1rem;">
                <a href="/" style="color: var(--text-muted); font-weight: normal; font-size: 0.9rem;">
                    &larr; <?= trans('back_home') ?>
                </a>
            </p>
        </div>

    </div>

</body>
</html>