<?php
/**
 * Create Post View Template.
 * * Renders the form for creating new blog entries.
 * * Contains input fields for title, content, and an optional image upload.
 * * IMPORTANT: The form uses 'enctype="multipart/form-data"' to allow file transfers.
 *
 * * Variables available in this view:
 * @var string|null $error (Optional) Validation error message passed from PostController (e.g., file too large).
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= trans('create_heading') ?></title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        form { display: flex; flex-direction: column; gap: 1rem; }
        input, textarea { padding: 0.5rem; font-size: 1rem; }
        textarea { height: 200px; }
        button { padding: 0.7rem; background: #007bff; color: white; border: none; cursor: pointer; font-size: 1rem;}
        button:hover { background: #0056b3; }
        
        /* Styles for the top bar (Back link left, Language right) */
        .top-bar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 1rem; 
        }

        .back-link { text-decoration: none; color: #007bff; }
        .back-link:hover { text-decoration: underline; }

        /* Styles for the language switcher */
        .lang-switch a { text-decoration: none; color: #333; margin-left: 0.5rem; font-size: 0.9rem; }
        .lang-switch a.active { font-weight: bold; text-decoration: underline; color: #000; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="/" class="back-link">← <?= trans('back_home') ?></a>

        <div class="lang-switch">
            <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
            <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
            |
            <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
        </div>
    </div>

    <h1><?= trans('create_heading') ?></h1>

    <?php if (isset($error)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="/post/create" method="POST" enctype="multipart/form-data">
        
        <label for="title"><?= trans('label_title') ?></label>
        <input type="text" id="title" name="title" required placeholder="<?= trans('ph_title') ?>">

        <label for="content"><?= trans('label_content') ?></label>
        <textarea id="content" name="content" required placeholder="<?= trans('ph_content') ?>"></textarea>

        <label for="image"><?= trans('label_image') ?></label>
        <input type="file" id="image" name="image" accept="image/*">

        <button type="submit"><?= trans('btn_publish') ?></button>
    </form>

</body>
</html>