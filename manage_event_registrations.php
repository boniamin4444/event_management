<?php
session_start();
// Include your database connection
include('db.php');

// Check if the user is logged in and has an ID (adjust the session variable accordingly)
if (!isset($_SESSION['user_id'])) {
    // If no user is logged in, redirect them to login page
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch only the events created by the logged-in user
$eventsSql = "SELECT * FROM events WHERE user_id = ?"; // Assuming 'user_id' is the column for the creator
$eventsStmt = $conn->prepare($eventsSql);
$eventsStmt->bind_param("i", $user_id);
$eventsStmt->execute();
$eventsResult = $eventsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event Registrations</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJXXY1fXjuWjR8KckF6ks4eZ6Jjsj50sVwZp6O6Mw6Q8o2qD4o0I5lFZnsz5" crossorigin="anonymous">
    <!-- Custom CSS for table styling -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        table {
            font-size: 16px;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-bordered {
            border: 2px solid #dee2e6;
        }

        .table-bordered th, .table-bordered td {
            border: 2px solid #dee2e6;
            padding: 15px;
            text-align: left;
        }

        .table th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body class="container py-5">

    <h2 class="mb-4">Manage Event Registrations</h2>
    
    <?php
    // Loop through each event
    while ($event = $eventsResult->fetch_assoc()) {
        $event_id = $event['id'];
        echo "<h3 class='mb-3'>Event Name:  " . htmlspecialchars($event['event_name']) . "</h3>";


        // Fetch users registered for this event
        $sql = "SELECT * FROM attend_event WHERE event_id = ? ORDER BY status ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are registered users for this event
        if ($result->num_rows > 0) {
            // Add download button for CSV export above the table
            echo "<form method='POST' action='download_csv.php' class='mb-3'>
                    <input type='hidden' name='event_id' value='" . $event_id . "'>
                    <button type='submit' class='btn btn-primary'>Download CSV</button>
                  </form>";

            // Table with Bootstrap styling
            echo "<div class='table-responsive'>
                    <table class='table table-bordered table-hover'>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['full_name']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                        <td>" . htmlspecialchars($row['phone']) . "</td>
                        <td>" . htmlspecialchars($row['address']) . "</td>
                        <td>" . htmlspecialchars($row['message']) . "</td>
                        <td>" . htmlspecialchars($row['status']) . "</td>
                        <td>
                            <form method='POST' action='change_status.php'>
                                <input type='hidden' name='registration_id' value='" . $row['user_id'] . "'>
                                <input type='hidden' name='event_id' value='" . $event_id . "'>
                                <button type='submit' class='btn btn-success' name='change_status' value='confirm'>Confirm</button>
                            </form>
                        </td>
                    </tr>";
            }

            echo "</tbody>
                </table>
              </div>";

        } else {
            echo "<p>No registrations yet for this event.</p>";
        }

        // Close the prepared statement
        $stmt->close();
    }
    ?>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6E1k7tIj8FwJXjQoF5a4N/5+zFxh5F6O1jwPIr+72Te+ZpZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0UJbX8IuMpe98kKzT0zxL37i4H9DPOpsVSO5YJ3p2HXZ/p+M" crossorigin="anonymous"></script>

</body>
</html>

<?php
$conn->close();
?>
