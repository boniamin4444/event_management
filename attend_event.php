<?php
session_start();
// Include your database connection
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $full_name = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $message = $_POST['message'];
    $status = 'pending';  // Set default status as 'pending'

    // Check if the user already registered for this event by user_id and event_id
    $checkSql = "SELECT COUNT(*) FROM attend_event WHERE (user_id = ? AND event_id = ?) OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("iis", $user_id, $event_id, $email);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    // If the count is greater than 0, the user has already registered for this event
    if ($count > 0) {
        // Show an alert message if the user has already registered
        echo "<script>alert('You have already registered for this event with either your user ID or email.');window.location.href='index.php';</script>";
        exit;
    }

    // Prepare the SQL query to insert the attendance record into attend_event table
    $sql = "INSERT INTO attend_event (user_id, event_id, full_name, email, phone, address, message, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; 

    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters to the statement
    $stmt->bind_param("iissssss", $user_id, $event_id, $full_name, $email, $phone, $address, $message, $status);
    
    // Execute the query
    if ($stmt->execute()) {
        // Redirect the user to a success page or back to the event page
        echo "<script>alert('You have successfully registered for the event.'); window.location.href='index.php';</script>";
        exit;
    } else {
        // Handle error (optional)
        echo "<script>alert('Error: " . $stmt->error . "');window.location.href='index.php';</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
