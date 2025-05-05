<?php
require_once 'auth.php';
if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

$filter_user = $_GET['user'] ?? '';
$filter_date = $_GET['date'] ?? '';
$filter_status = $_GET['status'] ?? '';

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
<body class="bg-gradient-to-br from-indigo-100 to-white min-h-screen p-6">
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-2xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">🔧 Admin Panel - Feedback Management</h2>
        <div class="text-center mb-6">
            <a href="dashboard.php" class="text-blue-600 font-medium hover:underline">← Back to Dashboard</a>
        </div>

        <!-- Filter Form -->
        <form action="" method="GET" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="user">Filter by Username</label>
                    <input type="text" name="user" id="user" value="<?= htmlspecialchars($filter_user); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="date">Filter by Date</label>
                    <input type="date" name="date" id="date" value="<?= htmlspecialchars($filter_date); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="status">Filter by Status</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">All</option>
                        <option value="pending" <?= $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="reviewed" <?= $filter_status === 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow font-semibold transition duration-300">Apply Filters</button>
            </div>
        </form>

        <!-- Feedback Table -->
        <div class="overflow-x-auto rounded-lg">
            <table class="w-full table-auto border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="px-4 py-3 border-b">ID</th>
                        <th class="px-4 py-3 border-b">Username</th>
                        <th class="px-4 py-3 border-b">Feedback</th>
                        <th class="px-4 py-3 border-b">Date</th>
                        <th class="px-4 py-3 border-b">Status / Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php if ($feedbacks): ?>
                        <?php foreach ($feedbacks as $i => $feedback): ?>
                            <tr class="<?= $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' ?>">
                                <td class="px-4 py-3 border-b"><?= $feedback['id'] ?></td>
                                <td class="px-4 py-3 border-b"><?= htmlspecialchars($feedback['username']) ?></td>
                                <td class="px-4 py-3 border-b"><?= nl2br(htmlspecialchars($feedback['feedback'])) ?></td>
                                <td class="px-4 py-3 border-b"><?= $feedback['created_at'] ?></td>
                                <td class="px-4 py-3 border-b flex flex-col md:flex-row md:items-center gap-2">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full 
                                        <?= $feedback['status'] === 'reviewed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                        <?= ucfirst($feedback['status'] ?? 'pending') ?>
                                    </span>
                                    <?php if ($feedback['status'] !== 'reviewed'): ?>
                                        <form action="update_status.php" method="POST">
                                            <input type="hidden" name="feedback_id" value="<?= $feedback['id'] ?>">
                                            <button type="submit" name="status" value="reviewed"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">
                                                Mark as Reviewed
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center px-4 py-6 text-gray-500">No feedback found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
