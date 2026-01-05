<?php
/**
 * Single Post View Template.
 * * Displays the detailed content of a specific blog post.
 * * Renders the comment section and the form for submitting new comments.
 * * Variables available in this view:
 * @var array $post Associative array containing post details (title, content, author, etc.).
 * @var array $comments Array of associative arrays representing the comments for this post.
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        
        /* Top Bar Styles */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .back-link { text-decoration: none; color: #007bff; }
        .back-link:hover { text-decoration: underline; }
        .lang-switch a { text-decoration: none; color: #333; margin-left: 0.5rem; font-size: 0.9rem; }
        .lang-switch a.active { font-weight: bold; text-decoration: underline; color: #000; }

        .meta { color: gray; margin-bottom: 1rem; }
        
        /* Image styling */
        .post-image-large { width: 100%; max-height: 500px; object-fit: cover; border-radius: 8px; margin-bottom: 2rem; }
        
        .content { 
            line-height: 1.8; 
            font-size: 1.1rem;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="/" class="back-link">← <?= trans('back_overview') ?></a>

        <div class="lang-switch">
            <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
            <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
            |
            <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
        </div>
    </div>

    <h1><?= htmlspecialchars($post['title']) ?></h1>

    <div class="meta">
        <?= trans('written_by') ?> <strong><?= htmlspecialchars($post['username']) ?></strong> 
        <?= trans('at') ?> <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
    </div>

    <?php if (!empty($post['image_url'])): ?>
        <img src="/<?= htmlspecialchars($post['image_url']) ?>" class="post-image-large">
    <?php endif; ?>

    <div class="content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <hr style="margin-top: 3rem;">
    <h3><?= trans('comments_headline') ?></h3>

    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="/comment/add" method="POST" style="margin-bottom: 2rem; background: #f9f9f9; padding: 1rem; border-radius: 5px;">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            
            <label style="display:block; margin-bottom: 0.5rem;"><?= trans('label_your_comment') ?></label>
            <textarea name="content" required style="width: 100%; height: 80px; margin-bottom: 0.5rem;"></textarea>
            
            <button type="submit" style="background: #28a745; color: white; border: none; padding: 0.5rem 1rem; cursor: pointer;">
                <?= trans('btn_submit_comment') ?>
            </button>
        </form>
    <?php else: ?>
        <p style="background: #fff3cd; padding: 10px; border: 1px solid #ffeeba;">
            <?= trans('msg_login_to_comment') ?>
        </p>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <p><?= trans('msg_no_comments') ?></p>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div style="border-bottom: 1px solid #eee; padding: 10px 0;">
                <strong><?= htmlspecialchars($comment['username']) ?></strong> 
                <span style="color: gray; font-size: 0.8rem;">
                    <?= trans('at') ?> <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                </span>
                <p style="margin: 5px 0;"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>