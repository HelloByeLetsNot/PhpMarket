<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current user data
    $stmt = $db->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    // Check if current password is correct
    if ($user && password_verify($current_password, $user['password'])) {
        // Check if new password matches the confirm password
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password in database
            $update_stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
            $update_stmt->bindValue(':password', $hashed_password);
            $update_stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
            if ($update_stmt->execute()) {
                $message = 'Password changed successfully.';
            } else {
                $error = 'Error updating password. Please try again.';
            }
        } else {
            $error = 'New passwords do not match.';
        }
    } else {
        $error = 'Current password is incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - EO Market</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>EO Market</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="items.php">Items</a>
            <a href="profile_list.php">Profiles</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Change Password</h2>
        <?php if ($message): ?>
            <p class="success"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit">Change Password</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 EO Market. All rights reserved.</p>
    </footer>
</body>
</html>
