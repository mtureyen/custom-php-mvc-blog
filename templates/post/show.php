<?php
/**
 * Template: Single Post Detail View
 *
 * Renders the complete view of a blog post including:
 * - Title, metadata (author, date), and the main content.
 * - The FULL feature image (no cropping).
 * - The comment section with a submission form.
 *
 * Data Dependencies:
 * @var array $post Associative array containing post details.
 * @var array $comments Array of comment arrays associated with this post.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
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
            --input-border: #E2E8F0;
        }

        /* General Layout */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
        }

        /* Main Container */
        .container {
            width: 100%;
            max-width: 800px;
        }

        /* Top Navigation Bar */
        .top-bar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 2rem; 
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

        /* Post Card Design */
        .post-card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            padding: 0; 
            overflow: hidden;
            margin-bottom: 3rem;
        }

        /* Feature Image Styling - FULL VIEW */
        .post-image-large { 
            width: 100%; 
            height: auto; /* Maintains original aspect ratio */
            display: block;
            border-bottom: 1px solid var(--input-border);
        }

        /* Inner Content Padding */
        .post-content-wrapper {
            padding: 2.5rem;
        }

        /* Typography */
        h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin: 0 0 0.5rem 0;
            line-height: 1.2;
        }

        .meta { 
            color: var(--text-muted); 
            font-size: 0.95rem; 
            margin-bottom: 2rem; 
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--input-border);
        }
        .meta strong { color: var(--primary); }

        .content { 
            line-height: 1.8; 
            font-size: 1.15rem;
            color: var(--text-main);
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }

        /* Comments Section Styling */
        .comments-section {
            margin-top: 3rem;
        }

        h3 {
            color: var(--text-main);
            border-left: 4px solid var(--primary);
            padding-left: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Comment Form */
        .comment-form {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            margin-bottom: 2rem;
            border: 1px solid var(--input-border);
        }

        label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        textarea {
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            border: 2px solid var(--input-border);
            border-radius: 8px;
            box-sizing: border-box;
            transition: all 0.2s ease;
            outline: none;
            font-family: inherit;
            margin-bottom: 1rem;
        }

        textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(105, 111, 199, 0.1);
        }

        button { 
            padding: 0.7rem 1.5rem; 
            background-color: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem; 
            font-weight: bold;
            transition: all 0.2s ease;
        }
        button:hover { 
            background-color: var(--primary-hover); 
            transform: translateY(-1px);
        }

        /* Comment List */
        .comment-item {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid #f0f0f0;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .comment-author { color: var(--primary); font-weight: bold; }
        .comment-date { color: var(--text-muted); }

        /* Login Hint */
        .login-hint {
            background-color: #FFF9C4;
            color: #856404;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #FFEEBA;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="top-bar">
            <a href="/" class="back-link">← <?= trans('back_overview') ?></a>

            <div class="lang-switch">
                <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
                <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
                <span>|</span>
                <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            </div>
        </div>

        <article class="post-card">
            
            <?php if (!empty($post['image_url'])): ?>
                <img src="/<?= htmlspecialchars($post['image_url']) ?>" class="post-image-large" alt="<?= trans('alt_image') ?>">
            <?php endif; ?>

            <div class="post-content-wrapper">
                <h1><?= htmlspecialchars($post['title']) ?></h1>

                <div class="meta">
                    <?= trans('written_by') ?> <strong><?= htmlspecialchars($post['username']) ?></strong> 
                    <span style="margin: 0 5px;">•</span>
                    <?= trans('at') ?> <?= htmlspecialchars($post['display_date']) ?>
                </div>

                <div class="content">
                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                </div>
            </div>

        </article>

        <div class="comments-section">
            <h3><?= trans('comments_headline') ?></h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <form action="/comment/add" method="POST">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        
                        <label><?= trans('label_your_comment') ?></label>

                        <textarea name="content" required placeholder="<?= trans('ph_comment') ?>" rows="3"></textarea>

                        <div style="text-align: right;">
                            <button type="submit">
                                <?= trans('btn_submit_comment') ?>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <p class="login-hint">
                    <?= trans('msg_login_to_comment') ?>
                </p>
            <?php endif; ?>

            <?php if (empty($comments)): ?>
                <p style="color: var(--text-muted); font-style: italic;"><?= trans('msg_no_comments') ?></p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($comment['username']) ?></span>
                            <span class="comment-date"><?= htmlspecialchars($comment['display_date']) ?></span>
                        </div>
                        <p style="margin: 0; line-height: 1.5;">
                            <?= nl2br(htmlspecialchars($comment['content'])) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>