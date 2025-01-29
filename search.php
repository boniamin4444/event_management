<?php
session_start();
error_reporting(0);
include('db.php');

// Capture the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch events from the database and their attendee counts
$sql = "SELECT * FROM events WHERE event_name LIKE ?";
$stmt = $conn->prepare($sql);

// Bind the search term to the query using wildcards for partial matches
$searchTerm = "%" . $searchTerm . "%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch event data into an array
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get the current attendees count for this event
        $event_id = $row['id'];
        
        $attendees_sql = "SELECT COUNT(*) FROM attend_event WHERE event_id = ?";
        $att_stmt = $conn->prepare($attendees_sql);
        $att_stmt->bind_param("i", $event_id);
        $att_stmt->execute();
        $att_stmt->bind_result($attendees_count);
        $att_stmt->fetch();

        $row['attendees_count'] = $attendees_count;
        $events[] = $row;

        $att_stmt->close();
    }
} else {
    echo "No events found.";
}

$conn->close();
?>
