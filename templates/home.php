<?php
/**
 * Template: Homepage View
 *
 * Renders the main landing page of the application.
 *
 * Features:
 * - Top Navigation: Handles Login/Logout/Register logic based on session state.
 * - Language Switcher: Allows toggling between German and English.
 * - Post Grid: Displays the list of latest blog posts fetched by the Controller.
 *
 * Data Dependencies:
 * @var array $posts Array of associative arrays representing blog posts (prepared by PostService).
 */
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'de' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trans('blog_title') ?></title>
    <style>
        :root {
            /* Your required color palette */
            --primary: #696FC7;
            --primary-hover: #565BB5; /* Slightly darker for hover states */
            --secondary: #A7AAE1;
            
            /* Improvised neutral colors for layout structure */
            --bg-color: #F5F6FA;      /* Very light gray-blue background */
            --card-bg: #FFFFFF;       /* Pure white for cards */
            --text-main: #2D3748;     /* Dark gray for readability */
            --text-muted: #718096;    /* Light gray for metadata */
            --danger: #E53E3E;        /* Red for danger actions */
        }

        /* General Layout Styles */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Container helper for centering content */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Navigation Styles */
        nav { 
            background: var(--card-bg);
            /* Soft shadow using your primary color tint */
            box-shadow: 0 2px 10px rgba(105, 111, 199, 0.1); 
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content {
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        /* Button Styles */
        .btn { 
            padding: 0.6rem 1.2rem; 
            text-decoration: none; 
            border-radius: 8px; 
            display: inline-block; 
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-primary { 
            background-color: var(--primary); 
            color: white; 
            box-shadow: 0 4px 6px rgba(105, 111, 199, 0.25);
        }
        
        .btn-primary:hover { 
            background-color: var(--primary-hover); 
            transform: translateY(-1px);
        }

        .btn-danger { 
            color: var(--danger); 
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
        }
        
        .btn-danger:hover {
            background-color: #FFF5F5;
            border-color: #FED7D7;
        }

        .nav-links a {
            color: var(--text-main);
            text-decoration: none;
            margin-right: 15px;
            font-weight: 500;
        }
        .nav-links a:hover {
            color: var(--primary);
        }

        /* Hero / Heading Section */
        .page-header {
            text-align: center;
            margin: 3rem 0 2rem;
        }

        h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        /* Post Grid Layout */
        .post-list { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 2.5rem; 
            margin-bottom: 4rem;
        }

        /* Card Design */
        .post-card { 
            background: var(--card-bg);
            border-radius: 16px; 
            overflow: hidden; 
            display: flex;
            flex-direction: column;
            height: 100%; 
            /* Modern shadow instead of solid borders */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid transparent;
        }

        /* Hover Effect: Lift the card up */
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(105, 111, 199, 0.15), 0 10px 10px -5px rgba(105, 111, 199, 0.1);
            border-color: var(--secondary); /* Subtle border highlight on hover */
        }

        /* Feature Image Styling */
        .post-image-container {
            width: 100%;
            height: 220px;
            overflow: hidden;
        }

        .post-image { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            background-color: var(--secondary); /* Fallback color */
            transition: transform 0.5s ease;
        }

        .post-card:hover .post-image {
            transform: scale(1.05); /* Zoom effect on hover */
        }
        
        /* Card Content */
        .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .meta { 
            color: var(--secondary); 
            font-weight: 600;
            font-size: 0.85rem; 
            margin-bottom: 0.5rem; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta strong {
            color: var(--primary);
        }

        h2 {
            margin: 0.5rem 0;
            font-size: 1.4rem;
            color: var(--text-main);
            line-height: 1.3;
        }
        
        .preview-text { 
            color: var(--text-muted); 
            line-height: 1.6; 
            flex-grow: 1; 
            margin-bottom: 1.5rem;
            overflow-wrap: break-word; 
            word-break: break-word;
        }

        /* Read More Link Styling */
        .read-more-link {
            color: var(--primary);
            font-weight: bold;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .read-more-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* Language Switcher Styles */
        .lang-switch {
            background-color: #f0f0f5;
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .lang-switch a { text-decoration: none; color: var(--text-muted); padding: 0 4px; }
        .lang-switch a.active { font-weight: bold; color: var(--primary); }
    </style>
</head>
<body>

    <nav>
        <div class="container nav-content">
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="color: var(--text-muted); margin-right: 10px;">
                        <?= trans('welcome') ?>, <strong style="color: var(--primary);"><?= htmlspecialchars($_SESSION['username']) ?></strong>!
                    </span>
                    <a href="/logout" class="btn btn-danger"><?= trans('nav_logout') ?></a>
                <?php else: ?>
                    <a href="/login"><?= trans('nav_login') ?></a>
                    <a href="/register" class="btn btn-primary" style="padding: 0.4rem 1rem; color: white; margin-right: 0;"><?= trans('nav_register') ?></a>
                <?php endif; ?>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1rem;">
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/post/create" class="btn btn-primary">
                        + <?= trans('nav_create') ?>
                    </a>
                <?php endif; ?>

                <div class="lang-switch">
                    <?php $currentLang = $_SESSION['lang'] ?? 'de'; ?>
                    <a href="/language?code=de" class="<?= $currentLang === 'de' ? 'active' : '' ?>">DE</a>
                    <span style="color: #ccc">|</span>
                    <a href="/language?code=en" class="<?= $currentLang === 'en' ? 'active' : '' ?>">EN</a>
                </div>

            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="page-header">
            <h1><?= trans('latest_posts') ?></h1>
            <div style="height: 4px; width: 60px; background-color: var(--secondary); margin: 0 auto; border-radius: 2px;"></div>
        </div>

        <?php if (empty($posts)): ?>
            <p style="text-align: center; color: var(--text-muted); font-size: 1.2rem;">
                <?= trans('no_posts') ?>
            </p>
        <?php else: ?>
            <div class="post-list">
                <?php foreach ($posts as $post): ?>
                    
                    <article class="post-card">
                        
                        <?php if (!empty($post['image_url'])): ?>
                            <div class="post-image-container">
                                <img src="/<?= htmlspecialchars($post['image_url']) ?>" alt="<?= trans('alt_image') ?>" class="post-image">
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <div class="meta">
                                <?= trans('from') ?> <strong><?= htmlspecialchars($post['username']) ?></strong> 
                                <span style="margin: 0 5px; color: #ddd;">•</span>
                                <?= htmlspecialchars($post['display_date']) ?>
                            </div>

                            <h2><?= htmlspecialchars($post['title']) ?></h2>
                            
                            <p class="preview-text">
                                <?= htmlspecialchars($post['preview_content']) ?>
                            </p>

                            <div style="margin-top: auto;"> 
                                <a href="/post/show?id=<?= $post['id'] ?>" class="read-more-link">
                                    <?= trans('read_more') ?> &rarr;
                                </a>
                            </div>
                        </div>

                    </article>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div> </body>
</html>