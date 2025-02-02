<?php
session_start();
error_reporting(0);
include('db.php');

// Get event ID from URL
$event_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($event_id == 0) {
    echo "Event not found.";
    exit;
}

// Fetch the event details from the database
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

// If event not found
if (!$event) {
    echo "Event not found.";
    exit;
}

// Get event images
$eventImages = !empty($event['event_images']) ? unserialize($event['event_images']) : [];
$attendees_sql = "SELECT COUNT(*) FROM attend_event WHERE event_id = ?";
$att_stmt = $conn->prepare($attendees_sql);
$att_stmt->bind_param("i", $event_id);
$att_stmt->execute();
$att_stmt->bind_result($attendees_count);
$att_stmt->fetch();
$att_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Event Details</title>
</head>
<style>
    /* Set minimum height for the entire page */
    .container {
        height: 100%;
        min-height: 700px; /* Ensure the page has at least 600px height */
    }

    .carousel-control-prev-icon, .carousel-control-next-icon {
        filter: invert(1); /* Inverts icon color to white */
    }

    /* Slight highlight effect when hovered */
    .carousel-control-prev, .carousel-control-next {
        position: relative;
    }
    .carousel-control-prev::after, .carousel-control-next::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 3px;
        background-color: rgba(0, 123, 255, 0.3); /* Light blue underline */
        border-radius: 2px;
    }
</style>


<body>
<?php include('header.php'); ?>

<div class="container mt-5">
    <h2 class="text-center"><?= htmlspecialchars($event['event_name']) ?></h2>

    <div class="row">
        <div class="col-md-6">
            <h4>Description:</h4>
            <p><?= nl2br(htmlspecialchars($event['event_description'])) ?></p>

            <h4>Location:</h4>
            <p><?= htmlspecialchars($event['event_location']) ?></p>

            <h4>Event Date:</h4>
            <p><?= htmlspecialchars($event['event_date']) ?></p>

            <h4>Capacity:</h4>
            <p><?= htmlspecialchars($event['event_capacity']) ?> people</p>

            <h4>Attendees Request:</h4>
            <p><?= $attendees_count ?> people</p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#attendanceModal" 
                    data-event-id="<?= $event['id'] ?>" data-user-id="<?= $_SESSION['user_id'] ?>">Click to attend</button>
            <?php else: ?>
                <button class="btn btn-primary" onclick="alert('Please login to attend this event!')">Login to Attend</button>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h4>Images:</h4>
            <?php if (count($eventImages) > 0): ?>
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($eventImages as $index => $image): ?>
                            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                <img src="<?= $image ?>" class="d-block w-100" alt="Event Image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            <?php else: ?>
                <p>No images available for this event.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- Modal for Event Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Attend Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm" method="POST" action="attend_event.php">
                    <input type="hidden" id="event_id" name="event_id" value="<?=  $event_id ?>"/>
                    <input type="hidden" id="user_id" name="user_id" value="<?= $_SESSION['user_id']; ?>"/>

                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" name="fullName" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>
