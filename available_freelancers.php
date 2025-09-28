<?php
//this is the php part
session_start();
require_once 'connection.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'poster') {
    header('Location: signin.php');
    exit;
}

// this gets the data of the logged user
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT firstname, lastname FROM freelancerr WHERE uid = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
$stmt->close();

// this handles the search filtering 
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_rating = isset($_GET['min_rating']) ? floatval($_GET['min_rating']) : 0;

// this part fetches the freelancers from the database and gets their details 
$freelancers_sql = "SELECT f.uid, f.firstname, f.lastname, f.email, f.country, 
                   COALESCE(AVG(r.rating), 0) as avg_rating,
                   COUNT(DISTINCT r.id) as review_count,
                   COUNT(DISTINCT p.id) as completed_projects,
                   GROUP_CONCAT(DISTINCT s.skill_name) as skills
                   FROM freelancerr f
                   LEFT JOIN reviews r ON f.uid = r.freelancer_id
                   LEFT JOIN hires h ON f.uid = h.freelancer_id AND h.status = 'completed'
                   LEFT JOIN projects p ON h.project_id = p.id
                   LEFT JOIN freelancer_skills fs ON f.uid = fs.freelancer_id
                   LEFT JOIN skills s ON fs.skill_id = s.id
                   WHERE f.role = 'freelancer'";

$params = [];
$types = "";

// add search filter from php
if (!empty($search)) {
    $freelancers_sql .= " AND (f.firstname LIKE ? OR f.lastname LIKE ? OR f.email LIKE ? OR s.skill_name LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    $types .= "ssss";
}

// add category filter from php
if (!empty($category)) {
    $freelancers_sql .= " AND s.skill_name = ?";
    $params[] = $category;
    $types .= "s";
}
// this is the minimum rating filter from php
$freelancers_sql .= " GROUP BY f.uid HAVING avg_rating >= ?";
$params[] = $min_rating;
$types .= "d";

// this part sorts the data retrived from the database
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'rating';
switch ($sort) {
    case 'name':
        $freelancers_sql .= " ORDER BY f.firstname, f.lastname";
        break;
    case 'projects':
        $freelancers_sql .= " ORDER BY completed_projects DESC";
        break;
    case 'rating':
    default:
        $freelancers_sql .= " ORDER BY avg_rating DESC";
        break;
}

// this executes the swl with filters and save the freelancers in a temporary array
$stmt = $conn->prepare($freelancers_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$freelancers_result = $stmt->get_result();
$freelancers = [];
while ($row = $freelancers_result->fetch_assoc()) {
    $freelancers[] = $row;
}
$stmt->close();

// Get unique skills for filter dropdown
$skills_sql = "SELECT DISTINCT skill_name FROM skills ORDER BY skill_name";
$stmt = $conn->prepare($skills_sql);
$stmt->execute();
$skills_result = $stmt->get_result();
$skills = [];
while ($row = $skills_result->fetch_assoc()) {
    $skills[] = $row['skill_name'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Freelancers | FreelanceHub</title>
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
        
        .freelancer-card {
            transition: all 0.3s ease;
        }
        
        .freelancer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .skill-tag {
            transition: all 0.2s ease;
        }
        
        .skill-tag:hover {
            background-color: #4f46e5;
            color: white;
        }
        
        .rating-stars {
            color: #fbbf24;
        }
    </style>
</head>
    <!--bg gray and all the stuff like tat is from TAILWIND CSS -->
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="briefcase" class="text-indigo-600 h-6 w-6"></i>
                        <span class="ml-2 text-xl font-bold text-gray-900">FreelanceHub</span>
                        <span class="ml-2 text-sm text-gray-500">| Find Freelancers</span>
                    </div>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="jobposterloggedinhome.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Home</a>
                    <a href="jobposterdashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                    <a href="available_freelancers.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Find Freelancers</a>
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
                            <h1 class="text-2xl font-bold text-gray-900">Find Top Freelancers</h1>
                            <p class="mt-1 text-sm text-gray-500">Browse and connect with skilled professionals for your projects</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Found <span class="font-semibold text-indigo-600"><?php echo count($freelancers); ?></span> freelancers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="available_freelancers.php" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($search); ?>" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Name, email, or skill">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Skill Category</label>
                            <select name="category" id="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Skills</option>
                                <?php foreach ($skills as $skill): ?>
                                    <option value="<?php echo htmlspecialchars($skill); ?>" <?php echo $category === $skill ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($skill); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <label for="min_rating" class="block text-sm font-medium text-gray-700">Minimum Rating</label>
                            <select name="min_rating" id="min_rating" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="0" <?php echo $min_rating == 0 ? 'selected' : ''; ?>>Any Rating</option>
                                <option value="4" <?php echo $min_rating == 4 ? 'selected' : ''; ?>>4+ Stars</option>
                                <option value="4.5" <?php echo $min_rating == 4.5 ? 'selected' : ''; ?>>4.5+ Stars</option>
                            </select>
                        </div>

                        <!-- Sort Filter -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                            <select name="sort" id="sort" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                                <option value="projects" <?php echo $sort === 'projects' ? 'selected' : ''; ?>>Most Projects</option>
                                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i data-feather="filter" class="h-4 w-4 inline mr-2"></i>
                            Apply Filters
                        </button>
                        <a href="available_freelancers.php" class="text-sm text-gray-600 hover:text-gray-900">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Freelancers Grid -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-3">
            <?php if (empty($freelancers)): ?>
                <div class="col-span-full bg-white shadow rounded-lg p-8 text-center">
                    <i data-feather="users" class="h-12 w-12 text-gray-400 mx-auto"></i>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No freelancers found</h3>
                    <p class="mt-2 text-sm text-gray-500">Try adjusting your search criteria or browse all freelancers</p>
                    <a href="available_freelancers.php" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        View All Freelancers
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($freelancers as $freelancer): ?>
                    <div class="freelancer-card bg-white shadow rounded-lg overflow-hidden" data-aos="fade-up">
                        <div class="p-6">
                            <!-- Freelancer Header -->
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 text-lg font-medium">
                                                <?php echo strtoupper(substr($freelancer['firstname'], 0, 1) . substr($freelancer['lastname'], 0, 1)); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <?php echo htmlspecialchars($freelancer['firstname'] . ' ' . $freelancer['lastname']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($freelancer['country']); ?></p>
                                    </div>
                                </div>
                                <button onclick="viewFreelancerProfile(<?php echo $freelancer['uid']; ?>)" 
                                        class="p-2 text-gray-400 hover:text-indigo-600 transition-colors duration-200"
                                        title="View Profile">
                                    <i data-feather="eye" class="h-5 w-5"></i>
                                </button>
                            </div>

                            <!-- Rating and Projects -->
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="flex rating-stars">
                                        <?php
                                        $rating = floatval($freelancer['avg_rating']);
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                        
                                        for ($i = 0; $i < 5; $i++) {
                                            if ($i < $fullStars) {
                                                echo '<i data-feather="star" class="h-4 w-4 fill-current"></i>';
                                            } elseif ($i == $fullStars && $hasHalfStar) {
                                                echo '<i data-feather="star" class="h-4 w-4 fill-current" style="fill: url(#half-star)"></i>';
                                            } else {
                                                echo '<i data-feather="star" class="h-4 w-4"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <span class="text-sm text-gray-600">
                                        <?php echo number_format($rating, 1); ?> (<?php echo $freelancer['review_count']; ?> reviews)
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?php echo $freelancer['completed_projects']; ?> projects
                                </div>
                            </div>

                            <!-- Skills -->
                            <?php if (!empty($freelancer['skills'])): ?>
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Skills</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <?php 
                                        $freelancer_skills = explode(',', $freelancer['skills']);
                                        foreach (array_slice($freelancer_skills, 0, 5) as $skill): 
                                        ?>
                                            <span class="skill-tag inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?php echo htmlspecialchars(trim($skill)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($freelancer_skills) > 5): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                +<?php echo count($freelancer_skills) - 5; ?> more
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex space-x-3">
                                <button onclick="viewFreelancerProfile(<?php echo $freelancer['uid']; ?>)" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i data-feather="user" class="h-4 w-4 mr-2"></i>
                                    View Profile
                                </button>
                                <button onclick="inviteToProject(<?php echo $freelancer['uid']; ?>)" 
                                        class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i data-feather="send" class="h-4 w-4 mr-2"></i>
                                    Invite
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Load More Button this i made only ot appear if theres more than 12 freelancers -->
        <?php if (count($freelancers) >= 12): ?>
            <div class="mt-8 text-center">
                <button class="px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Load More Freelancers
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- SVG for half star (if needed) -->
    <svg width="0" height="0" style="position:absolute">
        <defs>
            <linearGradient id="half-star" x1="0" x2="100%" y1="0" y2="0">
                <stop offset="50%" stop-color="#fbbf24"></stop>
                <stop offset="50%" stop-color="#d1d5db"></stop>
            </linearGradient>
        </defs>
    </svg>
    <!--  java script -->
    <script>
        // animate on scroll one
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize Feather Icons
        feather.replace();

        // Navigation functions
        function viewFreelancerProfile(freelancerId) {
            window.location.href = 'jobposterdashboard.php?id=' + freelancerId;
        }

        function inviteToProject(freelancerId) {
            window.location.href = 'invite_freelancer.php?id=' + freelancerId;
        }

        // Real-time search by name, email, or skills
        document.getElementById('search').addEventListener('input', function() {
           
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>