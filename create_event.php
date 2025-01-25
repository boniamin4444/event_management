<?php
session_start();  // Start the session to access session variables

// Include the database connection
include 'db.php'; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    // Sanitize and validate form input
    $userId = $_POST['userId'];
    $eventName = trim($_POST['eventName']);
    $eventDescription = trim($_POST['eventDescription']);
    $eventDate = $_POST['eventDate'];
    $eventLocation = trim($_POST['eventLocation']);
    $eventCapacity = $_POST['eventCapacity'];

    // Server-side validation
    if (empty($userId) || empty($eventName) || empty($eventDescription) || empty($eventDate) || empty($eventLocation) || empty($eventCapacity)) {
        echo "All fields are required!";
        exit;
    }

    // Prepare SQL query to insert the event details into the events table
    $sql = "INSERT INTO events (user_id, event_name, event_description, event_date, event_location, event_capacity, event_images) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Handle file uploads
        $uploadDirectory = "uploads/"; // Directory where image will be stored
        $imagePaths = [];

        // Loop through the uploaded files
        foreach ($_FILES['eventImages']['tmp_name'] as $key => $tmp_name) {
            $imageName = $_FILES['eventImages']['name'][$key];
            $imageTmpName = $_FILES['eventImages']['tmp_name'][$key];
            $imageSize = $_FILES['eventImages']['size'][$key];
            $imageError = $_FILES['eventImages']['error'][$key];

            if ($imageError === 0) {
                // Check if file is an image
                $imageType = mime_content_type($imageTmpName);
                if (strpos($imageType, "image") !== false) {
                    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                    $newImageName = uniqid('', true) . "." . $imageExtension;
                    $imagePath = $uploadDirectory . $newImageName;

                    // Move the uploaded image to the uploads directory
                    if (move_uploaded_file($imageTmpName, $imagePath)) {
                        $imagePaths[] = $imagePath;
                    } else {
                        echo "Error moving the image.";
                        exit;
                    }
                } else {
                    echo "Uploaded file is not an image.";
                    exit;
                }
            } else {
                echo "Error uploading image file.";
                exit;
            }
        }

        // Convert image paths array to serialized string
        $imagePathsSerialized = serialize($imagePaths);

        // Debugging output
        echo "Query: $sql<br>";
        echo "User ID: $userId<br>";
        echo "Event Name: $eventName<br>";
        echo "Event Description: $eventDescription<br>";
        echo "Event Date: $eventDate<br>";
        echo "Event Location: $eventLocation<br>";
        echo "Event Capacity: $eventCapacity<br>";
        echo "Serialized Images: $imagePathsSerialized<br>";

        // Bind the parameters to the SQL query
        $stmt->bind_param("issssss", $userId, $eventName, $eventDescription, $eventDate, $eventLocation, $eventCapacity, $imagePathsSerialized);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Event created successfully!'); window.location = 'index.php';</script>";
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement.";
    }

    // Close the database connection
    $conn->close();
}
?>
