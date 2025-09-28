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
$user_sql = "SELECT firstname, lastname, email FROM freelancerr WHERE uid = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Get freelancer's proposals count
$proposals_sql = "SELECT COUNT(*) as total_proposals FROM proposals WHERE freelancer_id = ?";
$stmt = $conn->prepare($proposals_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$proposals_result = $stmt->get_result();
$proposals_data = $proposals_result->fetch_assoc();
$total_proposals = $proposals_data['total_proposals'];
$stmt->close();

// Get accepted proposals count
$accepted_sql = "SELECT COUNT(*) as accepted_proposals FROM proposals WHERE freelancer_id = ? AND status = 'accepted'";
$stmt = $conn->prepare($accepted_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$accepted_result = $stmt->get_result();
$accepted_data = $accepted_result->fetch_assoc();
$accepted_proposals = $accepted_data['accepted_proposals'];
$stmt->close();

// Get pending proposals count
$pending_sql = "SELECT COUNT(*) as pending_proposals FROM proposals WHERE freelancer_id = ? AND status = 'pending'";
$stmt = $conn->prepare($pending_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_result = $stmt->get_result();
$pending_data = $pending_result->fetch_assoc();
$pending_proposals = $pending_data['pending_proposals'];
$stmt->close();

// Get completed projects count
$completed_sql = "SELECT COUNT(*) as completed_projects FROM hires WHERE freelancer_id = ? AND status = 'completed'";
$stmt = $conn->prepare($completed_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completed_result = $stmt->get_result();
$completed_data = $completed_result->fetch_assoc();
$completed_projects = $completed_data['completed_projects'];
$stmt->close();

// Get freelancer's recent proposals
$user_proposals_sql = "SELECT p.*, pr.title as project_title, pr.fixed_price, pr.deadline 
                      FROM proposals p 
                      JOIN projects pr ON p.project_id = pr.id 
                      WHERE p.freelancer_id = ? 
                      ORDER BY p.created_at DESC 
                      LIMIT 5";
        $stmt = $conn->prepare($user_proposals_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_proposals_result = $stmt->get_result();
        $user_proposals = [];
        while ($row = $user_proposals_result->fetch_assoc()) {
            $user_proposals[] = $row;
}
$stmt->close();

// Get recent projects available for proposals(still didnt work)
$recent_projects_sql = "SELECT * FROM projects 
                        WHERE status = 'open' 
                        ORDER BY created_at DESC 
                        LIMIT 3";
$stmt = $conn->prepare($recent_projects_sql);
$stmt->execute();
$recent_projects_result = $stmt->get_result();
$recent_projects = [];
while ($row = $recent_projects_result->fetch_assoc()) {
    $recent_projects[] = $row;
}
$stmt->close();

// Get current active projects (tried it a few times no luck)
$active_projects_sql = "SELECT h.*, pr.title, pr.description, pr.fixed_price, f.firstname as poster_firstname, f.lastname as poster_lastname
                       FROM hires h 
                       JOIN projects pr ON h.project_id = pr.id 
                       JOIN freelancerr f ON h.poster_id = f.uid 
                       WHERE h.freelancer_id = ? AND h.status = 'hired' 
                       ORDER BY h.hired_at DESC";
$stmt = $conn->prepare($active_projects_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$active_projects_result = $stmt->get_result();
$active_projects = [];
while ($row = $active_projects_result->fetch_assoc()) {
    $active_projects[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard | FreelanceHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .project-card {
            transition: all 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .active-tab {
            background-color: #4f46e5;
            color: white;
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
                        <span class="ml-2 text-sm text-gray-500">| Freelancer Dashboard</span>
                    </div>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="freelancerloggedinhome.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Home</a>
                    <a href="#" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Dashboard</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">My Projects</a>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <!-- Profile Icon -->
                    <div class="flex items-center justify-center p-2 rounded-full">
                        <i data-feather="user" class="h-6 w-6 text-gray-600"></i>
                        <span class="ml-2 text-sm text-gray-700"><?php echo htmlspecialchars($user['firstname']); ?></span>
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
        <!-- Welcome Section -->
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>!</h1>
                            <p class="mt-1 text-sm text-gray-500">Here's what's happening with your freelance work today.</p>
                        </div>
                        <button onclick="viewAvailableProjects()" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i data-feather="search" class="h-4 w-4 inline mr-2"></i>
                            Find Projects
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Proposals -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="send" class="h-8 w-8 text-indigo-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Proposals</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $total_proposals; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Proposals submitted</span>
                    </div>
                </div>
            </div>

            <!-- Accepted Proposals (still didn't work) -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="100">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="check-circle" class="h-8 w-8 text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Accepted</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $accepted_proposals; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Proposals accepted</span>
                    </div>
                </div>
            </div>

            <!-- Pending Proposals (still didn't work) -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="clock" class="h-8 w-8 text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $pending_proposals; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Awaiting response</span>
                    </div>
                </div>
            </div>

            <!-- Completed Projects (didn't work) -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="award" class="h-8 w-8 text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $completed_projects; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Projects completed</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column - Active Projects & Recent Proposals -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Active Projects -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Your Active Projects</h2>
                    <div class="space-y-4">
                        <?php if (empty($active_projects)): ?>
                            <div class="bg-white shadow rounded-lg p-6 text-center">
                                <i data-feather="briefcase" class="h-12 w-12 text-gray-400 mx-auto"></i>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No active projects</h3>
                                <p class="mt-2 text-sm text-gray-500">Start by submitting proposals to projects</p>
                                <button onclick="viewAvailableProjects()" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Find Projects
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($active_projects as $project): ?>
                                <div class="project-card bg-white shadow rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Client: <?php echo htmlspecialchars($project['poster_firstname'] . ' ' . $project['poster_lastname']); ?> • 
                                                $<?php echo number_format($project['fixed_price'], 2); ?>
                                            </p>
                                            <p class="mt-2 text-sm text-gray-600"><?php echo htmlspecialchars(substr($project['description'], 0, 100)); ?>...</p>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    In Progress
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="viewProject(<?php echo $project['project_id']; ?>)" class="p-2 text-gray-400 hover:text-indigo-600">
                                                <i data-feather="eye" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Proposals -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Your Recent Proposals</h2>
                    <div class="space-y-4">
                        <?php if (empty($user_proposals)): ?>
                            <div class="bg-white shadow rounded-lg p-6 text-center">
                                <i data-feather="send" class="h-12 w-12 text-gray-400 mx-auto"></i>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No proposals yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Submit your first proposal to get started</p>
                                <button onclick="viewAvailableProjects()" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Find Projects
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach (array_slice($user_proposals, 0, 3) as $proposal): ?>
                                <div class="project-card bg-white shadow rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($proposal['project_title']); ?></h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Submitted <?php echo date('M j, Y', strtotime($proposal['created_at'])); ?> • 
                                                $<?php echo number_format($proposal['fixed_price'], 2); ?>
                                            </p>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php 
                                                    if ($proposal['status'] === 'accepted') echo 'bg-green-100 text-green-800';
                                                    elseif ($proposal['status'] === 'rejected') echo 'bg-red-100 text-red-800';
                                                    else echo 'bg-yellow-100 text-yellow-800';
                                                    ?>">
                                                    <?php echo ucfirst($proposal['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="viewProposal(<?php echo $proposal['id']; ?>)" class="p-2 text-gray-400 hover:text-indigo-600">
                                                <i data-feather="eye" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($user_proposals) > 3): ?>
                                <div class="text-center">
                                    <a href="freelancer_proposals.php" class="text-indigo-600 hover:text-indigo-900 font-medium">View all proposals</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions & Recent Projects -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="viewAvailableProjects()" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i data-feather="search" class="h-4 w-4 mr-2"></i>
                                Find Projects
                            </button>
                            <button onclick="viewAllProposals()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i data-feather="send" class="h-4 w-4 mr-2"></i>
                                View All Proposals
                            </button>
                            <button onclick="viewActiveProjects()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i data-feather="briefcase" class="h-4 w-4 mr-2"></i>
                                Active Projects
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Projects Available -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Projects Available</h3>
                        <div class="space-y-4">
                            <?php if (empty($recent_projects)): ?>
                                <p class="text-sm text-gray-500 text-center">No projects available</p>
                            <?php else: ?>
                                <?php foreach ($recent_projects as $project): ?>
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h4 class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h4>
                                        <p class="mt-1 text-xs text-gray-500">$<?php echo number_format($project['fixed_price'], 2); ?></p>
                                        <p class="mt-2 text-xs text-gray-600"><?php echo htmlspecialchars(substr($project['description'], 0, 80)); ?>...</p>
                                        <button onclick="viewProjectDetails(<?php echo $project['id']; ?>)" class="mt-3 w-full text-center text-xs text-indigo-600 hover:text-indigo-900 font-medium">
                                            View Details
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($recent_projects)): ?>
                            <button onclick="viewAvailableProjects()" class="mt-4 w-full text-center text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                View All Projects
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script>
        // Initialize animation on scroll
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Feather Icons
        feather.replace();

        // Navigation functions
        function viewProject(projectId) {
            window.location.href = 'project_details.php?id=' + projectId;
        }

        function viewProposal(proposalId) {
            window.location.href = 'proposal_details.php?id=' + proposalId;
        }

        function viewProjectDetails(projectId) {
            window.location.href = 'project_details.php?id=' + projectId;
        }

        function viewAvailableProjects() {
            window.location.href = 'available_projects.php';
        }

        function viewAllProposals() {
            window.location.href = 'freelancer_proposals.php';
        }

        function viewActiveProjects() {
            window.location.href = 'freelancer_projects.php';
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>