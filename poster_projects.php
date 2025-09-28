<?php
//PHP PART
session_start();
require_once 'connection.php';

// Check if user is logged in and is a poster
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
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

// Handle project deletion
if (isset($_POST['delete_project'])) {
    $project_id = $_POST['project_id'];
    
    // Check if project belongs to the user
    $check_sql = "SELECT id FROM projects WHERE id = ? AND poster_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $project_id, $user_id);
    $stmt->execute();
    $check_result = $stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Delete project 
        $delete_sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $project_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Project deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete project: ' . $stmt->error;
        }
    } else {
        $_SESSION['error'] = 'Project not found or you do not have permission to delete it.';
    }
    $stmt->close();
    
    // Redirect to avoid form resubmission
    header('Location: poster_projects.php');
    exit;
}

// Get poster's projects with proposal counts
$projects_sql = "SELECT p.*, 
                COUNT(pr.id) as proposal_count,
                COUNT(CASE WHEN pr.status = 'accepted' THEN 1 END) as accepted_proposals
                FROM projects p 
                LEFT JOIN proposals pr ON p.id = pr.project_id 
                WHERE p.poster_id = ? 
                GROUP BY p.id 
                ORDER BY p.created_at DESC";
$stmt = $conn->prepare($projects_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();
$projects = [];
while ($row = $projects_result->fetch_assoc()) {
    $projects[] = $row;
}
$stmt->close();

// Get status counts for filter
$status_counts_sql = "SELECT status, COUNT(*) as count FROM projects WHERE poster_id = ? GROUP BY status";
$stmt = $conn->prepare($status_counts_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$status_counts_result = $stmt->get_result();
$status_counts = [];
$total_projects = 0;
while ($row = $status_counts_result->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
    $total_projects += $row['count'];
}
$stmt->close();
?>
<!-- HTML -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects | FreelanceHub</title>
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
        
        .project-card {
            transition: all 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .active-filter {
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
                        <span class="ml-2 text-sm text-gray-500">| Manage Projects</span>
                    </div>
                </div>
               <!----- Navigation Links -->
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="jobposterloggedinhome.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Home</a>
                    <a href="jobposterdashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                    <a href="poster_projects.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Manage Projects</a>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <!-- User Name -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Hello, <?php echo $user_name; ?></span>
                        <button onclick="window.location.href='jobposterdashboard.php';" class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition-colors duration-200" title="Dashboard">
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
                            <h1 class="text-2xl font-bold text-gray-900">Manage Your Projects</h1>
                            <p class="mt-1 text-sm text-gray-500">View, manage, and delete your job posts</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Projects: <span class="font-semibold text-indigo-600"><?php echo $total_projects; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filter by Status</h3>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterProjects('all')" class="filter-btn active-filter px-4 py-2 rounded-full text-sm font-medium bg-indigo-600 text-white">
                        All (<?php echo $total_projects; ?>)
                    </button>
                    <button onclick="filterProjects('open')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Open (<?php echo $status_counts['open'] ?? 0; ?>)
                    </button>
                    <button onclick="filterProjects('completed')" class="filter-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Completed (<?php echo $status_counts['completed'] ?? 0; ?>)
                    </button>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i data-feather="check-circle" class="h-5 w-5 text-green-600 mr-2"></i>
                    <span class="text-green-800"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i data-feather="alert-circle" class="h-5 w-5 text-red-600 mr-2"></i>
                    <span class="text-red-800"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Projects Grid -->
        <div class="mt-6 space-y-6">
            <?php if (empty($projects)): ?>
                <div class="bg-white shadow rounded-lg p-8 text-center">
                    <i data-feather="file-text" class="h-12 w-12 text-gray-400 mx-auto"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No projects yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Get started by posting your first job!</p>
                    <button onclick="window.location.href='poster_dashboard.php'" class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Post Your First Job
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-card bg-white shadow rounded-lg" data-status="<?php echo $project['status']; ?>">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">   <!-- echo outputs stuff safely-->
                                        <h3 class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($project['title']); ?></h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            <?php echo $project['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo ucfirst($project['status']); ?>
                                        </span>
                                    </div>
                                    
                                    <p class="mt-2 text-sm text-gray-600"><?php echo htmlspecialchars($project['description']); ?></p>
                                    
                                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i data-feather="dollar-sign" class="h-4 w-4 mr-2"></i>
                                            <span>$<?php echo number_format($project['fixed_price'], 2); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i data-feather="calendar" class="h-4 w-4 mr-2"></i>
                                            <span>Posted: <?php echo date('M j, Y', strtotime($project['created_at'])); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i data-feather="users" class="h-4 w-4 mr-2"></i>
                                            <span><?php echo $project['proposal_count']; ?> proposals (<?php echo $project['accepted_proposals']; ?> accepted)</span>
                                        </div>
                                    </div>
                                    
                                    <?php if ($project['deadline']): ?>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <i data-feather="clock" class="h-4 w-4 mr-2"></i>
                                            <span>Deadline: <?php echo date('M j, Y', strtotime($project['deadline'])); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-6 flex flex-wrap gap-3">
                                <button onclick="viewProject(<?php echo $project['id']; ?>)" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i data-feather="eye" class="h-4 w-4 mr-2"></i>
                                    View Details
                                </button>
                                
                                <button onclick="viewProposals(<?php echo $project['id']; ?>)" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i data-feather="users" class="h-4 w-4 mr-2"></i>
                                    View Proposals (<?php echo $project['proposal_count']; ?>)
                                </button>
                                
                                <?php if ($project['status'] === 'open' && $project['proposal_count'] == 0): ?>
                                    <button onclick="confirmDelete(<?php echo $project['id']; ?>, '<?php echo htmlspecialchars(addslashes($project['title'])); ?>')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i data-feather="trash-2" class="h-4 w-4 mr-2"></i>
                                        Delete
                                    </button>
                                <?php elseif ($project['status'] === 'open'): ?>
                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed" title="Cannot delete project with active proposals">
                                        <i data-feather="trash-2" class="h-4 w-4 mr-2"></i>
                                        Delete
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed" title="Cannot delete completed projects">
                                        <i data-feather="trash-2" class="h-4 w-4 mr-2"></i>
                                        Delete
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 overflow-y-auto hidden z-50">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i data-feather="alert-triangle" class="h-6 w-6 text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Delete Project
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete project "<span id="projectTitle" class="font-semibold"></span>"? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" class="inline-flex">
                        <input type="hidden" name="project_id" id="deleteProjectId">
                        <button type="submit" name="delete_project" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
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

        // Filter projects by status
        function filterProjects(status) {
            const projects = document.querySelectorAll('.project-card');
            const filterBtns = document.querySelectorAll('.filter-btn');
            
            // Update active filter button
            filterBtns.forEach(btn => btn.classList.remove('active-filter', 'bg-indigo-600', 'text-white'));
            filterBtns.forEach(btn => {
                if (btn.textContent.includes(status) || (status === 'all' && btn.textContent.includes('All'))) {
                    btn.classList.add('active-filter', 'bg-indigo-600', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                } else {
                    btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                }
            });
            
            // Show/hide projects
            projects.forEach(project => {
                if (status === 'all' || project.getAttribute('data-status') === status) {
                    project.style.display = 'block';
                } else {
                    project.style.display = 'none';
                }
            });
        }

        // Delete confirmation modal
        function confirmDelete(projectId, projectTitle) {
            document.getElementById('deleteProjectId').value = projectId;
            document.getElementById('projectTitle').textContent = projectTitle;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Navigation functions
        function viewProject(projectId) {
            window.location.href = 'project_details.php?id=' + projectId;
        }

        function viewProposals(projectId) {
            window.location.href = 'project_proposals.php?id=' + projectId;
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target.id === 'deleteModal') {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>