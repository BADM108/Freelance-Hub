<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'connection.php'; // must create $conn as mysqli

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php'); // change if your form page has a different name
    exit;
}

/* --------- Collect & sanitise input (server-side) --------- */
$firstname        = trim($_POST['fname'] ?? '');
$lastname         = trim($_POST['lname'] ?? '');
$email            = trim($_POST['email'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm-password'] ?? '';
$age              = isset($_POST['age']) ? (int) $_POST['age'] : 0;
$country          = trim($_POST['country'] ?? '');
$role = strtolower(trim($_POST['role'] ?? ''));




/* --------- Basic server-side validation --------- */
$allowed_roles = ['freelancer', 'poster'];
$role = strtolower(trim($_POST['role'] ?? ''));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'Invalid email address.';
    header('Location: signup.php');
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['register_error'] = 'Password must be at least 8 characters long.';
    header('Location: signup.php');
    exit;
}
if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'Passwords do not match.';
    header('Location: signup.php');
    exit;
}

/* --------- Check for existing email (prepared stmt) --------- */
$checkSql = "SELECT uid FROM freelancerr WHERE email = ? LIMIT 1";
if ($stmt = $conn->prepare($checkSql)) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered.';
        $stmt->close();
        header('Location: signup.php');
        exit;
    }
    $stmt->close();
} else {
    // Prepare failed
    $_SESSION['register_error'] = 'Database error: ' . $conn->error;
    header('Location: signup.php');
    exit;
}

/* --------- Insert new user --------- */
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$insertSql = "INSERT INTO freelancerr (firstname, lastname, email, age, password, country, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
if ($stmt = $conn->prepare($insertSql)) {
    $stmt->bind_param("sssisss", $firstname, $lastname, $email, $age, $passwordHash, $country, $role);

    if ($stmt->execute()) {
        $_SESSION['register_success'] = 'Registration successful. Please sign in.';
        $stmt->close();
        header('Location: signin.php');
        exit;
    } else {
        $_SESSION['register_error'] = 'Registration failed: ' . $stmt->error;
        $stmt->close();
        header('Location: signup.php');
        exit;
    }
} else {
    $_SESSION['register_error'] = 'Database error: ' . $conn->error;
    header('Location: signup.php');
    exit;
}
