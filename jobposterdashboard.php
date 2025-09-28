<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
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

// Get poster's projects count
$projects_sql = "SELECT COUNT(*) as total_projects FROM projects WHERE poster_id = ?";
$stmt = $conn->prepare($projects_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();
$projects_data = $projects_result->fetch_assoc();
$total_projects = $projects_data['total_projects'];
$stmt->close();

// Get completed projects count
$completed_sql = "SELECT COUNT(*) as completed_projects FROM projects WHERE poster_id = ? AND status = 'completed'";
$stmt = $conn->prepare($completed_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completed_result = $stmt->get_result();
$completed_data = $completed_result->fetch_assoc();
$completed_projects = $completed_data['completed_projects'];
$stmt->close();

// Get total proposals received
$proposals_sql = "SELECT COUNT(*) as total_proposals FROM proposals p 
                  JOIN projects pr ON p.project_id = pr.id 
                  WHERE pr.poster_id = ?";
$stmt = $conn->prepare($proposals_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$proposals_result = $stmt->get_result();
$proposals_data = $proposals_result->fetch_assoc();
$total_proposals = $proposals_data['total_proposals'];
$stmt->close();

// Get pending proposals count
$pending_sql = "SELECT COUNT(*) as pending_proposals FROM proposals p 
                JOIN projects pr ON p.project_id = pr.id 
                WHERE pr.poster_id = ? AND p.status = 'pending'";
$stmt = $conn->prepare($pending_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_result = $stmt->get_result();
$pending_data = $pending_result->fetch_assoc();
$pending_proposals = $pending_data['pending_proposals'];
$stmt->close();

// Get poster's projects
$user_projects_sql = "SELECT * FROM projects WHERE poster_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($user_projects_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_projects_result = $stmt->get_result();
$user_projects = [];
while ($row = $user_projects_result->fetch_assoc()) {
    $user_projects[] = $row;
}
$stmt->close();

// Get recent proposals
$recent_proposals_sql = "SELECT p.*, f.firstname, f.lastname, pr.title as project_title 
                        FROM proposals p 
                        JOIN freelancerr f ON p.freelancer_id = f.uid 
                        JOIN projects pr ON p.project_id = pr.id 
                        WHERE pr.poster_id = ? 
                        ORDER BY p.created_at DESC 
                        LIMIT 3";
$stmt = $conn->prepare($recent_proposals_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_proposals_result = $stmt->get_result();
$recent_proposals = [];
while ($row = $recent_proposals_result->fetch_assoc()) {
    $recent_proposals[] = $row;
}
$stmt->close();

// Get hired freelancers
$hires_sql = "SELECT h.*, f.firstname, f.lastname, pr.title 
             FROM hires h 
             JOIN freelancerr f ON h.freelancer_id = f.uid 
             JOIN projects pr ON h.project_id = pr.id 
             WHERE h.poster_id = ? 
             ORDER BY h.hired_at DESC";
$stmt = $conn->prepare($hires_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$hires_result = $stmt->get_result();
$hires = [];
while ($row = $hires_result->fetch_assoc()) {
    $hires[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Poster Dashboard | FreelanceHub</title>
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
                        <span class="ml-2 text-sm text-gray-500">| Job Poster Dashboard</span>
                    </div>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="jobposterloggedinhome.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Home</a>
                    <a href="#" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Dashboard</a>
                    <a href="poster_projects.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">My Projects</a>
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
                            <p class="mt-1 text-sm text-gray-500">Here's what's happening with your projects today.</p>
                        </div>
                        <button onclick="openPostJobModal()" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i data-feather="plus" class="h-4 w-4 inline mr-2"></i>
                            Post New Job
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Projects -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="file-text" class="h-8 w-8 text-indigo-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Projects</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $total_projects; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Your posted projects</span>
                    </div>
                </div>
            </div>

            <!-- Completed Projects -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="100">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="check-circle" class="h-8 w-8 text-green-600"></i>
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
                        <span class="text-gray-500">Finished projects</span>
                    </div>
                </div>
            </div>

            <!-- Proposals Received -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="users" class="h-8 w-8 text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Proposals Received</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $total_proposals; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Total proposals</span>
                    </div>
                </div>
            </div>

            <!-- Pending Proposals -->
            <div class="stat-card bg-white overflow-hidden shadow rounded-lg" data-aos="fade-up" data-aos-delay="300">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i data-feather="clock" class="h-8 w-8 text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Proposals</dt>
                                <dd>
                                    <div class="text-lg font-medium text-gray-900"><?php echo $pending_proposals; ?></div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-gray-500">Need review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column - Projects & Hires -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Projects -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Your Recent Projects</h2>
                    <div class="space-y-4">
                        <?php if (empty($user_projects)): ?>
                            <div class="bg-white shadow rounded-lg p-6 text-center">
                                <i data-feather="file-text" class="h-12 w-12 text-gray-400 mx-auto"></i>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No projects yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Get started by posting your first job!</p>
                                <button onclick="openPostJobModal()" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Post Your First Job
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach (array_slice($user_projects, 0, 3) as $project): ?>
                                <div class="project-card bg-white shadow rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Posted <?php echo date('M j, Y', strtotime($project['created_at'])); ?> • 
                                                $<?php echo number_format($project['fixed_price'], 2); ?>
                                            </p>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php echo $project['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                    <?php echo ucfirst($project['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="viewProject(<?php echo $project['id']; ?>)" class="p-2 text-gray-400 hover:text-indigo-600">
                                                <i data-feather="eye" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (count($user_projects) > 3): ?>
                                <div class="text-center">
                                    <a href="poster_projects.php" class="text-indigo-600 hover:text-indigo-900 font-medium">View all projects</a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Hired Freelancers -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Hired Freelancers</h2>
                    <div class="space-y-4">
                        <?php if (empty($hires)): ?>
                            <div class="bg-white shadow rounded-lg p-6 text-center">
                                <i data-feather="users" class="h-12 w-12 text-gray-400 mx-auto"></i>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No hires yet</h3>
                                <p class="mt-2 text-sm text-gray-500">Accept proposals to hire freelancers</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($hires as $hire): ?>
                                <div class="project-card bg-white shadow rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($hire['firstname'] . ' ' . $hire['lastname']); ?></h3>
                                            <p class="mt-1 text-sm text-gray-500">Working on: <?php echo htmlspecialchars($hire['title']); ?></p>
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    <?php echo $hire['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                                    <?php echo ucfirst($hire['status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Hired: <?php echo date('M j, Y', strtotime($hire['hired_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!--  Quick Actions & Recent Proposals -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="openPostJobModal()" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i data-feather="plus" class="h-4 w-4 mr-2"></i>
                                Post New Job
                            </button>
                            <button onclick="viewAllProposals()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i data-feather="users" class="h-4 w-4 mr-2"></i>
                                View All Proposals
                            </button>
                            <button onclick="viewAllProjects()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i data-feather="file-text" class="h-4 w-4 mr-2"></i>
                                Manage Projects
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Proposals -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Proposals</h3>
                        <div class="space-y-4">
                            <?php if (empty($recent_proposals)): ?>
                                <p class="text-sm text-gray-500 text-center">No proposals yet</p>
                            <?php else: ?>
                                <?php foreach ($recent_proposals as $proposal): ?>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-indigo-600 text-sm font-medium">
                                                    <?php echo strtoupper(substr($proposal['firstname'], 0, 1) . substr($proposal['lastname'], 0, 1)); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($proposal['firstname'] . ' ' . $proposal['lastname']); ?></p>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($proposal['project_title']); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo date('M j, g:i A', strtotime($proposal['created_at'])); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($recent_proposals)): ?>
                            <button onclick="viewAllProposals()" class="mt-4 w-full text-center text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                                View All Proposals
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Job Modal -->
    <div id="postJobModal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Post a New Job
                        </h3>
                        <div class="mt-2">
                            <form action="post_job.php" method="POST" class="space-y-4">
                                <div>
                                    <label for="jobTitle" class="block text-sm font-medium text-gray-700">Job Title</label>
                                    <input type="text" id="jobTitle" name="jobTitle" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                </div>
                                <div>
                                    <label for="jobDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="jobDescription" name="jobDescription" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required></textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="fixed_price" class="block text-sm font-medium text-gray-700">Fixed Price ($)</label>
                                        <input type="number" id="fixed_price" name="fixed_price" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    </div>
                                    <div>
                                        <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                                        <input type="date" id="deadline" name="deadline" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                    </div>
                                </div>
                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="submit" onclick="savejobpost()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                        Post Job
                                    </button>
                                    <button type="button" onclick="closePostJobModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Feather Icons
        feather.replace();

        // Modal functions
        function openPostJobModal() {
            document.getElementById('postJobModal').classList.remove('hidden');
        }

        function closePostJobModal() {
            document.getElementById('postJobModal').classList.add('hidden');
        }
        function savejobpost()
        {
            alert("Job Posted Successfully");
        }
        

        // Navigation functions
        function viewProject(projectId) {
            window.location.href = 'project_details.php?id=' + projectId;
        }

        function viewAllProposals() {
            window.location.href = 'poster_proposals.php';
        }

        function viewAllProjects() {
            window.location.href = 'poster_projects.php';
        }

        // Close modal when clicking outside
        document.getElementById('postJobModal').addEventListener('click', function(e) {
            if (e.target.id === 'postJobModal') {
                closePostJobModal();
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>