<?php
session_start();
include 'db.php';
$db = getDBConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch messages for the logged-in user
$user_id = $_SESSION['user_id'];
$messages_stmt = $db->prepare("SELECT messages.*, users.username AS sender_username FROM messages JOIN users ON messages.sender_id = users.id WHERE messages.receiver_id = :receiver_id ORDER BY created_at DESC");
$messages_stmt->bindValue(':receiver_id', $user_id, SQLITE3_INTEGER);
$messages_result = $messages_stmt->execute();

if ($messages_result->numColumns() == 0) {
    echo "No messages found.";
} else {
    while ($message = $messages_result->fetchArray(SQLITE3_ASSOC)) {
        echo "<div class='message'>";
        echo "<p><strong>From:</strong> " . htmlspecialchars($message['sender_username']) . "</p>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($message['message']) . "</p>";
        echo "<p><strong>Sent:</strong> " . htmlspecialchars($message['created_at']) . "</p>";
        
        // Reply form
        echo "<form action='reply_message.php' method='POST'>";
        echo "<input type='hidden' name='receiver_id' value='" . htmlspecialchars($message['sender_id']) . "'>";
        echo "<input type='hidden' name='original_message_id' value='" . htmlspecialchars($message['id']) . "'>";
        echo "<label for='reply'>Your Reply:</label>";
        echo "<textarea id='reply' name='reply' rows='3' required></textarea><br>";
        echo "<input type='submit' value='Send Reply'>";
        echo "</form>";

        echo "</div><hr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Inbox</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="inbox.php">Inbox</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Your Messages</h2>
        <div class="messages-container">
            <?php
            // Messages displayed here
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
