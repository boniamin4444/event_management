<?php
session_start();
// Check if the user is logged in (if necessary)
if (!isset($_SESSION['user_id'])) {
    // If no user is logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <!-- Include Bootstrap 4.6 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>

<div class="container py-5">

    <h2 class="mb-4 text-center">Create Event</h2>

    <!-- Event Create Form -->
    <form id="eventCreateForm" method="POST" action="create_event.php" enctype="multipart/form-data" class="bg-light p-4 rounded shadow-sm">
        <!-- Hidden user ID input -->
        <input type="hidden" name="userId" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="form-group mb-3">
            <label for="eventName">Event Name</label>
            <input type="text" class="form-control" id="eventName" name="eventName" required>
        </div>

        <div class="form-group mb-3">
            <label for="eventDescription">Event Description</label>
            <textarea class="form-control" id="eventDescription" name="eventDescription" rows="4" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="eventDate">Event Date</label>
            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
        </div>

        <div class="form-group mb-3">
            <label for="eventLocation">Event Location</label>
            <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
        </div>

        <div class="form-group mb-3">
            <label for="eventCapacity">Event Capacity</label>
            <input type="number" class="form-control" id="eventCapacity" name="eventCapacity" required min="1">
        </div>

        <div class="form-group mb-3">
            <label for="eventImages">Event Images</label>
            <input type="file" class="form-control-file" id="eventImages" name="eventImages[]" accept="image/*" multiple required>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Event</button>
        </div>
    </form>

</div>

<!-- Include Bootstrap 4.6 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
