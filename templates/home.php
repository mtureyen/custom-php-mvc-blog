<?php
/**
 * Homepage View Template.
 * * Renders the main landing page showing the list of latest blog posts.
 * * Variables available in this view:
 * @var array $posts Array of associative arrays, each representing a blog post (passed from HomeController).
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <title>Mein Blog</title>
    <style>
        /* CSS for the layout */
        body { font-family: sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        
        nav { display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid #ddd; }
        
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .btn-danger { color: red; }
        
        /* Grid for posts */
        .post-list { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 2rem; 
            margin-top: 2rem; 
        }

        .post-card { 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            overflow: hidden; 
            padding: 1rem; 
            display: flex;
            flex-direction: column;
            height: 100%; 
        }

        .post-image { width: 100%; height: 200px; object-fit: cover; background-color: #f0f0f0; display: block; margin-bottom: 1rem; border-radius: 4px; }
        
        .meta { color: gray; font-size: 0.9rem; margin-bottom: 0.5rem; }
        
        .preview-text { 
            color: #333; 
            line-height: 1.5; 
            flex-grow: 1; 
            margin-bottom: 1rem;
            overflow-wrap: break-word; 
            word-break: break-word;
        }

        /* Language Switcher Styles */
        .lang-switch a { text-decoration: none; color: #333; }
        .lang-switch a.active { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <nav>
        <div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?= trans('welcome') ?>, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!
                <a href="/logout" class="btn btn-danger"><?= trans('nav_logout') ?></a>
            <?php else: ?>
                <a href="/login"><?= trans('nav_login') ?></a> | <a href="/register"><?= trans('nav_register') ?></a>
            <?php endif; ?>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1rem;">
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/post/create" class="btn btn-primary"><?= trans('nav_create') ?></a>
            <?php endif; ?>

            <div class="lang-switch">
                <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
                <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
                |
                <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
            </div>

        </div>
    </nav>

    <h1><?= trans('latest_posts') ?></h1>

    <?php if (empty($posts)): ?>
        <p><?= trans('no_posts') ?></p>
    <?php else: ?>
        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                
                <article class="post-card">
                    <?php if (!empty($post['image_url'])): ?>
                        <div class="post-image-container">
                            <img src="/<?= htmlspecialchars($post['image_url']) ?>" alt="<?= trans('alt_image') ?>" class="post-image">
                        </div>
                    <?php endif; ?>

                    <div class="meta">
                        <?= trans('from') ?> <strong><?= htmlspecialchars($post['username']) ?></strong> 
                        <?= trans('at') ?> <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                    </div>

                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    
                    <p class="preview-text">
                        <?php 
                            // 1. Get raw content
                            $rawContent = $post['content'];

                            // 2. Check length (multibyte safe)
                            if (mb_strlen($rawContent) > 200) {
                                // 3. Cut content
                                $cutContent = mb_substr($rawContent, 0, 200);
                                // 4. Escape and append dots
                                echo htmlspecialchars($cutContent) . '...';
                            } else {
                                // Output full content
                                echo htmlspecialchars($rawContent);
                            }
                        ?>
                    </p>

                    <div> 
                        <a href="/post/show?id=<?= $post['id'] ?>" class="btn" style="color: #007bff; font-weight: bold; padding-left: 0;">
                            <?= trans('read_more') ?>
                        </a>
                    </div>

                </article>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</body>
</html>