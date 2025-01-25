<?php
session_start();
error_reporting(0);
// Include your database connection
include('db.php');

// Fetch events from the database and their attendee counts
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

// Fetch event data into an array
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get the current attendees count for this event
        $event_id = $row['id'];

        // Get the number of attendees registered for this event
        $attendees_sql = "SELECT COUNT(*) FROM attend_event WHERE event_id = ?";
        $stmt = $conn->prepare($attendees_sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $stmt->bind_result($attendees_count);
        $stmt->fetch();

        // Add event data and attendees count to the array
        $row['attendees_count'] = $attendees_count;
        $events[] = $row;

        $stmt->close();
    }
} else {
    echo "No events found.";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Events</title>
    <style>
        .event-card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .event-card {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns: one for images, one for text */
            gap: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .event-card-img-container {
            width: 100%;
            height: 100%;
            overflow: hidden;
            max-width: 100%; 
        }
        .event-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .event-card-details {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .event-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .event-description {
            font-size: 1rem;
            color: #666;
            flex-grow: 1;
        }
        .event-date, .event-location, .event-capacity {
            font-size: 0.9rem;
            color: #777;
        }
        .event-message {
            color: #f44336;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%; /* Button takes full width of the parent container */
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<div class="container mt-5" style="min-height:800px;">
    <!-- Event Card Container -->
    <div class="event-card-container">
        <?php foreach ($events as $event): ?>
            <?php 
                // Get event details
                $eventImages = !empty($event['event_images']) ? unserialize($event['event_images']) : [];
                $eventCapacity = $event['event_capacity'];
                $attendeesCount = $event['attendees_count']; 
                $isFull = $attendeesCount >= $eventCapacity;
            ?>
            
            <!-- Event Card -->
            <div class="event-card">
                <!-- Event Image -->
                <div class="event-card-img-container">
                    <?php foreach ($eventImages as $image): ?>
                        <img src="<?= $image ?>" alt="Event Image" class="event-card-img">
                    <?php endforeach; ?>
                </div>
                
                <!-- Event Details -->
                <div class="event-card-details">
                    <h5 class="event-name"><?= htmlspecialchars($event['event_name']) ?></h5>
                    <p class="event-description"><?= htmlspecialchars($event['event_description']) ?></p>
                    <p class="event-date">Date: <?= htmlspecialchars($event['event_date']) ?></p>
                    <p class="event-location">Location: <?= htmlspecialchars($event['event_location']) ?></p>
                    <p class="event-capacity">Capacity: <?= htmlspecialchars($eventCapacity) ?></p>

                    <!-- Conditional Button or Message -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($isFull): ?>
                            <span class="event-message">Event is fully booked</span>
                        <?php else: ?>
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#attendanceModal" 
                                    data-event-id="<?= $event['id'] ?>" data-user-id="<?= $_SESSION['user_id'] ?>">Click to attend</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="alert('Please login to join this event!')">Please login to join this event</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
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
                    <input type="hidden" id="event_id" name="event_id" value=""/>
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

<script>
    // Set event ID and user ID dynamically when the "Click to attend" button is clicked
    const attendButtons = document.querySelectorAll('.btn-primary[data-bs-toggle="modal"]');
    attendButtons.forEach(button => {
        button.addEventListener('click', function () {
            const eventId = this.getAttribute('data-event-id');
            const userId = this.getAttribute('data-user-id');

            document.getElementById('event_id').value = eventId;
            document.getElementById('user_id').value = userId;
        });
    });
</script>

</body>
</html>
