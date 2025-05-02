<?php
require_once 'auth.php';
if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = trim($_POST['feedback']);
    if (empty($feedback)) {
        $error = 'Feedback cannot be empty';
    } elseif (strlen($feedback) > 500) {
        $error = 'Feedback must be 500 characters or less';
    } else {
        $stmt = $pdo->prepare("INSERT INTO feedback (user_id, feedback, created_at) VALUES (?, ?, NOW())");
        if ($stmt->execute([$_SESSION['user_id'], $feedback])) {
            $success = 'Feedback submitted successfully!';
        } else {
            $error = 'Failed to submit feedback';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Submit Feedback</h2>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="text-green-500 mb-4"><?php echo $success; ?></p>
        <?php endif; ?>
        <form id="feedbackForm" action="" method="POST" onsubmit="return validateFeedback()">
            <div class="mb-4">
                <label for="feedback" class="block text-sm font-medium text-gray-700">Your Feedback</label>
                <textarea id="feedback" name="feedback" class="mt-1 p-2 w-full border rounded-md" rows="5" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Submit</button>
        </form>
        <a href="dashboard.php" class="block mt-4 text-center text-blue-500">Back to Dashboard</a>
    </div>
    <script>
        function validateFeedback() {
            const feedback = document.getElementById('feedback').value.trim();
            if (feedback.length === 0) {
                alert('Feedback cannot be empty');
                return false;
            }
            if (feedback.length > 500) {
                alert('Feedback must be 500 characters or less');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>