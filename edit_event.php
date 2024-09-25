<?php
include 'db.php';

$id = $_GET['id'];

// Fetch the event to be edited
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $description = $_POST['description'];

    // Validate the end date
    if (strtotime($end_datetime) < strtotime($start_datetime)) {
        echo "End datetime must be after start datetime.";
        exit;
    }

    // Update the event in the database
    $stmt = $pdo->prepare("UPDATE events SET title = ?, start_datetime = ?, end_datetime = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $start_datetime, $end_datetime, $description, $id]);

    // Redirect to index page
    header("Location: index.php?success=updated");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit Event</title>
</head>
<body>
<div class="container">
    <h2>Edit Event</h2>
    <form method="POST">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>
        <div class="form-group">
            <label for="start_datetime">Start Date and Time</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_datetime'])); ?>" required>
        </div>
        <div class="form-group">
            <label for="end_datetime">End Date and Time</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($event['end_datetime'])); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>
</body>
</html>
