<?php
session_start();
include 'db.php';
$db = getDBConnection();

// Fetch all users for the profile list
$result = $db->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profiles - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>User Profiles</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a href="items.php">Items</a>
        </nav>
    </header>

    <main>
        <ul>
            <?php while ($user = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <li>
                    <h2>
                        <a href="profile.php?id=<?php echo htmlspecialchars($user['id']); ?>">
                            <?php echo htmlspecialchars($user['username'] ?? 'Unknown User'); ?>
                        </a>
                    </h2>
                    <p>Discord: <?php echo htmlspecialchars($user['discord'] ?? 'Not Provided'); ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
