<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Sidebar with Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Hamburger icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #343a40;
            color: white;
            z-index: 1000;
            padding-top: 10px;
            overflow-y: auto;
            display: block;
            transform: translateX(-100%); /* Hide sidebar initially */
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.active {
            transform: translateX(0); /* Show sidebar when active class is added */
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

        .content-area {
            margin-left: 250px;
            padding-top: 20px;
            transition: margin-left 0.3s;
        }

        .toggle-btn {
            font-size: 30px;
            cursor: pointer;
            color: white;
            margin-left: 20px;
        }

        .header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px 20px;
            background-color: #343a40;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
        }

        .container {
            margin-top: 60px;
        }

        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                top: 60px;
                left: 0;
                width: 250px;
                height: calc(100vh - 60px);
                background-color: #343a40;
                color: white;
                z-index: 1000;
                padding-top: 10px;
                overflow-y: auto;
                display: block;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .toggle-btn {
                display: block;
            }

            .content-area {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Header with Hamburger Icon on Left -->
    <div class="header">
    <span class="h4">My Website</span>
    <span class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </span>
    <a href="../logout.php" class="btn btn-link text-white ms-auto" id="logoutBtn">
        <i class="fa fa-sign-out"></i> Logout
    </a>
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
    <div id="content" class="content-area container">
        <h2>Welcome to the Website</h2>
        <p>Click on the "Toggle Sidebar" button to see the sidebar in action!</p>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar visibility on mobile and desktop
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
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
