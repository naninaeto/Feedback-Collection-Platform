
<?php
require_once 'auth.php';
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (loginUser($pdo, $email, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal-custom': {
                            500: '#14b8a6',
                            600: '#0f766e',
                            700: '#0f4d47',
                        },
                        'purple-custom': {
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                        },
                        'error': '#dc2626',
                    },
                    backgroundImage: {
                        'gradient-custom': 'linear-gradient(135deg, #e0f2f1 0%, #ede9fe 100%)',
                    },
                },
            }
        }
    </script>
    <style>
        .login-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        .btn-loading {
            position: relative;
            pointer-events: none;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-teal-custom-50 to-purple-custom-50 flex items-center justify-center min-h-screen">
    <div class="login-card bg-white p-6 rounded-xl w-full max-w-md" role="main" aria-label="Login Form">
        <div class="flex justify-center mb-6">
            <!-- Placeholder for logo -->
            <div class="w-20 h-20 bg-teal-custom-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                L
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Login</h2>
        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-error p-3 mb-4 rounded" role="alert">
                <p class="text-sm text-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>
        <form id="loginForm" action="" method="POST" onsubmit="return validateLogin(event)">
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <div class="relative">
                    <input type="email" id="email" name="email" class="input-focus mt-1 p-2 w-full border rounded-md" required aria-describedby="email-error">
                    <span id="email-error" class="text-error text-sm hidden mt-1" role="alert"></span>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" class="input-focus mt-1 p-2 w-full border rounded-md" required aria-describedby="password-error">
                    <button type="button" id="togglePassword" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                    <span id="password-error" class="text-error text-sm hidden mt-1" role="alert"></span>
                </div>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="remember-me" name="remember-me" class="h-4 w-4 text-teal-custom-500 focus:ring-teal-custom-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember Me</label>
                </div>
                <a href="forgot-password.php" class="text-sm text-purple-custom-500 hover:text-purple-custom-600">Forgot Password?</a>
            </div>
            <button type="submit" id="loginButton" class="w-full bg-teal-custom-500 text-white p-2 rounded-md hover:bg-teal-custom-600 transition-colors duration-200">
                Login
            </button>
            <p class="mt-4 text-center text-sm text-gray-600">Don't have an account? <a href="register.php" class="text-purple-custom-500 hover:text-purple-custom-600">Register</a></p>
        </form>
    </div>
    <script>
        function validateLogin(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let isValid = true;

            // Reset error messages
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            emailError.textContent = '';
            passwordError.textContent = '';

            if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
                emailError.classList.remove('hidden');
                isValid = false;
            }
            if (password.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters long';
                passwordError.classList.remove('hidden');
                isValid = false;
            }

            if (isValid) {
                document.getElementById('loginButton').classList.add('btn-loading', 'bg-teal-custom-600');
                document.getElementById('loginForm').submit();
            }
            return false;
        }

        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
            this.querySelector('i').classList.toggle('fa-eye');
        });
    </script>
</body>
</html>
