<?php
session_start();
include 'db.php';
$db = getDBConnection();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$user_stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
$user_result = $user_stmt->execute();
$user = $user_result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

// Fetch user's items
$items_stmt = $db->prepare("SELECT * FROM items WHERE user_id = :user_id");
$items_stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$items_result = $items_stmt->execute();

// Fetch user's messages (limit to latest 5 for preview)
$messages_stmt = $db->prepare("SELECT messages.*, users.username AS sender_username FROM messages JOIN users ON messages.sender_id = users.id WHERE receiver_id = :receiver_id ORDER BY created_at DESC LIMIT 5");
$messages_stmt->bindValue(':receiver_id', $user_id, SQLITE3_INTEGER);
$messages_result = $messages_stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="inbox.php">Inbox</a>
>
        </nav>
    </header>

    <main>
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        
        <section class="dashboard-box">
            <h3>Your Listings</h3>
            <ul>
                <?php while ($item = $items_result->fetchArray(SQLITE3_ASSOC)): ?>
                    <li>
                        <a href="item.php?id=<?php echo htmlspecialchars($item['id']); ?>">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
                <?php if ($items_result->numColumns() == 0): ?>
                    <li>No listings found.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="dashboard-box">
            <h3>Inbox (Recent Messages)</h3>
            <ul>
                <?php while ($message = $messages_result->fetchArray(SQLITE3_ASSOC)): ?>
                    <li>
                        <strong>From:</strong> <?php echo htmlspecialchars($message['sender_username']); ?><br>
                        <strong>Message:</strong> <?php echo htmlspecialchars(substr($message['message'], 0, 50)) . '...'; ?><br>
                        <a href="inbox.php">View all messages</a>
                    </li>
                    <hr>
                <?php endwhile; ?>
                <?php if ($messages_result->numColumns() == 0): ?>
                    <li>No messages found.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section class="dashboard-box">
            <h3>Edit Profile</h3>
            <form action="edit_profile.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <br>
                <label for="discord">Discord:</label>
                <input type="text" id="discord" name="discord" value="<?php echo htmlspecialchars($user['discord']); ?>" required>
                <br>
                <input type="submit" value="Update Profile">
            </form>
        </section>

        <section class="dashboard-box">
            <h3>Change Password</h3>
            <form action="change_password.php" method="POST">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Change Password">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
