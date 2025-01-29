<?php
session_start();
// Include your database connection
include('db.php');

// Check if the event_id is passed and handle CSV generation
if (isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Set the CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="registrations_event_' . $event_id . '.csv"');

    // Open output stream for CSV file
    $output = fopen('php://output', 'w');

    // Column headings for the CSV file
    fputcsv($output, ['Full Name', 'Email', 'Phone', 'Address', 'Message', 'Status']);

    // Fetch users registered for the event
    $sql = "SELECT full_name, email, phone, address, message, status FROM attend_event WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop through the result and write data to CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close the statement
    fclose($output);
    exit;
}
?>
