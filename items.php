<?php
session_start();
include 'db.php'; // Ensure this line is included
$db = getDBConnection(); // Get the database connection
// Fetch all items from the database
$result = $db->query("SELECT items.*, users.username FROM items JOIN users ON items.user_id = users.id");
$items = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>EO Market</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="post_item.php">Post Item</a>
            <a href="profile_list.php">Profiles</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main>
        <h2>All Items</h2>
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
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
