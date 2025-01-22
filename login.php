<?php
// Start the session to manage user login state
session_start();

// Include the database connection file
include 'db.php';

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the login form
    $email = trim($_POST['loginEmail']);
    $password = $_POST['loginPassword'];

    // Validate the inputs (simple validation)
    if (empty($email) || empty($password)) {
        echo "Both email and password are required!";
        exit;
    }

    // Prepare SQL query to check if the user exists in the database
    $sql = "SELECT id, full_name, email, password, role FROM users WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Bind the email parameter to the SQL query
        $stmt->bind_param("s", $email);
        
        // Execute the query
        $stmt->execute();
        
        // Store the result
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows > 0) {
            // Bind the result to variables
            $stmt->bind_result($id, $full_name, $db_email, $db_password, $role);
            $stmt->fetch();

            // Verify the password using password_verify() for security
            if (password_verify($password, $db_password)) {
                // Password is correct, create a session for the logged-in user
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_email'] = $db_email;
                $_SESSION['role'] = $role; // Store the role in session

                // Redirect based on user role
                if ($role === 'admin') {
                    // Redirect to the admin dashboard
                    header("Location: dashboard.php");
                } else {
                    // Redirect to the user homepage or index page
                    header("Location: index.php");
                }
                exit;
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "No user found with that email.";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing the query.";
    }

    // Close the database connection
    $conn->close();
}
?>
