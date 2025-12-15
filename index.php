<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Anime World</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="styles_m.css" />
  
</head>
<body>
  <header class="navbar">
    <div class="logo">AnimeWorld</div>

    <nav style="margin-right: 90px;">
      <a href="#">Home</a>
      <a href="#anime-list">Browse</a>
      <a href="#continue-watching">Continue</a>
    </nav>
  </header>

  <h1>Anime World</h1>

  <div class="top-bar">
    <div class="left-section">
      <p class="welcome-text" style="font-size: 25px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
       
    </div>
    <div class="center-section">
      <div class="search-box">
        <input type="text" id="search-input" placeholder="Search anime...">
        <span class="search-icon">&#128269;</span>
        <div id="suggestions" style="color: white;"></div>
      </div>
    </div>
    <div class="right-section">
      <a href="profile.php"><i class="fas fa-user nav-icon"></i></a>
      <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
  <!-- JS will populate this dynamically -->
</section>

      <div class="dropdown">
        <button class="filter-btn">Select Category</button>
        <div class="dropdown-content">
          <button class="filter-btn" data-category="all">All</button>
          <button class="filter-btn" data-category="Action">Action</button>
          <button class="filter-btn" data-category="Adventure">Adventure</button>
          <button class="filter-btn" data-category="Fantasy">Fantasy</button>
          <button class="filter-btn" data-category="Thriller">Thriller</button>
          <button class="filter-btn" data-category="Mystery">Mystery</button>
          <button class="filter-btn" data-category="Romance">Romance</button>
          <button class="filter-btn" data-category="Sci-Fi">Sci-Fi</button>
        </div>
      </div>
    </div>
  </div>

  <main id="anime-list">
    <?php
    $categories = [
      // category data stays unchanged
      "Action" => [
        ["AOT.jpg", "Attack on Titan", "Action, Drama"],
        ["D_S.jpg", "Demon Slayer", "Action, Fantasy"],
        ["mha.jpg", "My Hero Academia", "Action, Superpower"],
        ["TR.jpg", "Tokyo Revengers", "Action, Thriller"],
        ["bleach.jpg", "Bleach", "Action, Supernatural"],
        ["jjk.jpg", "Jujutsu Kaisen", "Action, Dark Fantasy"],
        ["naruto.jpg", "Naruto", "Action, Ninja"]
      ],
      "Adventure" => [
        ["O_P.jpg", "One Piece", "Adventure, Fantasy"],
        ["dragon.jpg", "Dragon Ball", "Adventure, Action"],
        ["hunter.jpg", "Hunter x Hunter", "Adventure, Shounen"],
        ["sao.jpg", "Sword Art Online", "Adventure, Sci-Fi"],
        ["magi.jpg", "Magi", "Adventure, Fantasy"]
      ],
      "Thriller" => [
        ["D_N.jpg", "Death Note", "Mystery, Thriller"],
        ["monster.jpg", "Monster", "Psychological, Thriller"],
        ["PA.jpg", "Paranoia Agent", "Thriller, Mystery"],
        ["tokyoghoul.jpg", "Tokyo Ghoul", "Thriller, Horror"]
      ],
      "Sci-Fi" => [
        ["SG.jpg", "Steins;Gate", "Sci-Fi, Thriller"],
        ["psycho.jpg", "Psycho-Pass", "Sci-Fi, Action"],
        ["evangelion.jpg", "Evangelion", "Sci-Fi, Mecha"],
        ["gintama.jpg", "Gintama", "Sci-Fi, Comedy"]
      ],
      "Romance" => [
        ["YourName.jpg", "Your Name", "Romance, Drama"],
        ["ASV.jpg", "A Silent Voice", "Romance, Drama"],
        ["toradora.jpg", "Toradora!", "Romance, Comedy"],
        ["clannad.jpg", "Clannad", "Romance, Slice of Life"]
      ]
    ];
    foreach ($categories as $category => $animes) {
  echo "<h2 class='category-heading'>{$category}</h2><div class='anime-grid'>";
  foreach ($animes as $anime) {
    [$img, $name, $genre] = $anime;
    $animeURL = urlencode($name);
    echo "<div class='anime-card' data-category='{$category}'>
        <a href='watch.php?anime={$animeURL}&episode=1' style='text-decoration: none; color: inherit;'>
          <img src='{$img}' alt='{$name}' />
          <div class='anime-info'>
            <h2 class='anime-title'>{$name}</h2>
            <p class='anime-genre'>Genre: {$genre}</p>
          </div>
        </a>
      </div>";

  }
  echo "</div>";
}

    ?>
  </main>

  <footer>
    <p>&copy; 2025 Anime Showcase. All rights reserved.</p>
  </footer>

  <!-- Scripts and interactions remain unchanged -->
   <script>
    


    // Category Filter
    document.querySelectorAll(".filter-btn").forEach(button => {
      button.addEventListener("click", () => {
        document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
        button.classList.add("active");
        const category = button.dataset.category;
        document.querySelectorAll(".anime-card").forEach(card => {
          card.style.display = category === "all" || card.dataset.category.toLowerCase().includes(category.toLowerCase()) ? "block" : "none";
        });
      });
    });


    document.addEventListener("DOMContentLoaded", () => {
  const dropdownBtn = document.querySelector(".dropdown > .filter-btn");
  const dropdownContent = document.querySelector(".dropdown-content");

  dropdownBtn.addEventListener("click", () => {
    dropdownContent.style.display =
      dropdownContent.style.display === "block" ? "none" : "block";
  });

  window.addEventListener("click", (e) => {
    if (!document.querySelector(".dropdown").contains(e.target)) {
      dropdownContent.style.display = "none";
    }
  });
});



    // Scroll animation
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        entry.target.classList.toggle("visible", entry.isIntersecting);
      });
    }, { threshold: 0.1 });

    document.querySelectorAll(".anime-card").forEach(card => observer.observe(card));

    // Load last watched anime
    function loadProgress() {
      fetch('get_progress.php')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            const continueDiv = document.getElementById('continue-watching');
            continueDiv.innerHTML = `
              <p>Last watched: <strong>${data.anime_name}</strong> - Episode ${data.episode_number}</p>
              <button onclick="window.location.href='watch.php?anime=${encodeURIComponent(data.anime_name)}&episode=${data.episode_number}'">
                Continue Watching
              </button>
            `;
          }
        });
    }

    window.onload = loadProgress;

    // Anime Effects
    document.querySelectorAll(".anime-card").forEach((card) => {
      card.addEventListener("click", (event) => {
        event.preventDefault();
        const title = card.querySelector("h2").innerText;
        const link = card.querySelector("a").href;
        let delay = 2500;

        if (title.includes("Attack on Titan")) {
          document.body.classList.add("screen-shake");
          setTimeout(() => document.body.classList.remove("screen-shake"), delay);
        } else if (title.includes("Dragon Ball")) {
          showDragonBallEffect();
        } else if (title.includes("Demon Slayer")) {
          showSwordSlashEffect();
        } else if (title.includes("Death Note")) {
          showDeathNoteEffect(link);
          return;
        } else if (title.includes("One Piece")) {
          showBubbleEffect();
        }

        setTimeout(() => {
          window.location.href = link;
        }, delay);
      });
    });

    function showDragonBallEffect() {
      const ball = document.createElement("div");
      ball.classList.add("dragon-ball-effect");
      document.body.appendChild(ball);
      setTimeout(() => ball.remove(), 2500);
    }

    function showSwordSlashEffect() {
      const slash = document.createElement("div");
      slash.classList.add("sword-slash-effect");
      document.body.appendChild(slash);
      setTimeout(() => slash.remove(), 1500);
    }

    function showDeathNoteEffect(link) {
      const overlay = document.createElement("div");
      overlay.classList.add("death-note-fade");
      document.body.appendChild(overlay);
      setTimeout(() => {
        window.location.href = link;
      }, 1500);
    }

    function showBubbleEffect() {
      for (let i = 0; i < 100; i++) {
        const bubble = document.createElement("div");
        bubble.classList.add("bubble");
        bubble.style.left = `${Math.random() * 100}vw`;
        bubble.style.animationDuration = `${3 + Math.random() * 4}s`;
        bubble.style.animationDelay = `${Math.random() * 1.5}s`;
        const size = `${10 + Math.random() * 30}px`;
        bubble.style.width = size;
        bubble.style.height = size;
        document.body.appendChild(bubble);
        setTimeout(() => bubble.remove(), 7000);
      }
    }

    // Add animation styles
    const style = document.createElement("style");
    style.innerHTML = `
    @keyframes shake {
      0%, 100% { transform: translate(0, 0); }
      25% { transform: translate(-10px, 5px); }
      50% { transform: translate(10px, -5px); }
      75% { transform: translate(-5px, 10px); }
    }
    .screen-shake { animation: shake 0.2s linear infinite; }

    @keyframes spin-expand {
      0% { transform: rotate(0deg) scale(1); top: 50%; left: 50%; width: 100px; height: 100px; }
      100% { transform: rotate(360deg) scale(15); top: 0; left: 0; width: 100vw; height: 100vh; }
    }
    .dragon-ball-effect {
      position: fixed;
      top: 50%; left: 50%;
      width: 100px; height: 100px;
      background: url('dragonball.png') no-repeat center/contain;
      animation: spin-expand 3s ease-in-out forwards;
      z-index: 9999;
      pointer-events: none;
    }

    @keyframes sword-slash {
      0% { transform: translateX(-100%) skewX(-30deg); opacity: 1; }
      100% { transform: translateX(100%) skewX(-30deg); opacity: 0; }
    }
    .sword-slash-effect {
      position: fixed;
      top: 50%;
      left: 0;
      width: 100vw;
      height: 30px;
      background: linear-gradient(to right, red, orange, yellow);
      box-shadow: 0 0 20px rgba(255, 100, 0, 0.8);
      animation: sword-slash 1.5s ease-out forwards;
      z-index: 9999;
    }

    @keyframes rising-bubbles {
      0% { transform: translateY(0); opacity: 1; }
      100% { transform: translateY(-100vh); opacity: 0; }
    }
    .bubble {
      position: fixed;
      bottom: 0;
      background: rgba(25, 0, 255, 0.85);
      border-radius: 50%;
      animation: rising-bubbles linear infinite;
      opacity: 0.8;
      pointer-events: none;
      z-index: 9999;
    }

    @keyframes fade-to-black {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }
    .death-note-fade {
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: black;
      opacity: 0;
      animation: fade-to-black 2.5s ease-in-out forwards;
      z-index: 9999;
    }
    `;
    document.head.appendChild(style);





    const searchInput = document.getElementById("search-input");
const suggestionsBox = document.getElementById("suggestions");

let animeNames = Array.from(document.querySelectorAll(".anime-card h2")).map(h2 => h2.innerText);

searchInput.addEventListener("input", () => {
  const query = searchInput.value.toLowerCase();
  suggestionsBox.innerHTML = "";
  if (query.length === 0) {
    suggestionsBox.style.display = "none";
    return;
  }

  const matches = animeNames.filter(name => name.toLowerCase().includes(query));
  if (matches.length > 0) {
    suggestionsBox.style.display = "block";
    matches.forEach(name => {
      const div = document.createElement("div");
      div.textContent = name;
      div.addEventListener("click", () => {
        const targetCard = Array.from(document.querySelectorAll(".anime-card")).find(card =>
          card.querySelector("h2").innerText === name
        );
        if (targetCard) {
          targetCard.scrollIntoView({ behavior: "smooth", block: "center" });
          targetCard.classList.add("highlight");
          setTimeout(() => targetCard.classList.remove("highlight"), 2000);
        }
        searchInput.value = name;
        suggestionsBox.style.display = "none";
      });
      suggestionsBox.appendChild(div);
    });
  } else {
    suggestionsBox.style.display = "none";
  }
});




 const dropdown = document.querySelector(".dropdown");
const dropdownBtn = dropdown.querySelector(".filter-btn");

dropdownBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  dropdown.classList.toggle("open");
});

window.addEventListener("click", (e) => {
  if (!dropdown.contains(e.target)) {
    dropdown.classList.remove("open");
  }
});

  </script>

<script>
  const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 150) { // adjust scroll distance
      navbar.classList.add('visible');
    } else {
      navbar.classList.remove('visible');
    }
  });
</script>


</body>
</html>
