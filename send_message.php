<?php
session_start();
include 'db.php';
$db = getDBConnection();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $receiver_id = $_POST['receiver_id']; // The user to whom the message is sent
    $message = $_POST['message'];

    // Prepare and execute the statement
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, item_id, message) VALUES (:sender_id, :receiver_id, :item_id, :message)");
    $stmt->bindValue(':sender_id', $_SESSION['user_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':receiver_id', $receiver_id, SQLITE3_INTEGER);
    $stmt->bindValue(':item_id', $item_id, SQLITE3_INTEGER);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message.";
    }
}
?>
