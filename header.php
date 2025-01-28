<?php
session_start();
header("Access-Control-Allow-Origin: *");  // Allow all domains
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Allow specific headers

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
    <!-- FontAwesome for search icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #1d3557, #457b9d); /* Gradient */
            color: #fff;
            padding: 0;
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
            color: rgb(4, 4, 4);
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #457b9d;
            color: #fff;
        }

        /* For mobile responsiveness */
        .navbar-custom .navbar-toggler {
            border-color: #fff;
        }
        @media (max-width: 768px) {
            .navbar-custom .navbar-collapse {
                display: none;
            }
            .navbar-custom .navbar-toggler {
                display: block;
            }
            .navbar-custom.navbar-responsive .navbar-collapse {
                display: block;
            }
        }

      /* Style the search bar to make it larger */
#searchInput {
    width: 300px;  /* Increased width */
    font-size: 1.2rem;  /* Larger font size */
    padding: 10px;  /* More padding */
    border-radius: 50px; /* Rounded corners */
}

.input-group {
    display: flex;
    justify-content: center;  /* Center the search bar */
    align-items: center;
}

.input-group .btn {
    border-radius: 50px;  /* Rounded button */
    background-color: #ffcc00;  /* Yellow background for the button */
    color: white;
    border: none;
}

.input-group .btn:hover {
    background-color: #ff9900;  /* Darker yellow on hover */
}

/* Optional: on focus, the search bar expands */
#searchInput:focus {
    width: 350px;
    transition: width 0.3s ease;
}

    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-custom" id="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MyWebsite</a>
            <button class="navbar-toggler" type="button" onclick="toggleNavbar()" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
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

                    <?php if ($_SESSION['loggedin']): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#eventCreateModal">Create Event</a>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Centered Search Bar -->
                <div class="navbar-nav mx-auto">
                    <li class="nav-item d-flex align-items-center justify-content-center">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Search...">
                            <button class="btn btn-outline-secondary" type="button" onclick="searchFunction()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </li>
                </div>

                <!-- Right-aligned links -->
                <ul class="navbar-nav ms-auto">
                    <?php if ($_SESSION['loggedin']): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="user_dashboard.php">Dashboard</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>


<!-- Add your login and register modals here (same as in your original code) -->
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" method="POST" action="login.php">
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
                <form id="registerForm" method="POST" action="register.php">
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


<!-- Event Create Modal (with Multiple Images) -->
<div class="modal fade" id="eventCreateModal" tabindex="-1" aria-labelledby="eventCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventCreateModalLabel">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventCreateForm" method="POST" action="create_event.php" enctype="multipart/form-data">
                    <!-- Hidden user ID input -->
                    <input type="hidden" name="userId" value="<?php echo $_SESSION['user_id']; ?>">

                    <div class="mb-3">
                        <label for="eventName" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="eventName" name="eventName" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Event Description</label>
                        <textarea class="form-control" id="eventDescription" name="eventDescription" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventLocation" class="form-label">Event Location</label>
                        <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventCapacity" class="form-label">Event Capacity</label>
                        <input type="number" class="form-control" id="eventCapacity" name="eventCapacity" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="eventImages" class="form-label">Event Images</label>
                        <input type="file" class="form-control" id="eventImages" name="eventImages[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Event</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
    // Function to toggle the navbar on mobile view
    function toggleNavbar() {
        var navbar = document.getElementById("navbar");
        navbar.classList.toggle("navbar-responsive");
    }

    // Toggle search bar visibility
    function toggleSearchBar() {
        var searchInput = document.getElementById("searchInput");
        searchInput.classList.toggle("show");
    }
</script>
</body>
</html>
