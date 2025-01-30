<?php
// Start the session to manage user login state
session_start();

// Include the database connection file
include 'db.php'; // Make sure this file contains your database connection details

// Set headers for CORS (if necessary)
header("Access-Control-Allow-Origin: *");  // Allow all domains
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Allow specific headers

// Simulate login status (Replace this logic with your authentication system)
$_SESSION['loggedin'] = $_SESSION['loggedin'] ?? false; // Default: Not logged in

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $email = trim($_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    // Validate the inputs (simple validation)
    if (empty($email) || empty($password)) {
        echo "Both email and password are required!"; // Debugging statement
        exit;
    }

    // Prepare SQL query to check if the user exists in the database
    $sql = "SELECT id, full_name, email, password, role FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the email parameter to the SQL query
        $stmt->bind_param("s", $email);
        
        // Execute the query
        if ($stmt->execute()) {
            // Store the result
            $stmt->store_result();

            // Check if user exists
            if ($stmt->num_rows > 0) {
                // Bind the result to variables
                $stmt->bind_result($id, $full_name, $db_email, $db_password, $role);
                $stmt->fetch();

                // Debug: Output stored hash and entered password for comparison (remove this in production)
                // echo "Stored hash: " . $db_password . "<br>";
                // echo "Entered password: " . $password . "<br>";

                // Verify the password using password_verify() for security
                if (password_verify($password, $db_password)) {
                    // Password is correct, create a session for the logged-in user
                    $_SESSION['loggedin'] = true; // Mark the user as logged in
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $full_name;
                    $_SESSION['user_email'] = $db_email;
                    $_SESSION['role'] = $role; // Store the role in session

                    // Redirect based on user role
                    if ($role === 'admin') {
                        // Redirect to the admin dashboard
                        header("Location: user_dashboard.php");
                    } else {
                        // Redirect to the user homepage or index page
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    echo "<script>
        alert('Incorrect password.');
        window.location.href = 'index.php';
      </script>";

                }
            } else {
                echo "<scriot>alert('No user found with that email.'<script>"; // Debugging statement
            }
        } else {
            echo "Error executing the query: " . $conn->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

