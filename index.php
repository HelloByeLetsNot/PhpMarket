<?php
session_start();
include 'db.php'; // Ensure this line is included
$db = getDBConnection(); // Get the database connection
// Fetch recent items
$db = getDBConnection();
$itemStmt = $db->prepare("SELECT * FROM items ORDER BY created_at DESC LIMIT 10");
$itemResult = $itemStmt->execute();

$items = [];
while ($item = $itemResult->fetchArray(SQLITE3_ASSOC)) {
    $items[] = $item;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EO Market - Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Player+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>EO Market</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="profile_list.php">Profiles</a>
            <a href="login.php">Login</a>

        </nav>
    </header>
<main>
    <h2>Recent Listings</h2>
    <div class="item-list">
        <?php foreach ($items as $item): ?>
            <div class="item">
                <a href="item.php?id=<?php echo $item['id']; ?>">
                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

    <footer>
        <p>&copy; <?= date("Y") ?> EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
