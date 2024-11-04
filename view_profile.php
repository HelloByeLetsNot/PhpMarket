<?php
session_start();
include 'db.php'; // Ensure this line is included
$db = getDBConnection(); // Get the database connection
if (!isset($_GET['id'])) {
    die("User ID is required.");
}

$user_id = $_GET['id'];
$db = getDBConnection();
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EO Market - Profile</title>
        <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>EO Market</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="post_item.php">Post Item</a></li>
                <li><a href="profile_list.php">Profiles</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
        <p>Discord: <?php echo htmlspecialchars($user['discord']); ?></p>
        <p>In-Game Name: <?php echo htmlspecialchars($user['ingame_name']); ?></p>
        <p>Joined on: <?php echo htmlspecialchars($user['created_at']); ?></p>
        <h3>Items Posted:</h3>
        <ul>
            <?php
            $itemStmt = $db->prepare("SELECT * FROM items WHERE user_id = :user_id");
            $itemStmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $itemResult = $itemStmt->execute();

            while ($item = $itemResult->fetchArray(SQLITE3_ASSOC)): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['title']); ?></strong> - <?php echo htmlspecialchars($item['description']); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>

