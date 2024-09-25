<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $description = $_POST['description'];

    // Validate that the end datetime is after the start datetime
    if (strtotime($end_datetime) < strtotime($start_datetime)) {
        echo "End datetime must be after start datetime.";
        exit;
    }

    // Insert the event into the database
    $stmt = $pdo->prepare("INSERT INTO events (title, start_datetime, end_datetime, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $start_datetime, $end_datetime, $description]);

    // Redirect to index page
    header("Location: index.php?success=true");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Add Event</title>
</head>
<body>
<div class="container">
    <h2>Add Event</h2>
    <form method="POST">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="start_datetime">Start Date and Time</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
        </div>
        <div class="form-group">
            <label for="end_datetime">End Date and Time</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>
</body>
</html>
