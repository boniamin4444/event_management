<?php
include 'db.php'; // Include your database connection
header("Access-Control-Allow-Origin: *");  // Allow all domains
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Allow specific headers

// Check if form data is posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $full_name = trim($_POST['registerName']);
    $email = trim($_POST['registerEmail']);
    $password = $_POST['registerPassword'];
    $confirmPassword = $_POST['confirmPassword']; // Add confirm password check

    // Server-side validation
    $errors = [];

    // Validate Full Name (ensure it contains only letters and spaces)
    if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $errors[] = "Full name should contain only letters and spaces.";
    }

    // Validate Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Confirm password check
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // If there are errors, return the error message as a response
    if (!empty($errors)) {
        echo implode("<br>", $errors);
        exit;
    }

    // Hash the password for secure storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert the user data into the users table
    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters and execute query
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful!";
            header("location:index.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing SQL statement.";
    }

    // Close the database connection
    $conn->close();
}
?>
