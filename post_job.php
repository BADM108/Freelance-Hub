<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
    header('Location: signin.php');
    exit;
}
//security measure to ensure only logged in users can access this page
$poster_id = $_SESSION['user_id'];
$title = trim($_POST['jobTitle']);
$description = trim($_POST['jobDescription']);
$fixed_price = floatval($_POST['fixed_price']);
$deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

// Insert project
$sql = "INSERT INTO projects (poster_id, title, description, fixed_price, deadline) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issds", $poster_id, $title, $description, $fixed_price, $deadline);

// Execute and check for success
if ($stmt->execute()) {
    $_SESSION['success'] = 'Job posted successfully!';
} else {
    $_SESSION['error'] = 'Failed to post job: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header('Location: jobposterdashboard.php');
exit;
?>