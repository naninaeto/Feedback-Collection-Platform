<?php
require_once 'auth.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$username = htmlspecialchars($_SESSION['username']);
$isAdmin = isAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full transform transition duration-300 hover:scale-[1.01]">
        <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-4">Welcome, <?= $username ?> </h2>
        <p class="text-center text-gray-600 mb-6">You’re logged in. What would you like to do?</p>

        <div class="space-y-4">
            <a href="userfeedback.php"
               class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg text-center text-lg font-semibold transition-all duration-300 shadow-sm">
                📝 Submit Feedback
            </a>

            <?php if ($isAdmin): ?>
                <a href="admin.php"
                   class="block w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg text-center text-lg font-semibold transition-all duration-300 shadow-sm">
                    🛠️ Admin Panel
                </a>
            <?php endif; ?>

            <a href="logout.php"
               class="block w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg text-center text-lg font-semibold transition-all duration-300 shadow-sm">
                🚪 Logout
            </a>
        </div>

        <footer class="mt-6 text-center text-sm text-gray-400">
            &copy; <?= date("Y") ?> Feedback Platform. All rights reserved.
        </footer>
    </div>
</body>
</html>
