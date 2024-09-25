<?php
include 'db.php';

$id = $_GET['id'];

// Delete the event from the database
$stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$id]);

// Redirect to index page with success message
header("Location: index.php");
?>
