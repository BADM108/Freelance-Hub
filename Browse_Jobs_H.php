<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!-- Styles for the card and close button -->
<style>
    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 22px;
        color: #888;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.2s ease;
    }
    
    .close-btn:hover {
        color: #4f46e5;
        transform: scale(1.2);
    }
</style>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <!-- Card Container -->
    <div class=" relative bg-white rounded-xl shadow-lg p-10 max-w-md w-full text-center border border-gray-200">
        <a href="freelanceHubHome.php" class="close-btn">&times;</a>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Please sign in first <br>
            <span class="text-indigo-600">to start browsing jobs</span>
        </h1>
        <button onclick="window.location.href='signin.php';" class="px-6 py-3 text-base font-medium text-white rounded-lg shadow 
             bg-indigo-600 hover:bg-indigo-500 
             transition duration-200">
      Click here to sign in
    </button>
    </div>

</body>

</html>