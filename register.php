<?php
require_once 'auth.php';
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } else {
        try {
            if (registerUser($pdo, $username, $email, $password)) {
                $success = 'Registration successful! Please login.';
            } else {
                $error = 'Registration failed. Email or username may already be taken.';
            }
        } catch (PDOException $e) {
            $error = 'Registration failed. Email or username may already be taken.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="text-green-500 mb-4"><?php echo $success; ?></p>
        <?php endif; ?>
        <form id="registerForm" action="" method="POST" onsubmit="return validateRegister()">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="mt-1 p-2 w-full border rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 p-2 w-full border rounded-md" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 p-2 w-full border rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-center">Already have an account? <a href="index.php" class="text-blue-500">Login</a></p>
    </div>
    <script>
        function validateRegister() {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (username.length < 3) {
                alert('Username must be at least 3 characters long');
                return false;
            }
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }
            if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>