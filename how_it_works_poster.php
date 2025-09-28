<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
$user_sql = "SELECT firstname, lastname FROM freelancerr WHERE uid = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How It Works - Clients | FreelanceHub</title>
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
        .step-card {
            transition: all 0.3s ease;
        }
        .step-card:hover {
            transform: translateY(-5px);
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
                        <span class="ml-2 text-sm text-gray-500">| How It Works</span>
                    </div>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <?php if ($user_role === 'freelancer'): ?>
                        <a href="jobposterdashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                        <a href="available_projects.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Find Work</a>
                        <a href="how_it_works_freelancer.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">How It Works</a>
                    <?php else: ?>
                        <a href="jobposterdashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                        <a href="available_freelancers.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Find Freelancers</a>
                        <a href="how_it_works_poster.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">How It Works</a>
                    <?php endif; ?>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Hello, <?php echo $user_name; ?></span>
                        <button onclick="window.location.href='<?php echo $user_role === 'freelancer' ? 'freelanceHubfreelancerDashboard.php' : 'jobposterdashboard.php'; ?>';" 
                                class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition-colors duration-200" title="Dashboard">
                            <i data-feather="user" class="h-6 w-6 text-gray-600"></i>
                        </button>
                    </div>
                    
                    <button onclick="window.location.href='freelanceHubHome.php';" class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl" data-aos="fade-down">
                    How FreelanceHub Works for Clients
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-indigo-100" data-aos="fade-down" data-aos-delay="100">
                    Find the perfect freelancer for your project. Get quality work delivered on time, every time.
                </p>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Step 1 -->
            <div class="step-card text-center" data-aos="fade-up">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-indigo-100 text-indigo-600">
                        <i data-feather="file-text" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">1. Post Your Project</h3>
                <p class="mt-4 text-gray-600">
                    Describe your project needs, set your budget, and define the timeline. Be specific to attract the right freelancers.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Clear project description
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Set realistic budget
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Define deliverables
                    </li>
                </ul>
            </div>

            <!-- Step 2 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-green-100 text-green-600">
                        <i data-feather="users" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">2. Receive Proposals</h3>
                <p class="mt-4 text-gray-600">
                    Get proposals from qualified freelancers. Review their profiles, portfolios, and ratings to find the perfect match.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Compare freelancer profiles
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Review work samples
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Check ratings and reviews
                    </li>
                </ul>
            </div>

            <!-- Step 3 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 text-blue-600">
                        <i data-feather="message-circle" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">3. Interview & Hire</h3>
                <p class="mt-4 text-gray-600">
                    Chat with top candidates, ask questions, and select the best freelancer for your project needs.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Message freelancers directly
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Discuss project details
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Make your selection
                    </li>
                </ul>
            </div>
        </div>

        <!-- Second Row Steps -->
        <div class="mt-16 grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Step 4 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-purple-100 text-purple-600">
                        <i data-feather="play" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">4. Start Collaboration</h3>
                <p class="mt-4 text-gray-600">
                    Use our platform to communicate, share files, and track progress. Stay updated throughout the project.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Secure messaging system
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        File sharing capabilities
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Progress tracking
                    </li>
                </ul>
            </div>

            <!-- Step 5 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-yellow-100 text-yellow-600">
                        <i data-feather="check-circle" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">5. Review Work</h3>
                <p class="mt-4 text-gray-600">
                    Receive completed work and provide feedback. Request revisions if needed until you're completely satisfied.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Review deliverables
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Provide constructive feedback
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Approve final work
                    </li>
                </ul>
            </div>

            <!-- Step 6 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-green-100 text-green-600">
                        <i data-feather="star" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">6. Pay & Review</h3>
                <p class="mt-4 text-gray-600">
                    Release payment securely and leave a review. Build long-term relationships with top freelancers.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Secure payment release
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Rate your experience
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Save favorite freelancers
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="bg-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl" data-aos="fade-up">
                    Why Choose FreelanceHub?
                </h2>
            </div>
            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <i data-feather="zap" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Quick Hiring</h3>
                    <p class="mt-2 text-gray-600">Find and hire quality freelancers in minutes, not weeks.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <i data-feather="shield" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Quality Guarantee</h3>
                    <p class="mt-2 text-gray-600">Only pay when you're satisfied with the delivered work.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <i data-feather="globe" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Global Talent</h3>
                    <p class="mt-2 text-gray-600">Access skilled professionals from around the world.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl" data-aos="fade-right">
                <span class="block">Ready to get started?</span>
                <span class="block text-indigo-200">Post your first project and find amazing talent.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0" data-aos="fade-left">
                <div class="inline-flex rounded-md shadow">
                    <a href="poster_dashboard.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Post a Project
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="available_freelancers.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70">
                        Browse Freelancers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        feather.replace();
    </script>
</body>
</html>
<?php $conn->close(); ?>