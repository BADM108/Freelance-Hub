<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceHub | Find & Hire Top Freelancers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>FreelanceHub</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
        
        .animate-scroll {
            display: flex;
            width: max-content;
            animation: scroll 18s linear infinite reverse;
        }
        
        @keyframes fadeInSlow {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .relative.mt-8.w-full.max-w-2xl.mx-auto.overflow-hidden {
            animation: fadeInSlow 10s ease forwards;
            opacity: 0;
        }
        
        .profile-icon {
            transition: all 0.3s ease;
        }
        
        .profile-icon:hover {
            transform: scale(1.1);
            color: #4f46e5;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php
    session_start();
    require_once 'connection.php';
    
    // Check if user is logged in
    $user_name = "Guest";
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user_sql = "SELECT firstname, lastname FROM freelancerr WHERE uid = ?";
        $stmt = $conn->prepare($user_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();
        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            $user_name = htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
        }
        $stmt->close();
    }
    ?>

    <header x-data="{ open: false, page: '' }" class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="briefcase" class="text-indigo-600 h-6 w-6"></i>
                        <span class="ml-2 text-xl font-bold text-gray-900">FreelanceHub</span>
                    </div>
                </div>
                  <!-- Navigation Links -->
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="#" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Home</a>
                    <a href="available_projects.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Browse Jobs</a>                 
                    <a href="How_It_Works_freelancer.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">How It Works</a>
                </div>

                <div class="hidden md:ml-6 md:flex md:items-center">
                    <!-- Profile Icon with User Name -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-700">Hello, <?php echo $user_name; ?></span>
                        <button onclick="window.location.href='freelanceHubfreelancerDashboard.php';" class="flex items-center justify-center p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 profile-icon" title="Profile">
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
        <script>
            if (window.feather) {
                feather.replace();
            }
        </script>
    </header>

    <!-- Hero Section -->
    <div class="hero-gradient">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl" data-aos="fade-down">
                    Find the perfect freelance services for your business
                </h1>
                <p class="mt-6 max-w-lg mx-auto text-xl text-indigo-100" data-aos="fade-down" data-aos-delay="100">
                    Connect with talented freelancers for any project, any budget, and any timeline.
                </p>
                <div class="mt-10 flex justify-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative max-w-xl w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-feather="search" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-4 border border-transparent rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-white focus:border-white sm:text-sm"
                            placeholder="What service are you looking for?">
                        <div class="absolute inset-y-0 right-2 flex items-center">
                            <button class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
                <!---- Category Scrolling Animation ---->
                <div class="relative mt-8 w-full max-w-2xl mx-auto overflow-hidden">
                    <div class="pointer-events-none absolute inset-y-0 left-0 w-18 bg-gradient-to-1 "></div>
                    <div class="pointer-events-none absolute inset-y-0 right-0 w-18 bg-gradient-to-l "></div>

                    <div class="flex space-x-4 animate-scroll">
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Web Development</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Graphic Design</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Digital Marketing</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Writing</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Animations</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Music</span>

                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Web Development</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Graphic Design</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Digital Marketing</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Writing</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Animations</span>
                        <span class="px-4 py-2 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">Music</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl" data-aos="fade-up">
                Popular Freelance Categories
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-gray-500" data-aos="fade-up" data-aos-delay="100">
                Browse the most in-demand services on FreelanceHub
            </p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
          <!-- Category Cards -->
            <div class="category-card bg-white overflow-hidden shadow rounded-lg transition-all duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <i data-feather="code" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Web Development</h3>
                            <p class="mt-1 text-sm text-gray-500">1,200+ Freelancers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-card bg-white overflow-hidden shadow rounded-lg transition-all duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="300">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-pink-500 rounded-md p-3">
                            <i data-feather="image" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Graphic Design</h3>
                            <p class="mt-1 text-sm text-gray-500">850+ Freelancers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-card bg-white overflow-hidden shadow rounded-lg transition-all duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="400">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <i data-feather="bar-chart-2" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Digital Marketing</h3>
                            <p class="mt-1 text-sm text-gray-500">1,500+ Freelancers</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-card bg-white overflow-hidden shadow rounded-lg transition-all duration-300 ease-in-out" data-aos="fade-up" data-aos-delay="500">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <i data-feather="smartphone" class="h-6 w-6 text-white"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Mobile Development</h3>
                            <p class="mt-1 text-sm text-gray-500">700+ Freelancers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <div class="bg-gray-100">
        <!-- How it works section -->
    </div>
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <!-- Testimonials section -->
    </div>
    <div class="bg-indigo-700">
        <!-- CTA section -->
    </div>
    <footer class="bg-gray-800">
        <!-- Footer section -->
    </footer>

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