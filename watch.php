<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Attack on Titan - Watch Episodes</title>
  <link rel="stylesheet" href="styles_ap.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: white;
      margin: 0;
      padding: 0;
    }

    header, footer {
      text-align: center;
      padding: 1rem;
      background-color: #1e1e1e;
    }

    main {
      padding: 20px;
    }

    .season-episodes {
      display: none;
      margin-top: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .video-box {
      background-color: #1f1f1f;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
      position: relative;
    }

    .video-box h3 {
      font-size: 16px;
      margin: 5px 0;
    }

    .video-box video {
      width: 100%;
      border-radius: 5px;
      max-height: 180px;
    }

    .now-watching {
      position: absolute;
      top: 8px;
      left: 8px;
      background-color: crimson;
      padding: 3px 6px;
      border-radius: 5px;
      font-size: 12px;
    }

    .video-popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.9);
  display: none;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  z-index: 1000;
}

.video-popup video {
  width: 90%;
  max-width: 1000px;
  height: auto;
  margin-bottom: 20px;
}

.video-popup button {
  padding: 10px 20px;
  background: crimson;
  color: white;
  border: none;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
}

    select {
      padding: 10px;
      font-size: 16px;
      margin-bottom: 20px;
      width: 100%;
    }

    a {
      display: block;
      margin-top: 20px;
      color: #66bfff;
      text-align: center;
    }

    @media screen and (max-width: 600px) {
      .video-box video {
        max-height: 120px;
      }

      .video-popup video {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <header>
    <h1>Attack on Titan - Watch Episodes</h1>
  </header>

  <main>
    <label for="seasonSelect"><strong>Select Season:</strong></label>
    <select id="seasonSelect">
      <option value="">-- Select Season --</option>
      <option value="season1">Season 1</option>
      <option value="season2">Season 2</option>
      <option value="season3">Season 3</option>
      <option value="season4">Season 4 (Final)</option>
    </select>

    <?php
    $videoLinks = [
      1 => ["https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4"],
      2 => ["https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4"],
      3 => ["https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4"],
      4 => ["https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4", "https://www.w3schools.com/html/mov_bbb.mp4", "https://www.w3schools.com/html/movie.mp4"]
    ];

    for ($season = 1; $season <= 4; $season++) {
        echo "<div id='season$season' class='season-episodes'>";
        foreach ($videoLinks[$season] as $index => $video) {
            $episode = $index + 1;
            echo "
            <div class='video-box' onclick=\"openPlayer('$video', 'Attack on Titan', $episode, 'now-watching-$season-$episode')\">
              <div class='now-watching' id='now-watching-$season-$episode' style='display:none;'>Now Watching</div>
              <h3>Season $season - Episode $episode</h3>
              <video muted>
                <source src='$video' type='video/mp4'>
              </video>
            </div>";
        }
        echo "</div>";
    }
    ?>
    <a href="index.php">⬅ Back to Anime Showcase</a>
  </main>

  <div id="videoPopup" class="video-popup" style="display:none;">
    <video id="fullscreenVideo" controls></video>
    <button onclick="closePlayer()">Close</button>
  </div>

  <footer>
    <p>&copy; 2025 Anime Showcase. All rights reserved.</p>
  </footer>

  <script>
    const seasonSelect = document.getElementById('seasonSelect');
    const seasonSections = document.querySelectorAll('.season-episodes');
    const videoPopup = document.getElementById('videoPopup');
    const fullscreenVideo = document.getElementById('fullscreenVideo');

    window.addEventListener('DOMContentLoaded', () => {
      seasonSections.forEach(section => section.style.display = 'none');

      // Optional: Auto-load last watched season
      fetch('get_last_progress.php')
        .then(res => res.json())
        .then(data => {
          const season = data.last_season;
          if (season) {
            const id = `season${season}`;
            seasonSelect.value = id;
            document.getElementById(id).style.display = 'grid';
          }
        }).catch(err => console.log("No last progress data"));
    });

    seasonSelect.addEventListener('change', () => {
      seasonSections.forEach(section => section.style.display = 'none');
      const selected = seasonSelect.value;
      if (selected) {
        document.getElementById(selected).style.display = 'grid';
      }
    });

  function openPlayer(src, animeName, episodeNumber, labelId) {
  fullscreenVideo.src = src;
  fullscreenVideo.play();
  videoPopup.style.display = 'flex';

  document.querySelectorAll('.now-watching').forEach(label => label.style.display = 'none');
  document.getElementById(labelId).style.display = 'inline-block';

  saveProgress(animeName, episodeNumber);
}

function closePlayer() {
  fullscreenVideo.pause();
  fullscreenVideo.src = "";
  videoPopup.style.display = 'none';
}


    function saveProgress(animeName, episodeNumber) {
      fetch('save_progress.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `anime_name=${encodeURIComponent(animeName)}&episode_number=${encodeURIComponent(episodeNumber)}`
      }).then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            console.log('Progress saved!');
          } else {
            console.error('Save failed:', data.message);
          }
        });
    }
  </script>


 
</body>
</html>