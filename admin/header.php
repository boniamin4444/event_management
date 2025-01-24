<?php
// Start PHP session if needed (for advanced purposes like session-based toggles)
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Sidebar with Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for sidebar and submenus */
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: white;
            transition: left 0.3s;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar .menu-item {
            padding: 15px 20px;
            cursor: pointer;
        }

        .sidebar .menu-item:hover {
            background-color: #495057;
        }

        .submenu {
            display: none;
            list-style-type: none;
            padding-left: 20px;
        }

        .submenu li {
            padding: 10px 20px;
            cursor: pointer;
        }

        .submenu li:hover {
            background-color: #6c757d;
        }

        .submenu-toggle::after {
            content: " ▼";
        }

        .submenu-toggle.open::after {
            content: " ▲";
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="d-flex justify-content-between p-3 bg-dark text-white">
        <span class="h4">My Website</span>
        <button class="btn btn-outline-light" onclick="toggleSidebar()">Toggle Sidebar</button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <ul class="list-unstyled">
            <li>
                <div class="menu-item submenu-toggle" onclick="toggleSubMenu('submenu1')">Menu 1</div>
                <ul id="submenu1" class="submenu">
                    <li>Submenu 1.1</li>
                    <li>Submenu 1.2</li>
                </ul>
            </li>
            <li>
                <div class="menu-item submenu-toggle" onclick="toggleSubMenu('submenu2')">Menu 2</div>
                <ul id="submenu2" class="submenu">
                    <li>Submenu 2.1</li>
                    <li>Submenu 2.2</li>
                </ul>
            </li>
            <li>
                <div class="menu-item submenu-toggle" onclick="toggleSubMenu('submenu3')">Menu 3</div>
                <ul id="submenu3" class="submenu">
                    <li>Submenu 3.1</li>
                    <li>Submenu 3.2</li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Content area -->
    <div class="container mt-4" style="margin-left: 250px;">
        <h2>Welcome to the Website</h2>
        <p>Click on the "Toggle Sidebar" button to see the sidebar in action!</p>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Toggle submenu visibility
        function toggleSubMenu(submenuId) {
            const submenu = document.getElementById(submenuId);
            submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            const submenuToggle = submenu.previousElementSibling;
            submenuToggle.classList.toggle('open');
        }
    </script>

</body>
</html>
