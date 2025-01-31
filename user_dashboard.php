<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit();
}

include 'db.php';


$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && $_SESSION['role'] === 'admin';


?>

<script>
    document.addEventListener('contextmenu', function (e) {
        e.preventDefault();  // Prevent the default right-click menu
        alert('If You Want To Know Anything? Please Contact With The Developer.(+8801793280228 Or +8801822860232).');  // Show the alert message
    });
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            width: 250px;
            padding-top: 20px;
            background-color: #343a40;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 10px 30px;
            font-size: 1rem;
        }

        .sidebar .nav-link:hover {
            background-color: #454d55;
        }

        .sidebar .nav-item .submenu {
            display: none;
            background-color: #495057;
            padding-left: 40px;
        }

        .sidebar .nav-item.active .submenu {
            display: block;
        }

        .sidebar .nav-item:hover .submenu {
            display: block;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 56px;
            background-color: #343a40;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .sidebar .brand img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 50%;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            background-color: #343a40;
            color: #fff;
            padding: 10px 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px 20px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .iframe-container {
            position: relative;
            overflow: hidden;
            padding-top: 56.25%; 
        }

        .iframe-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none; 
        }

        .search-container {
            position: relative;
            margin-left: 20px;
        }

        .autocomplete-results {
            position: absolute;
            background-color: #f9f9f9;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            z-index: 1;
        }

        .notification-container {
            position: relative;
        }

        .notification-container .badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }

        /* Mobile view toggle button */
        .sidebar-toggle {
            display: none;
            background-color: #343a40;
            color: #fff;
            border: none;
            padding: 10px;
            font-size: 18px;
            cursor: pointer;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <h2>Lence Cafe</h2>
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button> <!-- Toggle button -->
            <div class="notification-container">
            <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </li>
            </div>
        </div>
    </div>
</header>

<!-- Sidebar -->
<div class="sidebar">
    <div class="brand">
        <img src="path_to_your_logo_image.png" alt="Event management Logo">
        Event Management
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="index.php" ><i class="fas fa-home mr-2"></i>Go Back / Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#videosSubMenu" aria-expanded="false" aria-controls="videosSubMenu">
                <i class="fas fa-video mr-2"></i>Events <i class="fas fa-caret-down"></i>
            </a>
            <div class="submenu collapse" id="videosSubMenu">
                <a class="nav-link" href="javascript:void(0);" onclick="loadPage('user_events.php');"><i class="fas fa-list mr-2"></i>Show All Events</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#addTeacherSubMenu" aria-expanded="false" aria-controls="addTeacherSubMenu">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Joining list <i class="fas fa-caret-down"></i>
            </a>
            <div class="submenu collapse" id="addTeacherSubMenu">
                <a class="nav-link" href="javascript:void(0);" onclick="loadPage('manage_event_registrations.php');"><i class="fas fa-eye mr-2"></i>View All Join list</a>
                <a class="nav-link" href="javascript:void(0);" onclick="loadPage('create_eventFromDashboard.php');"><i class="fas fa-chalkboard-teacher mr-2"></i>Add New Event</a>
            </div>
        </li>
       
        <li class="nav-item">
    <?php if ($isAdmin): ?>
        <a class="nav-link" data-toggle="collapse" href="#adminUsersSubMenu" aria-expanded="false" aria-controls="adminUsersSubMenu">
            <i class="fas fa-users-cog mr-2"></i>Admin & Users <i class="fas fa-caret-down"></i>
        </a>
        <div class="submenu collapse" id="adminUsersSubMenu">
            <a class="nav-link" href="javascript:void(0);" onclick="loadPage('all_users.php');"><i class="fas fa-list mr-2"></i>Show All Admin & Users</a>
        </div>
    <?php endif; ?>
</li>


        <li class="nav-item">
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </li>
    </ul>
</div>

<!-- Main content area with iFrame -->
<div class="main-content">
    <div class="iframe-container">
        <iframe class="iframe-content" id="iframeContent" src="home.php" frameborder="0"></iframe>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        &copy; 2025 Your Company. All rights reserved by Event Management.
    </div>
</footer>

<!-- JavaScript libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script>
    function loadPage(page) {
        document.getElementById('iframeContent').src = page;
    }

    // Sidebar toggle for mobile view
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('open');
    }
</script>
</body>
</html>
