<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is a freelancer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'freelancer') {
    header('Location: signin.php');
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT firstname, lastname FROM freelancerr WHERE uid = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
$stmt->close();

// Get available projects
$projects_sql = "SELECT * FROM projects WHERE status = 'open' ORDER BY created_at DESC";
$stmt = $conn->prepare($projects_sql);
$stmt->execute();
$projects_result = $stmt->get_result();
$projects = [];
while ($row = $projects_result->fetch_assoc()) {
    $projects[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Projects | FreelanceHub</title>
    <!-- Tailwind CSS imported link -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- this is where the animations on the scroll is taken from -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <!-- this is where the feather icons are taken from -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- this is where the google fonts are taken from -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="briefcase" class="text-indigo-600 h-6 w-6"></i>
                        <span class="ml-2 text-xl font-bold text-gray-900">FreelanceHub</span>
                        <span class="ml-2 text-sm text-gray-500">| Find Work</span>
                    </div>
                </div>
                <!-- Navigation Links -->
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="freelanceHubfreelancerDashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                    <a href="available_projects.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Find Work</a>
                    <a href="How_It_Works_Page.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">How It Works</a>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <!-- User Name -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Hello, <?php echo $user_name; ?></span>
                        <button onclick="window.location.href='freelanceHubfreelancerDashboard.php';" class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition-colors duration-200" title="Dashboard">
                            <i data-feather="user" class="h-6 w-6 text-gray-600"></i>
                        </button>
                    </div>
                    
                    <!-- Logout Button -->
                    <button onclick="window.location.href='freelanceHubHome.php';" class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Available Projects</h1>
                            <p class="mt-1 text-sm text-gray-500">Browse and apply to available projects that match your skills</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Found <span class="font-semibold text-indigo-600"><?php echo count($projects); ?></span> projects</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6">
            <?php if (empty($projects)): ?>
                <div class="bg-white shadow rounded-lg p-8 text-center">
                    <i data-feather="briefcase" class="h-12 w-12 text-gray-400 mx-auto"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No projects available</h3>
                    <p class="mt-2 text-sm text-gray-500">Check back later for new project opportunities</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        $<?php echo number_format($project['fixed_price'], 2); ?>
                                    </span>
                                </div>
                                
                                <p class="mt-2 text-sm text-gray-600"><?php echo htmlspecialchars($project['description']); ?></p>
                                
                                <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i data-feather="calendar" class="h-4 w-4 mr-1"></i>
                                        <span>Posted: <?php echo date('M j, Y', strtotime($project['created_at'])); ?></span>
                                    </div>
                                    
                                    <?php if ($project['deadline']): ?>
                                        <div class="flex items-center">
                                            <i data-feather="clock" class="h-4 w-4 mr-1"></i>
                                            <span>Deadline: <?php echo date('M j, Y', strtotime($project['deadline'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <button onclick="submitProposal(<?php echo $project['id']; ?>)" 
                                        class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                    <i data-feather="send" class="h-4 w-4 inline mr-2"></i>
                                    Submit Proposal
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <!-- JavaScript -->
    <script>
        function submitProposal(projectId) {
            // Open proposal submission page
            window.location.href = 'submit_proposal.php?project_id=' + projectId;
        }
        
        // Initialize Feather Icons
        feather.replace();

        // Initialize animation on scroll
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>