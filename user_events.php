<?php
session_start();  // Start the session to access session variables

// Include database connection
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user role from the database
$queryRole = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($queryRole);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Fetch events based on user role
if ($role === 'admin') {
    // Admin can see all events
    $query = "SELECT * FROM events";
    $stmt = $conn->prepare($query);
} else {
    // Regular users see only their events
    $query = "SELECT * FROM events WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);

// Delete an event (admin can delete any, users can delete only their own)
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];

    if ($role === 'admin') {
        $deleteQuery = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('i', $event_id);
    } else {
        $deleteQuery = "DELETE FROM events WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('ii', $event_id, $user_id);
    }

    $stmt->execute();
    header("Location: user_events.php");
    exit();
}

// Edit an event (users can edit only their own events)
if (isset($_POST['edit'])) {
    $event_id = $_POST['event_id'];
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_capacity = $_POST['event_capacity'];

    if ($role === 'admin') {
        $updateQuery = "UPDATE events SET event_name = ?, event_description = ?, event_date = ?, event_location = ?, event_capacity = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssii", $event_name, $event_description, $event_date, $event_location, $event_capacity, $event_id);
    } else {
        $updateQuery = "UPDATE events SET event_name = ?, event_description = ?, event_date = ?, event_location = ?, event_capacity = ? WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssiii", $event_name, $event_description, $event_date, $event_location, $event_capacity, $event_id, $user_id);
    }

    $stmt->execute();
    header("Location: user_events.php");
    exit();
}

// Fetch existing images for event
function fetchEventImages($event_id) {
    global $conn;
    $query = "SELECT event_images FROM events WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $stmt->bind_result($event_images);
    $stmt->fetch();
    $stmt->close();
    return unserialize($event_images);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Your Events</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Description</th>
                <th>Event Date</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= $event['id']; ?></td>
                <td><?= $event['event_name']; ?></td>
                <td class="text-truncate" style="max-width: 150px;"><?= $event['event_description']; ?></td>
                <td><?= $event['event_date']; ?></td>
                <td class="text-truncate" style="max-width: 150px;"><?= $event['event_location']; ?></td>
                <td><?= $event['event_capacity']; ?></td>
                <!-- Display Images -->
                        <td>
                            <?php
                            $existingImages = fetchEventImages($event['id']);
                            if (!empty($existingImages)) {
                                // Show the first image (or you can decide to display more)
                                echo "<img src='{$existingImages[0]}' class='img-thumbnail' width='100' height='100' alt='Event Image'>";
                            } else {
                                echo "No images uploaded.";
                            }
                            ?>
                        </td>
                        <td>
                            <!-- Edit button -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editModal<?= $event['id']; ?>">Edit</button>
                            <!-- Delete button -->
                            <a href="user_events.php?delete=<?= $event['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Event Modal -->
                    <div class="modal fade" id="editModal<?= $event['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Event</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="event_id" value="<?= $event['id']; ?>">
                                        <div class="form-group">
                                            <label for="event_name">Event Name</label>
                                            <input type="text" class="form-control" id="event_name" name="event_name" value="<?= $event['event_name']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="event_description">Event Description</label>
                                            <textarea class="form-control" id="event_description" name="event_description" required><?= $event['event_description']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="event_date">Event Date</label>
                                            <input type="date" class="form-control" id="event_date" name="event_date" value="<?= $event['event_date']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="event_location">Event Location</label>
                                            <input type="text" class="form-control" id="event_location" name="event_location" value="<?= $event['event_location']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="event_capacity">Event Capacity</label>
                                            <input type="number" class="form-control" id="event_capacity" name="event_capacity" value="<?= $event['event_capacity']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="event_images">Upload Images</label>
                                            <input type="file" class="form-control" id="event_images" name="event_images[]" multiple>
                                            <div class="mt-2">
                                                <h5>Existing Images:</h5>
                                                <?php
                                                $existingImages = fetchEventImages($event['id']);
                                                if (!empty($existingImages)) {
                                                    foreach ($existingImages as $image) {
                                                        echo "<img src='$image' class='img-thumbnail' width='100' height='100'>";
                                                    }
                                                } else {
                                                    echo "No images uploaded.";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No events found for your account.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
