<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Marketplace</h1>
            <nav>
                <ul>
                    <li><a href="item_list.php">Items for Sale</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="hero">
            <h2>Welcome to the Marketplace</h2>
            <p>Your one-stop shop for all game items!</p>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Marketplace</p>
    </footer>
</body>
</html>
