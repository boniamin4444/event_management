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



$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query to search for events
$sql = "SELECT * FROM events WHERE event_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchTerm . "%"; // Add wildcards for partial matching
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch event data
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Get the current attendees count for this event
        $event_id = $row['id'];

        $attendees_sql = "SELECT COUNT(*) FROM attend_event WHERE event_id = ?";
        $att_stmt = $conn->prepare($attendees_sql);
        $att_stmt->bind_param("i", $event_id);
        $att_stmt->execute();
        $att_stmt->bind_result($attendees_count);
        $att_stmt->fetch();

        $row['attendees_count'] = $attendees_count;
        $events[] = $row;

        $att_stmt->close();
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
    display: flex; /* Use flexbox for the layout */
    flex-wrap: wrap; /* Allow cards to wrap to the next line */
    justify-content: center; /* Center the cards horizontally */
    gap: 30px; /* Space between cards */
}
        .event-card {
            display: grid;
            grid-template-columns: 1fr 2fr; /* Two columns: one for images, one for text */
            gap: 30px;
            border: 1px solid #ddd;
            border-radius: 15px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            padding: 20px;
            margin-bottom: 20px;
        }
        .event-card:hover {
            transform: translateY(-10px);
        }
        .event-card-img-container {
            position: relative;
        }
        .image-count {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 1rem;
        }
        .event-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .event-card-details {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .event-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
        }
        .event-description {
            font-size: 1rem;
            color: #666;
            flex-grow: 1;
        }
        .event-date, .event-location, .event-capacity {
            font-size: 1rem;
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
        .carousel-inner img {
            object-fit: cover;
            max-height: 500px; /* Set maximum height for images in the carousel */
        }

        /* Custom style for carousel buttons */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: skyblue !important; /* Set button background to yellow */
            border-radius: 50%; /* Make the buttons round */
            width: 40px; /* Adjust size if needed */
            height: 40px; /* Adjust size if needed */
        }

        /* Optional: Change button hover effect to make it even more visible */
        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: darkorange !important; /* Change to dark orange on hover */
        }

        .event-name, .event-description, .event-date, .event-location, .event-capacity {
    font-weight: bold;
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
                    <?php if (count($eventImages) > 0): ?>
                        <!-- Show the first image -->
                         <div>
                        <img src="<?= $eventImages[0] ?>" alt="Event Image" class="event-card-img" data-bs-toggle="modal" data-bs-target="#imageModal-<?= $event['id'] ?>">
                        <!-- Show the number of remaining images -->
                        <?php if (count($eventImages) > 1): ?>
                            <span class="image-count">+<?= count($eventImages) - 1 ?> more pic</span>
                        <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Event Details -->
                <div class="event-card-details">
                <h5 class="event-name"><strong>Event Name: <?= htmlspecialchars($event['event_name']) ?></strong></h5>
                <p class="event-description"><strong>Description: <?= htmlspecialchars($event['event_description']) ?></strong></p>
               <p class="event-date"><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
               <p class="event-location"><strong>Location:</strong> <?= htmlspecialchars($event['event_location']) ?></p>
               <p class="event-capacity"><strong>Capacity:</strong> <?= htmlspecialchars($eventCapacity) ?></p>


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

             <!-- Modal for displaying all images -->
             <div class="modal fade" id="imageModal-<?= $event['id'] ?>" tabindex="-1" aria-labelledby="imageModalLabel-<?= $event['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel-<?= $event['id'] ?>">Event Images</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Carousel for images -->
                            <div id="carouselExample-<?= $event['id'] ?>" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($eventImages as $index => $image): ?>
                                        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                            <img src="<?= $image ?>" class="d-block w-100" alt="Event Image">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample-<?= $event['id'] ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample-<?= $event['id'] ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
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
                    <input type="hidden" id="event_id" name="event_id" value="<? $event_id ?>"/>
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
