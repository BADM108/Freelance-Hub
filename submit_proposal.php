<?php
session_start();
require_once 'connection.php';
//DIDNT WORK OUT AS INTENDED

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: signin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'];
    $freelancer_id = $_SESSION['user_id'];
    $proposal_message = trim($_POST['proposal_message']);

    $sql = "INSERT INTO proposals (project_id, freelancer_id, proposal_message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $project_id, $freelancer_id, $proposal_message);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Proposal submitted successfully!';
        header('Location: freelancer_dashboard.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to submit proposal: ' . $stmt->error;
    }
    
    $stmt->close();
}

// Get project details
$project_id = $_GET['project_id'];
$project_sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($project_sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$project_result = $stmt->get_result();
$project = $project_result->fetch_assoc();
$stmt->close();
?>

<!-- HTML form for submitting proposals -->