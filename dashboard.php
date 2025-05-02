<?php
require_once 'auth.php';
if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="text-center mb-4">This is your dashboard.</p>
        <a href="userfeedback.php" class="block mb-4 w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 text-center">Submit Feedback</a>
        <?php if (isAdmin()): ?>
            <a href="admin.php" class="block mb-4 w-full bg-green-500 text-white p-2 rounded-md hover:bg-green-600 text-center">Admin Panel</a>
        <?php endif; ?>
        <a href="logout.php" class="block w-full bg-red-500 text-white p-2 rounded-md hover:bg-red-600 text-center">Logout</a>
    </div>
</body>
</html>