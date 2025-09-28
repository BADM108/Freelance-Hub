<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceHub | Find & Hire Top Freelancers</title>
   <!--Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- this is where the animations on the scroll is taken from -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- this is where the feather icons are taken from -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Alpine JS for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- this is where the google fonts are taken from -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- this is where the inter font is taken from -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Primary Styles -->
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
        /* Keyframes for scrolling animation */
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
    </style>
</head>

<body class="bg-gray-50">
        <!-- Header -->
    <header x-data="{ open: false, page: '' }" class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i data-feather="briefcase" class="text-indigo-600 h-6 w-6"></i>
                        <span class="ml-2 text-xl font-bold text-gray-900">FreelanceHub</span>
                    </div>
                </div>

                 <!---- Navigation Links ---->
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <a href="#" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-medium">Home</a>
                    <a href="Browse_Jobs_H.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Browse Jobs</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">Find Freelancers</a>
                    <a href="How_It_Works_Page.php" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 text-sm font-medium">How It Works</a>
                </div>

                <!---- main buttons ---->
                <div class="hidden md:ml-6 md:flex md:items-center">
                    <button id="btnSignIn" onclick="window.location.href='signin.php';" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                 Sign In
                    </button>

                    <button id="btnSignUp" onclick="window.location.href='signup.php';" class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign Up
          </button>
                </div>
            </div>
        </div>
        <!--checks if the feather icons are loaded -->
        <script>
            if (window.feather) {
                feather.replace();
            }
        </script>
</body>

</html>

<div class="hero-gradient">
        <!---- Hero Section ---->

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

<!-- Popular Categories Section -->

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
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl" data-aos="fade-up">
                How FreelanceHub Works
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-gray-500" data-aos="fade-up" data-aos-delay="100">
                Simple steps to get your project done
            </p>
        </div>
        <div class="mt-16">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                <div class="relative" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <span class="text-lg font-bold">1</span>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">
                        Post a Project
                    </h3>
                    <p class="mt-2 text-base text-gray-500">
                        Describe your project and set your budget. Our system will match you with qualified freelancers.
                    </p>
                </div>
                <div class="mt-10 lg:mt-0 relative" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <span class="text-lg font-bold">2</span>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">
                        Review Proposals
                    </h3>
                    <p class="mt-2 text-base text-gray-500">
                        Compare freelancer profiles, portfolios, and bids. Message candidates to ask questions.
                    </p>
                </div>
                <div class="mt-10 lg:mt-0 relative" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <span class="text-lg font-bold">3</span>
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900">
                        Get It Done
                    </h3>
                    <p class="mt-2 text-base text-gray-500">
                        Award your project and collaborate securely through our platform. Pay only when you're satisfied.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl" data-aos="fade-up">
            Trusted by Thousands of Businesses
        </h2>
        <p class="mt-4 max-w-2xl mx-auto text-gray-500" data-aos="fade-up" data-aos-delay="100">
            Join companies who found success with FreelanceHub
        </p>
    </div>
    <div class="mt-16 grid gap-8 md:grid-cols-3">
        <div class="bg-white p-8 rounded-lg shadow" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" src="http://static.photos/people/200x200/1" alt="Sarah Johnson">
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Sarah Johnson</h3>
                    <p class="text-gray-500">CEO, TechStart Inc.</p>
                </div>
            </div>
            <p class="mt-4 text-gray-600">
                "FreelanceHub helped us scale our development team quickly. We found 3 amazing developers who are now core members of our team."
            </p>
            <div class="mt-4 flex">
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
            </div>
        </div>
        <div class="bg-white p-8 rounded-lg shadow" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" src="http://static.photos/people/200x200/2" alt="Michael Chen">
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Michael Chen</h3>
                    <p class="text-gray-500">Marketing Director</p>
                </div>
            </div>
            <p class="mt-4 text-gray-600">
                "Our marketing campaigns have never been better since we started working with freelancers from FreelanceHub. The quality is outstanding."
            </p>
            <div class="mt-4 flex">
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
            </div>
        </div>
        <div class="bg-white p-8 rounded-lg shadow" data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-12 w-12 rounded-full" src="http://static.photos/people/200x200/3" alt="Emma Rodriguez">
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Emma Rodriguez</h3>
                    <p class="text-gray-500">Founder, DesignStudio</p>
                </div>
            </div>
            <p class="mt-4 text-gray-600">
                "As a small business, we couldn't afford full-time designers. FreelanceHub gave us access to top talent exactly when we needed it."
            </p>
            <div class="mt-4 flex">
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
                <i data-feather="star" class="h-5 w-5 text-yellow-400"></i>
            </div>
        </div>
    </div>
</div>
<div class="bg-indigo-700">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl" data-aos="fade-right">
            <span class="block">Ready to dive in?</span>
            <span class="block text-indigo-200">Start your project today.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0" data-aos="fade-left">
            <div class="inline-flex rounded-md shadow">
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Post a Project
                    </a>
            </div>
            <div class="ml-3 inline-flex rounded-md shadow">
                <a href="#" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70">
                        Browse Freelancers
                    </a>
            </div>
        </div>
    </div>
</div>
<footer class="bg-gray-800">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">For Clients</h3>
                <ul class="mt-4 space-y-4">
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">How to Hire</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Pricing</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Client Stories</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">For Freelancers</h3>
                <ul class="mt-4 space-y-4">
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">How to Find Work</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Freelancer Plus</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Freelancer Stories</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Company</h3>
                <ul class="mt-4 space-y-4">
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">About Us</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Careers</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Connect</h3>
                <ul class="mt-4 space-y-4">
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Blog</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Twitter</a></li>
                    <li><a href="#" class="text-base text-gray-300 hover:text-white">Facebook</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
            <div class="flex space-x-6 md:order-2">
                <a href="#" class="text-gray-400 hover:text-gray-300">
                    <i data-feather="twitter" class="h-6 w-6"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-300">
                    <i data-feather="facebook" class="h-6 w-6"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-300">
                    <i data-feather="instagram" class="h-6 w-6"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-300">
                    <i data-feather="linkedin" class="h-6 w-6"></i>
                </a>
            </div>
            <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                &copy; 2023 FreelanceHub. All rights reserved.
            </p>
        </div>
    </div>
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