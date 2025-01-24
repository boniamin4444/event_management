<?php
// Include the database connection file
include '../db.php';

// SQL query to create the users table with 'role' column
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',  -- Add ENUM column with default value 'user'
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Events table with images as serialized array
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_capacity INT NOT NULL,
    event_images TEXT,  -- To store serialized array of images
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";


// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
