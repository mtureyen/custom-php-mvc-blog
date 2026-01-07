<?php
/**
 * Database Seeder Script
 *
 * This script populates the database with dummy data for testing purposes.
 * It performs the following actions:
 * 1. Clears existing data from users, posts, and comments tables.
 * 2. Resets auto-increment counters.
 * 3. Creates default test users.
 * 4. Generates blog posts using images from the 'tests/fixtures' directory.
 * 5. Simulates user uploads by copying fixture images to the public upload folder.
 * 6. Adds random comments to the generated posts.
 *
 * Usage: Run via 'seed_data.bat' (Windows) or command line inside the container.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Service\Database;

// 1. Initialize Database Connection
$pdo = (new Database())->getConnection();

echo "🌱 Starting Database Seeding...\n";

// ---------------------------------------------------------
// Cleanup: Remove old data and reset IDs
// ---------------------------------------------------------
// We delete in reverse order of dependencies (Comments -> Posts -> Users)
// to avoid foreign key constraint violations.
$pdo->exec("DELETE FROM comments");
$pdo->exec("DELETE FROM posts");
$pdo->exec("DELETE FROM users");

// Reset Auto-Increment counters to start IDs at 1 again
$pdo->exec("ALTER TABLE users AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE posts AUTO_INCREMENT = 1");
$pdo->exec("ALTER TABLE comments AUTO_INCREMENT = 1");

echo "Old data cleaned up.\n";

// ---------------------------------------------------------
// 2. Create Test Users
// ---------------------------------------------------------
$users = ['Alice', 'Bob', 'Charlie', 'Dave'];
// All test users share the same password for easier testing
$passwordHash = password_hash('password123', PASSWORD_DEFAULT); 

foreach ($users as $username) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:name, :pw)");
    $stmt->execute([':name' => $username, ':pw' => $passwordHash]);
}

echo "4 Users created (Password for all: 'password123')\n";

// ---------------------------------------------------------
// 3. Process Images & Create Posts
// ---------------------------------------------------------
$sourceDir = __DIR__ . '/fixtures';          // Source: Test images
$targetDir = __DIR__ . '/../public/uploads'; // Target: Webroot upload folder

// Ensure the target upload directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Retrieve IDs of the newly created users
$userIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);

// Define dummy titles for the blog posts
$titles = [
    "My First Vacation",
    "Why PHP is Awesome",
    "Docker for Beginners",
    "The Best Food in the World"
];

// Get all .jpg images from the fixtures folder
$imageFiles = glob($sourceDir . '/*.jpg'); 
$imageCounter = 0;

foreach ($titles as $index => $title) {
    // Assign a random author to the post
    $randomUserId = $userIds[array_rand($userIds)];
    
    // Image Handling Logic
    $dbImagePath = null;
    if (!empty($imageFiles)) {
        // Cycle through images if there are fewer images than posts (Modulo operator)
        $sourceImage = $imageFiles[$imageCounter % count($imageFiles)];
        $filename = basename($sourceImage);
        $targetPath = $targetDir . '/' . $filename;
        
        // Simulate an upload by copying the file physically
        copy($sourceImage, $targetPath);
        
        // Path to be stored in the database (relative to public folder)
        $dbImagePath = 'uploads/' . $filename;
        
        $imageCounter++;
    }

    // Insert Post into Database
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content, image_url, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([
        $randomUserId, 
        $title, 
        "Here is some dummy text for the post '$title'. Lorem ipsum dolor sit amet, consectetur adipiscing elit.", 
        $dbImagePath
    ]);
}

echo "Posts created (including images).\n";

// ---------------------------------------------------------
// 4. Generate Random Comments
// ---------------------------------------------------------
// Fetch all created Post IDs
$postIds = $pdo->query("SELECT id FROM posts")->fetchAll(PDO::FETCH_COLUMN);

$dummyComments = [
    "Wow, great post!",
    "I totally agree with this.",
    "Nice picture!",
    "Interesting perspective.",
    "Thanks for sharing."
];

foreach ($postIds as $postId) {
    // Generate 2 random comments per post
    for ($i = 0; $i < 2; $i++) {
        $randomUserId = $userIds[array_rand($userIds)];
        $randomComment = $dummyComments[array_rand($dummyComments)];
        
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$postId, $randomUserId, $randomComment]);
    }
}

echo "Comments generated.\n";
echo "DONE! Happy testing.\n";