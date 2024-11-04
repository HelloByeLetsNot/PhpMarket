<?php
session_start();
include 'db.php';
$db = getDBConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch messages sent to the user
$stmt = $db->prepare("SELECT m.*, u.username as sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = :receiver_id ORDER BY m.created_at DESC");
$stmt->bindValue(':receiver_id', $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Messages - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Your Messages</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Messages</h2>
        <ul>
            <?php while ($message = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <li>
                    <strong><?php echo htmlspecialchars($message['sender_username']); ?>:</strong>
                    <?php echo htmlspecialchars($message['message']); ?>
                    <em>(<?php echo $message['created_at']; ?>)</em>
                </li>
            <?php endwhile; ?>
            <?php if ($result->numColumns() == 0): ?>
                <li>No messages found.</li>
            <?php endif; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
