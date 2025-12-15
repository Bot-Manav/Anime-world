<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            // Save both username and user ID to session
            $_SESSION["username"] = $user["username"];
            $_SESSION["user_id"] = $user["id"];  // Assuming your users table has an 'id' column

            // Successful login, redirect to main page
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found.'); window.history.back();</script>";
        exit();
    }
} else {
    // If accessed directly without POST, redirect to login form
    header("Location: login.html");
    exit();
}
?>
