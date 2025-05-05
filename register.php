
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
    $terms = isset($_POST['terms']) ? 1 : 0;
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif (!$terms) {
        $error = 'You must agree to the Terms of Service';
    } else {
        try {
            if (registerUser($pdo, $username, $email, $password)) {
                $success = 'Registration successful! Please <a href="index.php" class="text-purple-custom-500 hover:text-purple-custom-600">login</a>.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        'success': '#10b981',
                    },
                    backgroundImage: {
                        'gradient-custom': 'linear-gradient(135deg, #e0f2f1 0%, #ede9fe 100%)',
                    },
                },
            }
        }
    </script>
    <style>
        .register-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card:hover {
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
    <div class="register-card bg-white p-6 rounded-xl w-full max-w-md" role="main" aria-label="Registration Form">
        <div class="flex justify-center mb-6">
            <!-- Placeholder for logo -->
            <div class="w-20 h-20 bg-teal-custom-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                R
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Register</h2>
        <?php if ($error): ?>
            <div class="bg-red-50 border-l-4 border-error p-3 mb-4 rounded" role="alert">
                <p class="text-sm text-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-50 border-l-4 border-success p-3 mb-4 rounded" role="alert">
                <p class="text-sm text-success"><?php echo $success; ?></p>
            </div>
        <?php endif; ?>
        <form id="registerForm" action="" method="POST" onsubmit="return validateRegister(event)">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="relative">
                    <input type="text" id="username" name="username" class="input-focus mt-1 p-2 w-full border rounded-md" required aria-describedby="username-error">
                    <span id="username-error" class="text-error text-sm hidden mt-1" role="alert"></span>
                </div>
            </div>
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
            <div class="flex items-center mb-4">
                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-teal-custom-500 focus:ring-teal-custom-500 border-gray-300 rounded" required aria-describedby="terms-error">
                <label for="terms" class="ml-2 block text-sm text-gray-900">I agree to the <a href="#" class="text-purple-custom-500 hover:text-purple-custom-600">Terms of Service</a></label>
                <span id="terms-error" class="text-error text-sm hidden ml-2" role="alert"></span>
            </div>
            <button type="submit" id="registerButton" class="w-full bg-teal-custom-500 text-white p-2 rounded-md hover:bg-teal-custom-600 transition-colors duration-200">
                Register
            </button>
            <p class="mt-4 text-center text-sm text-gray-600">Already have an account? <a href="index.php" class="text-purple-custom-500 hover:text-purple-custom-600">Login</a></p>
        </form>
    </div>
    <script>
        function validateRegister(event) {
            event.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const terms = document.getElementById('terms').checked;
            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const termsError = document.getElementById('terms-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let isValid = true;

            // Reset error messages
            usernameError.classList.add('hidden');
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            termsError.classList.add('hidden');
            usernameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';
            termsError.textContent = '';

            if (username.length < 3) {
                usernameError.textContent = 'Username must be at least 3 characters long';
                usernameError.classList.remove('hidden');
                isValid = false;
            }
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
            if (!terms) {
                termsError.textContent = 'You must agree to the Terms of Service';
                termsError.classList.remove('hidden');
                isValid = false;
            }

            if (isValid) {
                document.getElementById('registerButton').classList.add('btn-loading', 'bg-teal-custom-600');
                document.getElementById('registerForm').submit();
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
