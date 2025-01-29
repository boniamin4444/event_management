<?php
session_start();
// Include your database connection
include('db.php');

// Check if the user is an admin
// (You can add authentication and authorization checks here if needed)

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_status'])) {
    $registration_id = $_POST['registration_id'];
    $event_id = $_POST['event_id'];
    
    // Check if the status is already 'confirmed'
    $checkStatusSql = "SELECT status FROM attend_event WHERE user_id = ? AND event_id = ?";
    $checkStmt = $conn->prepare($checkStatusSql);
    $checkStmt->bind_param("ii", $registration_id, $event_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        
        if ($row['status'] === 'confirmed') {
            // If status is already confirmed, show an alert
            echo "<script>alert('You already changed the status to confirmed.'); window.location.href='manage_event_registrations.php';</script>";
        } else {
            // Update the status of the registration to 'confirmed'
            $updateStatusSql = "UPDATE attend_event SET status = 'confirmed' WHERE user_id = ? AND event_id = ?";
            $updateStmt = $conn->prepare($updateStatusSql);
            $updateStmt->bind_param("ii", $registration_id, $event_id);
            
            if ($updateStmt->execute()) {
                // Status successfully updated
                echo "<script>alert('Status updated to confirmed.'); window.location.href='manage_event_registrations.php';</script>";
            } else {
                // Handle error
                echo "<script>alert('Error: " . $updateStmt->error . "'); window.location.href='manage_event_registrations.php';</script>";
            }

            // Close the update statement
            $updateStmt->close();
        }
    } else {
        // Handle case where no registration exists for the given user_id and event_id
        echo "<script>alert('Registration not found.'); window.location.href='manage_event_registrations.php';</script>";
    }

    // Close the check status statement
    $checkStmt->close();
}

$conn->close();
?>
