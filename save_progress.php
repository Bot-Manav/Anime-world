<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$anime_name = $_POST['anime_name'] ?? '';
$episode_number = $_POST['episode_number'] ?? '';

if (empty($anime_name) || empty($episode_number)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

// Use prepared statement to insert or update
$sql = "INSERT INTO watch_progress (user_id, anime_name, episode_number) 
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE anime_name = VALUES(anime_name), episode_number = VALUES(episode_number), updated_at = CURRENT_TIMESTAMP";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isi", $user_id, $anime_name, $episode_number);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
?>
