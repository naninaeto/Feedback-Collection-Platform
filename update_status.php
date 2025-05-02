<?php
require_once 'auth.php';
if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'], $_POST['status'])) {
    $feedback_id = $_POST['feedback_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE feedback SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $feedback_id])) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Failed to update status";
    }
}
?>
