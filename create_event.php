<?php
session_start();  // Start the session to access session variables

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include 'db.php'; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Check if any images are uploaded
        if (isset($_FILES['eventImages']['tmp_name']) && is_array($_FILES['eventImages']['tmp_name'])) {
            $numFiles = count($_FILES['eventImages']['tmp_name']);
            
            // Allowed image extensions
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

            // Handle multiple image uploads
            for ($i = 0; $i < $numFiles; $i++) {
                $imageTmpName = $_FILES['eventImages']['tmp_name'][$i];
                $imageName = $_FILES['eventImages']['name'][$i];
                $imageSize = $_FILES['eventImages']['size'][$i];
                $imageError = $_FILES['eventImages']['error'][$i];

                if ($imageError === 0) {
                    // Get the file extension and convert it to lowercase for case-insensitivity
                    $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                    $imageExtension = strtolower($imageExtension);

                    // Check if the extension is allowed
                    if (in_array($imageExtension, $allowedExtensions)) {
                        // Generate a unique name for the image
                        $newImageName = uniqid('', true) . "." . $imageExtension;
                        $imagePath = $uploadDirectory . $newImageName;

                        // Move the uploaded image to the uploads directory
                        if (move_uploaded_file($imageTmpName, $imagePath)) {
                            $imagePaths[] = $imagePath;  // Add the image path to the array
                        } else {
                            echo "Error moving the image.";
                            exit;
                        }
                    } else {
                        echo "Uploaded file is not a valid image type.";
                        exit;
                    }
                } else {
                    echo "Error uploading image file.";
                    exit;
                }
            }
        }

        // Convert image paths array to serialized string
        $imagePathsSerialized = serialize($imagePaths);

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
