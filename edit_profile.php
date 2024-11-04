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

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $discord = trim($_POST['discord']);

    // Validate inputs
    if (empty($username) || empty($discord)) {
        echo "All fields are required.";
    } else {
        // Update user information in the database
        $stmt = $db->prepare("UPDATE users SET username = :username, discord = :discord WHERE id = :id");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':discord', $discord, SQLITE3_TEXT);
        $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);

        if ($stmt->execute()) {
            // Redirect back to the dashboard after successful update
            header("Location: dashboard.php?update=success");
            exit();
        } else {
            echo "Failed to update profile. Please try again.";
        }
    }
} else {
    // Fetch user details for the form
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Edit Profile</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="items.php">Items</a>
            <a href="post_item.php">Post Item</a>
            <a href="profile_list.php">Profiles</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <form action="edit_profile.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <br>
            <label for="discord">Discord:</label>
            <input type="text" id="discord" name="discord" value="<?php echo htmlspecialchars($user['discord']); ?>" required>
            <br>
            <input type="submit" value="Update Profile">
        </form>

        <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
            <p>Profile updated successfully!</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
