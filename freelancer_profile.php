<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
    header('Location: signin.php');
    exit;
}
//security measure to ensure only logged in users can access this page
if (!isset($_GET['id'])) {
    header('Location: available_freelancers.php');
    exit;
}

$freelancer_id = $_GET['id'];

// Get freelancer details
$freelancer_sql = "SELECT f.*, 
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as review_count,
                   COUNT(DISTINCT h.id) as completed_projects
                   FROM freelancerr f
                   LEFT JOIN reviews r ON f.uid = r.freelancer_id
                   LEFT JOIN hires h ON f.uid = h.freelancer_id AND h.status = 'completed'
                   WHERE f.uid = ? AND f.role = 'freelancer'";
$stmt = $conn->prepare($freelancer_sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$freelancer_result = $stmt->get_result();
$freelancer = $freelancer_result->fetch_assoc();
$stmt->close();

if (!$freelancer) {
    header('Location: available_freelancers.php');
    exit;
}

// Get freelancer skills
$skills_sql = "SELECT s.skill_name, s.description 
               FROM freelancer_skills fs 
               JOIN skills s ON fs.skill_id = s.id 
               WHERE fs.freelancer_id = ?";
$stmt = $conn->prepare($skills_sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$skills_result = $stmt->get_result();
$skills = [];
while ($row = $skills_result->fetch_assoc()) {
    $skills[] = $row;
}
$stmt->close();

// Get freelancer portfolio/projects
$portfolio_sql = "SELECT p.title, p.description, p.fixed_price, p.created_at 
                  FROM projects p 
                  JOIN hires h ON p.id = h.project_id 
                  WHERE h.freelancer_id = ? AND h.status = 'completed' 
                  ORDER BY p.created_at DESC 
                  LIMIT 5";
$stmt = $conn->prepare($portfolio_sql);
$stmt->bind_param("i", $freelancer_id);
$stmt->execute();
$portfolio_result = $stmt->get_result();
$portfolio = [];
while ($row = $portfolio_result->fetch_assoc()) {
    $portfolio[] = $row;
}
$stmt->close();
?>

