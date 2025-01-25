<?php
// Include the database connection file
include '../db.php';

// SQL query to create the users table with 'role' column
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',  -- Add ENUM column with default value 'user'
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// SQL query to create the events table with 'user_id' column (without foreign key)
$sql_events = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    user_id INT NOT NULL,  -- Add user_id without foreign key constraint
    event_name VARCHAR(255) NOT NULL,
    event_description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_location VARCHAR(255) NOT NULL,
    event_capacity INT NOT NULL,
    event_images TEXT,  -- To store serialized array of images
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";



$sql_attend_events = "CREATE TABLE IF NOT EXISTS attend_event (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    message TEXT,
    attend_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'pending') DEFAULT 'pending'
)";


// Execute the query for users table
if ($conn->query($sql_users) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// Execute the query for events table
if ($conn->query($sql_events) === TRUE) {
    echo "Table 'events' created successfully.<br>";
} else {
    echo "Error creating table 'events': " . $conn->error . "<br>";
}
if ($conn->query($sql_attend_events) === TRUE) {
    echo "Table 'Attend events table' created successfully.<br>";
} else {
    echo "Error creating table 'events': " . $conn->error . "<br>";
}

// Close the database connection
$conn->close();
?>
