<?php
session_start();

// Simulate login status (Replace this logic with your authentication system)
$_SESSION['loggedin'] = $_SESSION['loggedin'] ?? false; // Default: Not logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar with Modals</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #1d3557, #457b9d); /* Gradient */
            color: #fff;
        }
        .navbar-custom .navbar-brand {
            color: #f1faee;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .navbar-custom .navbar-brand:hover {
            color: #a8dadc;
        }
        .navbar-custom .nav-link {
            color: #f1faee;
            margin: 0 10px;
            font-size: 1rem;
            font-weight: 500;
        }
        .navbar-custom .nav-link:hover {
            color: #a8dadc;
            text-decoration: none;
        }
        .dropdown-menu {
            background-color: #1d3557;
            border: none;
        }
        .dropdown-menu .dropdown-item {
            color: #f1faee;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #457b9d;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">MyWebsite</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="color: white;"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Left-aligned links -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Services</a>
                        </li>
                    </ul>

                    <!-- Right-aligned links -->
                    <ul class="navbar-nav ms-auto">
                        <?php if ($_SESSION['loggedin']): ?>
                            <!-- Dropdown for logged-in users -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    User
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="#">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li><a class="dropdown-item" href="#">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <!-- Login link for guests -->
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

   <!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST">
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Register here</a></p>
                <div id="errorMessage" class="text-danger mt-3"></div>
                <div id="successMessage" class="text-success mt-3"></div>
            </div>
        </div>
    </div>
</div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Register</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="registerName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="registerName" name="registerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="registerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="registerPassword" name="registerPassword" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-success">Register</button>
                </form>
                <div id="errorMessage" class="text-danger mt-2"></div>
                <div id="successMessage" class="text-success mt-2"></div>
            </div>
        </div>
    </div>
</div>





<script>
$(document).ready(function() {
    // Handle form submission with AJAX
    $("#registerForm").on("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        var fullName = $("#registerName").val();
        var email = $("#registerEmail").val();
        var password = $("#registerPassword").val();
        var confirmPassword = $("#confirmPassword").val();

        // Client-side validation
        if (password !== confirmPassword) {
            $("#errorMessage").text("Passwords do not match.");
            return false;
        }

        if (fullName === "" || email === "" || password === "" || confirmPassword === "") {
            $("#errorMessage").text("All fields are required.");
            return false;
        }

        if (password.length < 8) {
            $("#errorMessage").text("Password must be at least 8 characters.");
            return false;
        }

        // Clear any previous messages
        $("#errorMessage").text('');
        $("#successMessage").text('');

        // AJAX request to submit form data to the PHP script
        $.ajax({
            url: "register.php", // The PHP file that will process the registration
            method: "POST",
            data: {
                registerName: fullName,
                registerEmail: email,
                registerPassword: password
            },
            success: function(response) {
                // Show the success message
                $("#successMessage").text(response);
            },
            error: function() {
                $("#errorMessage").text("An error occurred. Please try again.");
            }
        });
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
