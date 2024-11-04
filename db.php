<?php
// db.php

function getDBConnection() {
    // Create a new SQLite database connection
    $db = new SQLite3('marketplace.db');
    
    if (!$db) {
        die("Could not connect to the database.");
    }

    return $db;
}
?>
