<?php header("Access-Control-Allow-Origin: *");  // Allow all domains
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  // Allow specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");  // Allow specific headers
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Latest stable Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest stable Bootstrap 5 JS with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <title>Document</title>
</head>
<body>
    
<?php include('header.php'); ?>

<div class="container mt-5" style="min-height:500px;">
    <!-- Your content goes here -->
 </div>

<?php include('footer.php'); ?>

</body>
</html>
