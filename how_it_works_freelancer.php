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
    <title>How It Works - Freelancers | FreelanceHub</title>
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
                        <a href="freelanceHubfreelancerDashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                        <a href="available_projects.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Find Work</a>
                        <a href="how_it_works_freelancer.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">How It Works</a>
                    <?php else: ?>
                        <a href="poster_dashboard.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Dashboard</a>
                        <a href="available_freelancers.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Find Freelancers</a>
                        <a href="how_it_works_poster.php" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">How It Works</a>
                    <?php endif; ?>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Hello, <?php echo $user_name; ?></span>
                        <button onclick="window.location.href='<?php echo $user_role === 'freelancer' ? 'freelanceHubfreelancerDashboard.php' : 'poster_dashboard.php'; ?>';" 
                                class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition-colors duration-200" title="Dashboard">
                            <i data-feather="user" class="h-6 w-6 text-gray-600"></i>
                        </button>
                    </div>
                    
                    <button onclick="window.location.href='logout.php';" class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
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
                    How FreelanceHub Works for Freelancers
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-indigo-100" data-aos="fade-down" data-aos-delay="100">
                    Start earning money doing what you love. Here's how to get started on FreelanceHub.
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
                        <i data-feather="user-plus" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">1. Create Your Profile</h3>
                <p class="mt-4 text-gray-600">
                    Build a compelling profile showcasing your skills, experience, and portfolio. The better your profile, the more likely clients will hire you.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Add your skills and expertise
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Upload portfolio samples
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Set your hourly rate or project preferences
                    </li>
                </ul>
            </div>

            <!-- Step 2 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-green-100 text-green-600">
                        <i data-feather="search" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">2. Find Perfect Projects</h3>
                <p class="mt-4 text-gray-600">
                    Browse through thousands of projects that match your skills. Use filters to find projects that fit your expertise and schedule.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Search by skill, budget, and deadline
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Save favorite projects
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Get matched with relevant projects
                    </li>
                </ul>
            </div>

            <!-- Step 3 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 text-blue-600">
                        <i data-feather="send" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">3. Submit Proposals</h3>
                <p class="mt-4 text-gray-600">
                    Write compelling proposals that stand out. Explain why you're the perfect fit and how you'll deliver value to the client.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Customize each proposal
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Showcase relevant experience
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Ask clarifying questions
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
                        <i data-feather="award" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">4. Get Hired</h3>
                <p class="mt-4 text-gray-600">
                    Clients review your proposals and select the best fit. Once hired, you'll receive all project details and can start working.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Receive project notification
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Review project requirements
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Confirm timeline and deliverables
                    </li>
                </ul>
            </div>

            <!-- Step 5 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="400">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-yellow-100 text-yellow-600">
                        <i data-feather="briefcase" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">5. Complete Work</h3>
                <p class="mt-4 text-gray-600">
                    Deliver high-quality work on time. Communicate regularly with your client and provide updates on your progress.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Use our collaboration tools
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Submit work for review
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Make revisions if needed
                    </li>
                </ul>
            </div>

            <!-- Step 6 -->
            <div class="step-card text-center" data-aos="fade-up" data-aos-delay="500">
                <div class="flex justify-center">
                    <div class="flex items-center justify-center h-20 w-20 rounded-full bg-green-100 text-green-600">
                        <i data-feather="dollar-sign" class="h-10 w-10"></i>
                    </div>
                </div>
                <h3 class="mt-6 text-xl font-bold text-gray-900">6. Get Paid</h3>
                <p class="mt-4 text-gray-600">
                    Once the client approves your work, receive secure payment through our platform. Build your reputation with reviews.
                </p>
                <ul class="mt-4 text-sm text-gray-500 space-y-2">
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Secure payment processing
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Multiple withdrawal options
                    </li>
                    <li class="flex items-center justify-center">
                        <i data-feather="check" class="h-4 w-4 text-green-500 mr-2"></i>
                        Receive client reviews
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
                    Why Freelance With Us?
                </h2>
            </div>
            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <i data-feather="shield" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Secure Payments</h3>
                    <p class="mt-2 text-gray-600">Get paid on time with our secure payment protection system.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white">
                        <i data-feather="users" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Global Clients</h3>
                    <p class="mt-2 text-gray-600">Access clients from around the world and expand your opportunities.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                        <i data-feather="trending-up" class="h-6 w-6"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Build Your Reputation</h3>
                    <p class="mt-2 text-gray-600">Earn reviews and build a strong profile that attracts more clients.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl" data-aos="fade-right">
                <span class="block">Ready to start freelancing?</span>
                <span class="block text-indigo-200">Join thousands of successful freelancers today.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0" data-aos="fade-left">
                <div class="inline-flex rounded-md shadow">
                    <a href="available_projects.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Browse Projects
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="freelanceHubfreelancerDashboard.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70">
                        Go to Dashboard
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