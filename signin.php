<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>
    <style>
        body {
            background-color: #f9fafb;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            position: relative;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 24px;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #4f46e5;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #4338ca;
        }
        
        .message {
            margin-top: 1rem;
            text-align: center;
            font-size: 14px;
            color: red;
        }
        
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
        
        .signup-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 14px;
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="signinS.php" method="POST">

        <h2>Sign In</h2>
        <a href="freelanceHubHome.php" class="close-btn">&times;</a>
        <input type="email" id="email" name="email" placeholder="Email" required />
        <input type="password" id="password" name="password" placeholder="Password" required />
        <button type="submit">Sign In</button>
        <p class="message" id="message"></p>
        <a href="signup.php" class="signup-link">Don't have an account? Click here to Sign Up</a>
        </form>
    </div>
    
</body>

</html>