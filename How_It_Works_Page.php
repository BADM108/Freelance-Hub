<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>How It Works | FreelanceHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<style>
    /* Gradient Text */
    
    .gradient-text,
    .logo-text {
        background: linear-gradient(to right, #9333ea, #4f46e5, #2563eb);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    /* Nav Links */
    
    .nav-link {
        color: #4b5563;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .nav-link:hover {
        color: #4f46e5;
    }
    
    .nav-link.active {
        background: linear-gradient(to right, #9333ea, #4f46e5, #2563eb);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 600;
    }
    /* Step Cards */
    
    .step-card {
        background: #fff;
        padding: 2.5rem;
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        animation: fadeUp 0.8s ease forwards;
        opacity: 0;
    }
    
    .step-card:nth-child(1) {
        animation-delay: 0.2s;
    }
    
    .step-card:nth-child(2) {
        animation-delay: 0.4s;
    }
    
    .step-card:nth-child(3) {
        animation-delay: 0.6s;
    }
    
    .step-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    /* Step Numbers */
    
    .step-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(to right, #9333ea, #4f46e5, #2563eb);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    /* Highlights inside text */
    
    .highlight {
        color: #4f46e5;
        font-weight: 600;
    }
    /* Gradient Button */
    
    .btn-gradient {
        display: inline-block;
        background: linear-gradient(to right, #9333ea, #4f46e5, #2563eb);
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.75rem;
        font-weight: 500;
        font-size: 1.125rem;
        text-decoration: none;
        box-shadow: 0 6px 16px;
        animation: fadeIn 1s ease forwards;
        opacity: 0;
    }
    
    .btn-gradient:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(79, 70, 229, 0.4);
    }
    /* Animations */
    
    @keyframes fadeUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: scale(0.95);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes fadeDown {
        0% {
            opacity: 0;
            transform: translateY(-20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Hero animation */
    
    .hero-title {
        animation: fadeDown 0.8s ease forwards;
        opacity: 0;
    }
    
    .hero-sub {
        animation: fadeDown 1s ease forwards;
        opacity: 0;
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .space-x-6 {
        animation: fadeIn 1s ease forwards;
        opacity: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.85rem;
    }
</style>
<div class="hero-gradient">

    <body class="bg-gray-50 text-gray-800 font-sans">

        <!-- Header -->
        <header class="bg-white shadow sticky top-0 z-50">
            <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
                <h1 class="logo-text text-2xl font-bold">FreelanceHub</h1>
                <nav class="space-x-6">
                    <a href="freelanceHubHome.php" class="nav-link">Home</a>
                    <a href="signup.php" class="nav-link">Sign Up</a>
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <section class="text-center py-20 bg-gray-100">
            <h2 class="gradient-text text-4xl md:text-5xl font-extrabold mb-4 hero-title">How FreelanceHub Works</h2>
            <p class="text-lg max-w-2xl mx-auto text-gray-600 hero-sub">
                Whether you're a client searching for top talent or a freelancer looking for projects, FreelanceHub makes the process simple, secure, and rewarding.
            </p>
        </section>

        <!-- Steps -->
        <section class="max-w-6xl mx-auto px-6 py-20">
            <div class="grid md:grid-cols-3 gap-12 text-center">

                <!-- Step 1 -->
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3 class="text-xl font-semibold mb-3">Create Your Profile</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sign up in minutes. As a <span class="highlight">client</span>, showcase your projects and hiring needs. As a <span class="highlight">freelancer</span>, highlight your skills and portfolio to attract the right opportunities.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3 class="text-xl font-semibold mb-3">Post or Discover Jobs</h3>
                    <p class="text-gray-600 leading-relaxed">
                        <span class="highlight">Clients</span> can post jobs with budgets, timelines, and details.
                        <span class="highlight">Freelancers</span> can browse matching projects, send proposals, and connect with employers worldwide.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3 class="text-xl font-semibold mb-3">Work, Collaborate & Get Paid</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Use our secure platform to chat, share files, and track milestones. Payments are protected by <span class="highlight">escrow security</span> — freelancers get paid on time, and clients release funds once work is delivered.
                    </p>
                </div>

            </div>
        </section>

        <!-- CTA (instructions for a user to what to do next)-->
        <section class="bg-gray-100 py-20 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">Ready to Get Started?</h2>
            <p class="text-gray-600 mb-8">Join FreelanceHub today and unlock opportunities — whether you’re hiring or freelancing.</p>
            <a href="signup.php" class="btn-gradient">Sign Up Free</a>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t mt-10">
            <div class="max-w-7xl mx-auto px-6 py-6 text-center text-gray-500 text-sm">
                © 2025 FreelanceHub. All rights reserved.
            </div>
        </footer>

    </body>

</html>