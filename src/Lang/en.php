<?php
return [
    // Navigation & General
    'welcome' => 'Hello',
    'nav_login' => 'Login',
    'nav_register' => 'Register',
    'nav_logout' => 'Logout',
    'nav_create' => 'Create new Post',
    'back_home' => 'Back to Home',

    // Home Page
    'latest_posts' => 'Latest Posts',
    'no_posts' => 'No posts yet.',
    'from' => 'By',
    'at' => 'on',
    'read_more' => 'Read more →',
    'alt_image' => 'Post image',

    // Detail Page
    'back_overview' => 'Back to Overview',
    'written_by' => 'Written by',
    'comments_headline' => 'Comments',
    'label_your_comment' => 'Your Comment:',
    'btn_submit_comment' => 'Post Comment',
    'msg_login_to_comment' => 'Please <a href="/login">login</a> to comment.',
    'msg_no_comments' => 'No comments yet. Be the first!',
    'placeholder_no_image' => 'No Image',

    // Create Post
    'create_heading' => 'Create new Post',
    'label_title' => 'Title:',
    'ph_title' => 'Enter title...',
    'label_content' => 'Content:',
    'ph_content' => 'Write something...',
    'label_image' => 'Upload Image (optional, max 5MB):',
    'btn_publish' => 'Publish',

    // Login Page
    'heading_login' => 'Login',
    'label_username' => 'Username:',
    'label_password' => 'Password:',
    'btn_login' => 'Login',
    'text_no_account' => 'No account yet?',
    'link_register_here' => 'Register here',

    // Register Page
    'heading_register' => 'Create Account',
    'label_password_repeat' => 'Repeat Password:',
    'btn_register' => 'Register',
    'text_have_account' => 'Already have an account?',
    'link_login_here' => 'Login here',
    'link_cancel_home' => 'Cancel and back to Home',


    // PostController Error Handler
    'err_img_too_big' => 'Image too large! Max 5 MB allowed.',
    'err_img_save' => 'Error saving the image.',
    'err_img_type' => 'Only JPG, PNG, GIF or WEBP allowed!',
    'err_server_limit' => 'File is way too large (Server limit exceeded).',
    'err_fill_fields' => 'Please fill in title and content!',

    // AuthController Error Handler
    'err_login_failed' => 'Invalid username or password!',
    'err_user_taken' => 'Username is already taken!',
    'err_no_spaces' => 'Username must not contain spaces!',
    'err_username_chars' => 'Username can only contain letters, numbers and underscores!',
    'err_username_length' => 'Username must be between 3 and 18 characters!',
    'err_pw_too_short' => 'Password must be at least 8 characters long!',
    'err_pw_mismatch' => 'Passwords do not match!',
];