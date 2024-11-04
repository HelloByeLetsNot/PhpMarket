<?php
session_start();
include 'db.php'; // Ensure this line is included
$db = getDBConnection(); // Get the database connection
$stmt = $pdo->query("SELECT items.*, users.username FROM items JOIN users ON items.user_id = users.id");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items for Sale</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Items for Sale</h1>
                <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="post_item.php">Post Item</a>
            <a href="profile_list.php">Profiles</a>
            <a href="login.php">Login</a>
                </nav>
    </header>
    <main>
        <?php foreach ($items as $item): ?>
            <div class="item">
                <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                <p><?php echo htmlspecialchars($item['description']); ?></p>
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item image" width="200">
                <p>Posted by: <?php echo htmlspecialchars($item['username']); ?></p>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>
