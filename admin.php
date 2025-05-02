

<?php
require_once 'auth.php';
if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

// Fetch feedback with filters
$filter_user = isset($_GET['user']) ? $_GET['user'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT f.id, f.feedback, f.created_at, f.status, u.username 
          FROM feedback f 
          JOIN users u ON f.user_id = u.id 
          WHERE 1=1";
$params = [];
if ($filter_user) {
    $query .= " AND u.username LIKE ?";
    $params[] = "%$filter_user%";
}
if ($filter_date) {
    $query .= " AND DATE(f.created_at) = ?";
    $params[] = $filter_date;
}
if ($filter_status) {
    $query .= " AND f.status = ?";
    $params[] = $filter_status;
}
$query .= " ORDER BY f.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$feedbacks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Panel - Feedback Management</h2>
        <a href="dashboard.php" class="block mb-4 text-blue-500 text-center">Back to Dashboard</a>
        
        <!-- Filter Form -->
        <form action="" method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700">Filter by Username</label>
                    <input type="text" id="user" name="user" value="<?php echo htmlspecialchars($filter_user); ?>" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Filter by Date</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                    <select id="status" name="status" class="mt-1 p-2 w-full border rounded-md">
                        <option value="">All</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="reviewed" <?php echo $filter_status === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-4 w-full md:w-auto bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Apply Filters</button>
        </form>

        <!-- Feedback Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Username</th>
                        <th class="px-4 py-2">Feedback</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $feedback['id']; ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($feedback['username']); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($feedback['feedback']); ?></td>
                            <td class="border px-4 py-2"><?php echo $feedback['created_at']; ?></td>
                            <td class="border px-4 py-2">
                                <?php echo isset($feedback['status']) ? $feedback['status'] : 'pending'; ?>
                                <form action="update_status.php" method="POST" class="inline">
                                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                                    <button type="submit" name="status" value="reviewed" class="ml-2 text-blue-500 hover:underline">
                                        Mark as Reviewed
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($feedbacks)): ?>
                        <tr>
                            <td colspan="5" class="border px-4 py-2 text-center">No feedback found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>