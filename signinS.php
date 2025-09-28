<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'connection.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signin.php');
    exit;
}

/* --------- Collect input --------- */
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

/* --------- Basic validation --------- */
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Invalid email address.';
    header('Location: signin.php');
    exit;
}

if (empty($password)) {
    $_SESSION['login_error'] = 'Password is required.';
    header('Location: signin.php');
    exit;
}

/* --------- Check user credentials --------- */
$sql = "SELECT uid, firstname, lastname, email, password, role FROM freelancerr WHERE email = ? LIMIT 1";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($uid, $firstname, $lastname, $db_email, $passwordHash, $role);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $passwordHash)) {
            // Login successful - set session variables
            $_SESSION['user_id'] = $uid;
            $_SESSION['firstname'] = $firstname;
            $_SESSION['lastname'] = $lastname;
            $_SESSION['email'] = $db_email;
            $_SESSION['role'] = $role;
            $_SESSION['loggedin'] = true;
            
            $_SESSION['login_success'] = 'Login successful!';
            
            // Redirect based on role or to dashboard
            if ($role === 'freelancer') {
                header('Location: freelanceHubfreelancerDashboard.php');
            } else if ($role === 'poster') {
                header('Location: jobposterDashboard.php');
            } else {
                header('Location: freelanceHubHome.php');
            }
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
            header('Location: signin.php');
            exit;
        }
    } else {
        $_SESSION['login_error'] = 'Invalid email or password.';
        header('Location: signin.php');
        exit;
    }
    $stmt->close();
} else {
    $_SESSION['login_error'] = 'Database error: ' . $conn->error;
    header('Location: signin.php');
    exit;
}

$conn->close();
?>