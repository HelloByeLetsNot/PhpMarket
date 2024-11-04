<?php
session_start();
include 'db.php';
$db = getDBConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$reply_success = false;
$reply_failure = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_id = $_POST['receiver_id'];
    $reply = $_POST['reply'];

    // Prepare and execute the statement to insert the reply with NULL for item_id
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, item_id, message, created_at) VALUES (:sender_id, :receiver_id, NULL, :message, CURRENT_TIMESTAMP)");
    $stmt->bindValue(':sender_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':receiver_id', $receiver_id, SQLITE3_INTEGER);
    $stmt->bindValue(':message', $reply, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $reply_success = true; // Indicate success
    } else {
        $reply_failure = true; // Indicate failure
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
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <?php if ($reply_success): ?>
            <p class="success-message">Message sent successfully!</p>
        <?php elseif ($reply_failure): ?>
            <p class="error-message">Failed to send message. Please try again.</p>
        <?php endif; ?>

        <!-- The rest of your inbox code goes here -->
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
