<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user role
$roleQuery = "SELECT role FROM users WHERE id = ?";
$roleStmt = $conn->prepare($roleQuery);
$roleStmt->bind_param("i", $user_id);
$roleStmt->execute();
$roleStmt->bind_result($role);
$roleStmt->fetch();
$roleStmt->close();

// Initialize variables
$totalUsers = 0;
$totalEvents = 0;
$totalAttendEvents = 0;
$totalRegistrations = 0;
$totalPending = 0;
$totalConfirmed = 0;

// If admin, fetch overall stats
if ($role === 'admin') {
    $totalUsers = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
    $totalEvents = $conn->query("SELECT COUNT(*) FROM events")->fetch_row()[0];
    $totalAttendEvents = $conn->query("SELECT COUNT(*) FROM attend_event")->fetch_row()[0];
    $totalPending = $conn->query("SELECT COUNT(*) FROM attend_event WHERE status = 'pending'")->fetch_row()[0];
    $totalConfirmed = $conn->query("SELECT COUNT(*) FROM attend_event WHERE status = 'confirmed'")->fetch_row()[0];
} else {
    // If regular user, fetch only their stats
    $totalEvents = $conn->query("SELECT COUNT(*) FROM events WHERE user_id = $user_id")->fetch_row()[0];
    $totalAttendEvents = $conn->query("SELECT COUNT(*) FROM attend_event WHERE user_id = $user_id")->fetch_row()[0];

    // Get event-wise registrations, pending & confirmed counts
    $eventStatsQuery = "
        SELECT e.id, e.event_name, 
               (SELECT COUNT(*) FROM attend_event a WHERE a.event_id = e.id) AS totalRegistrations,
               (SELECT COUNT(*) FROM attend_event a WHERE a.event_id = e.id AND a.status = 'pending') AS totalPending,
               (SELECT COUNT(*) FROM attend_event a WHERE a.event_id = e.id AND a.status = 'confirmed') AS totalConfirmed
        FROM events e
        WHERE e.user_id = $user_id
    ";
    $eventStatsResult = $conn->query($eventStatsQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h2>Dashboard</h2>

<div class="row">
    <?php if ($role === 'admin'): ?>
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center p-3">
                <h4>Total Users</h4>
                <h2><?= $totalUsers ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center p-3">
                <h4>Total Events</h4>
                <h2><?= $totalEvents ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white text-center p-3">
                <h4>Total Registrations</h4>
                <h2><?= $totalAttendEvents ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-3">
                <h4>Confirmed Registrations</h4>
                <h2><?= $totalConfirmed ?></h2>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card bg-danger text-white text-center p-3">
                <h4>Pending Registrations</h4>
                <h2><?= $totalPending ?></h2>
            </div>
        </div>

    <?php else: ?>
        <div class="col-md-4">
            <div class="card bg-primary text-white text-center p-3">
                <h4>Your Events</h4>
                <h2><?= $totalEvents ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white text-center p-3">
                <h4>Total Attend Events</h4>
                <h2><?= $totalAttendEvents ?></h2>
            </div>
        </div>

        <?php while ($row = $eventStatsResult->fetch_assoc()): ?>
            <div class="col-md-4 mt-3">
                <div class="card p-3">
                    <h5 class="text-center">Event Name: <?= htmlspecialchars($row['event_name']) ?></h5>
                    <p>Total Registrations: <strong><?= $row['totalRegistrations'] ?></strong></p>
                    <p>Pending: <strong class="text-warning"><?= $row['totalPending'] ?></strong></p>
                    <p>Confirmed: <strong class="text-success"><?= $row['totalConfirmed'] ?></strong></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
