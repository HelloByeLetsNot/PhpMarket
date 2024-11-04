<?php
session_start();
include 'db.php';
$db = getDBConnection();

// Check if user ID is set in the URL
if (!isset($_GET['id'])) {
    header("Location: profile_list.php"); // Redirect if no ID is provided
    exit();
}

$user_id = intval($_GET['id']);

// Fetch user details from the database
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    header("Location: profile_list.php"); // Redirect if user not found
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($user['username']); ?>'s Profile</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="items.php">Items</a>
        </nav>
    </header>

    <main>
        <h2>Discord: <?php echo htmlspecialchars($user['discord'] ?? 'Not Provided'); ?></h2>
        <h3>Items Listed:</h3>
        <ul>
            <?php
            // Fetch items listed by the user
            $items_stmt = $db->prepare("SELECT * FROM items WHERE user_id = :user_id");
            $items_stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $items_result = $items_stmt->execute();

            while ($item = $items_result->fetchArray(SQLITE3_ASSOC)): ?>
                <li>
                    <a href="item.php?id=<?php echo htmlspecialchars($item['id']); ?>">
                        <?php echo htmlspecialchars($item['name']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
