<?php
/**
 * Registration View Template.
 * * Displays the user registration form allowing new users to create an account.
 * * Variables available in this view:
 * @var string|null $error (Optional) Validation error message passed from AuthController.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trans('heading_register') ?></title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; padding: 0 1rem; }
        form { display: flex; flex-direction: column; gap: 1rem; }
        input { padding: 0.5rem; font-size: 1rem; }
        button { padding: 0.7rem; background: #007bff; color: white; border: none; cursor: pointer; font-size: 1rem; }
        button:hover { background: #0056b3; }
        .error { color: red; }
        .links { margin-top: 1.5rem; font-size: 0.9rem; }

        /* Styles for the language switcher */
        .top-bar { display: flex; justify-content: flex-end; margin-bottom: 1rem; }
        .lang-switch a { text-decoration: none; color: #333; margin-left: 0.5rem; font-size: 0.9rem; }
        .lang-switch a.active { font-weight: bold; text-decoration: underline; color: #000; }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="lang-switch">
            <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
            <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
            |
            <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
        </div>
    </div>

    <h1><?= trans('heading_register') ?></h1>

    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: bold;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form action="/register" method="POST">
        <label for="username"><?= trans('label_username') ?></label>
        <input type="text" id="username" name="username" required>

        <label for="password"><?= trans('label_password') ?></label>
        <input type="password" id="password" name="password" required>

        <label for="password_repeat"><?= trans('label_password_repeat') ?></label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <button type="submit"><?= trans('btn_register') ?></button>
    </form>

    <div class="links">
        <p>
            <?= trans('text_have_account') ?> 
            <a href="/login"><?= trans('link_login_here') ?></a>
        </p>
        
        <p>
            <a href="/" style="color: #666; text-decoration: none;">
                &larr; <?= trans('link_cancel_home') ?>
            </a>
        </p>
    </div>

</body>
</html>