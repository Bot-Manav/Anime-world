<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.html');
  exit();
}
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Anime World</title>
  <link rel="stylesheet" href="styles_m.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #0f0f0f, #1a1a1a);
      color: #f5f5f5;
      margin: 0;
      padding-top: 100px;
    }

    .profile-container {
      max-width: 600px;
      margin: 0 auto;
      padding: 30px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      box-shadow: 0 8px 32px rgba(0,0,0,0.4);
      text-align: center;
    }

    .profile-container h2 {
      margin-bottom: 20px;
    }

    .profile-info {
      font-size: 1.1em;
      margin: 20px 0;
    }

    .logout-btn {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1em;
      cursor: pointer;
    }

    .logout-btn:hover {
      background-color: #2563eb;
    }

    .navbar{
      padding: 20px 0px;
    }

  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo">AnimeWorld</div>
    <nav>
      <a href="index.php">Home</a>
      <a href="index.php#anime-list">Browse</a>
      <a href="index.php#continue-watching">Continue</a>
    </nav>
  </header>

  <div class="profile-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

    <div class="profile-info">
      <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
      <p><strong>Email:</strong> (Coming soon)</p>
      <p><strong>Watch history:</strong> (Coming soon)</p>
    </div>

    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
  </div>
</body>
</html>
