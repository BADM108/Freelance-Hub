<?php
session_start();
require_once 'connection.php';

// THIS PAGE DIDNT WORK OUT AS INTENDED

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
    header('Location: signin.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: available_freelancers.php');
    exit;
}

$freelancer_id = $_GET['id'];
$poster_id = $_SESSION['user_id'];

// Get freelancer details
$freelancer_sql = "SELECT firstname, lastname FROM freelancerr WHERE uid = ?";
$stmt = $conn->prepare($freelancer_sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$freelancer_result = $stmt->get_result();
$freelancer = $freelancer_result->fetch_assoc();
$stmt->close();

// Get poster's projects for invitation
$projects_sql = "SELECT id, title FROM projects WHERE poster_id = ? AND status = 'open'";
$stmt = $conn->prepare($projects_sql);
$stmt->bind_param("i", $poster_id);
$stmt->execute();
$projects_result = $stmt->get_result();
$projects = [];
while ($row = $projects_result->fetch_assoc()) {
    $projects[] = $row;
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $message = trim($_POST['message']);
    
    // Insert invitation
    $invite_sql = "INSERT INTO invitations (project_id, freelancer_id, poster_id, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($invite_sql);
    $stmt->bind_param("iiis", $project_id, $freelancer_id, $poster_id, $message);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Invitation sent successfully!';
        header('Location: available_freelancers.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to send invitation: ' . $stmt->error;
    }
    $stmt->close();
}
?>

<!-- HTML form for inviting freelancer to project -->