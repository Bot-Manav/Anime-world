<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Redirect to login page with success message
        header("Location: login.html?registered=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
