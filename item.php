<?php
session_start();
include 'db.php';
$db = getDBConnection();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Variable to store message status
$message_status = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $receiver_id = $_POST['receiver_id']; // The user to whom the message is sent
    $message = $_POST['message'];

    // Prepare and execute the statement
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, item_id, message, created_at) VALUES (:sender_id, :receiver_id, :item_id, :message, CURRENT_TIMESTAMP)");
    $stmt->bindValue(':sender_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':receiver_id', $receiver_id, SQLITE3_INTEGER);
    $stmt->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $message_status = 'Message sent successfully!';
    } else {
        $message_status = 'Failed to send message.';
    }
}

// Fetch item details based on ID passed in URL
$item_id = $_GET['id'] ?? null;
if (!$item_id) {
    echo "Item not found.";
    exit();
}

$item_stmt = $db->prepare("SELECT items.*, users.username AS seller_username, users.discord AS seller_discord FROM items JOIN users ON items.user_id = users.id WHERE items.id = :item_id");
$item_stmt->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
$item_result = $item_stmt->execute();
$item = $item_result->fetchArray(SQLITE3_ASSOC);

if (!$item) {
    echo "Item not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Item Details</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="inbox.php">Inbox</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section class="item-details-box">
            <h2><?php echo htmlspecialchars($item['name']); ?></h2>
            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Item Image" class="item-image">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($item['description']); ?></p>
            <p><strong>Price:</strong> $<?php echo htmlspecialchars($item['price']); ?></p>
            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
            <p><strong>Posted by:</strong> <?php echo htmlspecialchars($item['seller_username']); ?> (Discord: <?php echo htmlspecialchars($item['seller_discord']); ?>)</p>
        </section>

        <?php if ($message_status): ?>
            <p class="message-status"><?php echo $message_status; ?></p>
        <?php endif; ?>

        <section class="message-box">
            <h3>Send a Message to the Seller</h3>
            <form action="" method="POST">
                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($item['user_id']); ?>">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <br>
                <input type="submit" value="Send Message">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
