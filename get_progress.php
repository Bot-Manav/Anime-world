<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT anime_name, episode_number FROM watch_progress WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'status' => 'success',
        'anime_name' => $row['anime_name'],
        'episode_number' => $row['episode_number']
    ]);
} else {
    echo json_encode(['status' => 'no_data']);
}

$stmt->close();
$conn->close();
?>
