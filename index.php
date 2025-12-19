<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      <p class="welcome-text" style="font-size: 25px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
      </p>

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

        if (title.includes("Attack on Titan")) showTitanEffect();
        else if (title.includes("Dragon Ball")) showDragonBallEffect();
        else if (title.includes("Demon Slayer")) showSwordSlashEffect();
        else if (title.includes("Death Note") || title.includes("Monster") || title.includes("Paranoia Agent") || title.includes("Tokyo Ghoul")) {
          showDarkEffect(link);
          return;
        }
        else if (title.includes("One Piece")) showBubbleEffect();
        else if (title.includes("My Hero Academia")) showSmashEffect();
        else if (title.includes("Tokyo Revengers")) showGlitchEffect();
        else if (title.includes("Bleach")) showGetsugaEffect();
        else if (title.includes("Jujutsu Kaisen")) showCursedEffect();
        else if (title.includes("Naruto")) showRasenganEffect();
        else if (title.includes("Hunter x Hunter")) showNenEffect();
        else if (title.includes("Sword Art Online")) showLinkStartEffect();
        else if (title.includes("Magi")) showRukhEffect();
        else if (title.includes("Steins;Gate")) showTimeTravelEffect();
        else if (title.includes("Psycho-Pass")) showDominatorEffect();
        else if (title.includes("Evangelion")) showATFieldEffect();
        else if (title.includes("Gintama")) showComedyEffect();
        else if (['Your Name', 'A Silent Voice', 'Toradora', 'Clannad'].some(t => title.includes(t))) showRomanceEffect();
        else {
          window.location.href = link;
          return;
        }

        setTimeout(() => {
          window.location.href = link;
        }, delay);
      });
    });

    function showTitanEffect() {
      document.body.classList.add("screen-shake");
      setTimeout(() => document.body.classList.remove("screen-shake"), 2500);
    }

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

    function showDarkEffect(link) {
      const overlay = document.createElement("div");
      overlay.classList.add("death-note-fade");
      const text = document.createElement("div");
      text.classList.add("dark-text");
      text.innerText = "Target Eliminated...";
      overlay.appendChild(text);
      document.body.appendChild(overlay);
      setTimeout(() => { window.location.href = link; }, 2500);
    }

    // Reuse bubble effect but optimized inside this block
    function showBubbleEffect() {
      for (let i = 0; i < 50; i++) {
        const bubble = document.createElement("div");
        bubble.classList.add("bubble");
        bubble.style.left = `${Math.random() * 100}vw`;
        bubble.style.animationDuration = `${3 + Math.random() * 4}s`;
        bubble.style.width = `${10 + Math.random() * 30}px`;
        bubble.style.height = bubble.style.width;
        document.body.appendChild(bubble);
        setTimeout(() => bubble.remove(), 7000);
      }
    }

    function showSmashEffect() {
      const smash = document.createElement("div");
      smash.classList.add("smash-effect");
      smash.innerText = "SMASH!!";
      document.body.appendChild(smash);
      document.body.classList.add("heavy-shake");
      setTimeout(() => { smash.remove(); document.body.classList.remove("heavy-shake"); }, 2000);
    }

    function showGlitchEffect() {
      const overlay = document.createElement("div");
      overlay.classList.add("glitch-overlay");
      document.body.appendChild(overlay);
      setTimeout(() => overlay.remove(), 2000);
    }

    function showGetsugaEffect() {
      const wave = document.createElement("div");
      wave.classList.add("getsuga-wave");
      document.body.appendChild(wave);
      setTimeout(() => wave.remove(), 2000);
    }

    function showCursedEffect() {
      const energy = document.createElement("div");
      energy.classList.add("cursed-energy");
      document.body.appendChild(energy);
      setTimeout(() => energy.remove(), 2000);
    }

    function showRasenganEffect() {
      const rasengan = document.createElement("div");
      rasengan.classList.add("rasengan");
      document.body.appendChild(rasengan);
      setTimeout(() => rasengan.remove(), 2500);
    }

    function showNenEffect() {
      const aura = document.createElement("div");
      aura.classList.add("nen-aura");
      document.body.appendChild(aura);
      setTimeout(() => aura.remove(), 2500);
    }

    function showLinkStartEffect() {
      const tunnel = document.createElement("div");
      tunnel.classList.add("link-start-tunnel");
      tunnel.innerText = "LINK START";
      document.body.appendChild(tunnel);
      setTimeout(() => tunnel.remove(), 2500);
    }

    function showRukhEffect() {
      for (let i = 0; i < 60; i++) {
        const part = document.createElement("div");
        part.classList.add("rukh-particle");
        part.style.left = Math.random() * 100 + "vw";
        part.style.top = Math.random() * 100 + "vh";
        document.body.appendChild(part);
        setTimeout(() => part.remove(), 2000);
      }
    }

    function showTimeTravelEffect() {
      const overlay = document.createElement("div");
      overlay.classList.add("time-travel-overlay");
      document.body.appendChild(overlay);
      setTimeout(() => overlay.remove(), 2500);
    }

    function showDominatorEffect() {
      const scan = document.createElement("div");
      scan.classList.add("dominator-overlay");
      scan.innerHTML = "<div class='scan-line'></div><div class='crime-coef'>CRIME COEFFICIENT: OVER 300</div>";
      document.body.appendChild(scan);
      setTimeout(() => scan.remove(), 2500);
    }

    function showATFieldEffect() {
      const field = document.createElement("div");
      field.classList.add("at-field");
      document.body.appendChild(field);
      setTimeout(() => field.remove(), 2000);
    }

    function showComedyEffect() {
      const sweat = document.createElement("div");
      sweat.classList.add("comedy-effect");
      document.body.appendChild(sweat);
      setTimeout(() => sweat.remove(), 2000);
    }

    function showRomanceEffect() {
      for (let i = 0; i < 30; i++) {
        const heart = document.createElement("div");
        heart.classList.add("romance-heart");
        heart.style.left = Math.random() * 100 + "vw";
        heart.style.animationDuration = (2 + Math.random()) + "s";
        document.body.appendChild(heart);
        setTimeout(() => heart.remove(), 3000);
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
    style.innerHTML += `
    /* Additional Anime Effects */
    
    /* Dark / Thriller Text */
    .dark-text {
        font-family: 'Courier New', monospace; color: red;
        font-size: 2rem; opacity: 0;
        animation: fadeIn 2s forwards 0.5s;
    }
    @keyframes fadeIn { to {opacity:1;} }

    /* MHA Smash */
    @keyframes smash-zoom {
        0% { transform: scale(0.1); opacity: 0; }
        50% { transform: scale(1.5); opacity: 1; }
        100% { transform: scale(3); opacity: 0; }
    }
    .smash-effect {
        position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
        font-family: Impact, sans-serif; font-size: 10rem;
        color: #ff0000; -webkit-text-stroke: 4px yellow;
        animation: smash-zoom 0.5s ease-out forwards; z-index: 9999; pointer-events: none;
    }
    .heavy-shake { animation: shake 0.1s linear infinite; filter: blur(2px); }

    /* Tokyo Revengers Glitch */
    @keyframes glitch-anim {
        0% { clip-path: inset(40% 0 61% 0); transform: translate(-5px,0); }
        20% { clip-path: inset(92% 0 1% 0); transform: translate(5px,0); }
        40% { clip-path: inset(43% 0 1% 0); transform: translate(-5px,0); }
        60% { clip-path: inset(25% 0 58% 0); transform: translate(5px,0); }
        80% { clip-path: inset(54% 0 7% 0); transform: translate(-5px,0); }
        100% { clip-path: inset(58% 0 43% 0); transform: translate(5px,0); }
        100% { clip-path: inset(0 0 0 0); transform: translate(0,0); }
    }
    .glitch-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(0,0,0,0.2);
        z-index: 9999; pointer-events: none;
        animation: glitch-anim 0.2s infinite;
        backdrop-filter: invert(0.8);
    }

    /* Bleach Getsuga */
    @keyframes getsuga-slash {
        0% { transform: translateX(-100%) rotate(-45deg); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translateX(100%) rotate(-45deg); opacity: 0; }
    }
    .getsuga-wave {
        position: fixed; top: 0; left: 0; width: 200vw; height: 100vh;
        background: linear-gradient(transparent, black, red, black, transparent);
        animation: getsuga-slash 1.5s cubic-bezier(0.1, 0.7, 1.0, 0.1) forwards;
        z-index: 9999; pointer-events: none;
    }

    /* JJK Cursed Energy */
    @keyframes curse-flow {
        0% { filter: hue-rotate(0deg) contrast(150%); transform: scale(1); }
        50% { filter: hue-rotate(270deg) blur(5px); transform: scale(1.05); }
        100% { filter: hue-rotate(0deg) contrast(150%); transform: scale(1); }
    }
    .cursed-energy {
        position: fixed; top:0; left:0; width: 100vw; height: 100vh;
        background: radial-gradient(circle, transparent 30%, #4b0082 90%);
        mix-blend-mode: exclusion;
        animation: curse-flow 2s infinite; z-index: 9999; pointer-events: none;
    }

    /* Naruto Rasengan */
    @keyframes spin-rasengan {
        0% { transform: translate(-50%, -50%) rotate(0deg) scale(0); }
        20% { transform: translate(-50%, -50%) rotate(360deg) scale(1); }
        100% { transform: translate(-50%, -50%) rotate(3600deg) scale(15); opacity: 0; }
    }
    .rasengan {
        position: fixed; top: 50%; left: 50%;
        width: 150px; height: 150px;
        background: radial-gradient(circle, #fff, #00BFFF, #000080);
        border-radius: 50%;
        box-shadow: 0 0 60px #00BFFF;
        animation: spin-rasengan 2.5s ease-in forwards;
        z-index: 9999; pointer-events: none;
    }

    /* HxH Nen */
    .nen-aura {
        position: fixed; inset: 0;
        box-shadow: inset 0 0 100px 50px lime;
        filter: drop-shadow(0 0 20px gold);
        animation: shake 0.5s infinite;
        z-index: 9999; pointer-events: none;
    }

    /* SAO Link Start */
    @keyframes tunnel-dive {
        0% { transform: scale(1); opacity: 0; }
        20% { opacity: 1; }
        100% { transform: scale(5); opacity: 1; background: #fff;}
    }
    .link-start-tunnel {
        position: fixed; top:0; left:0; width: 100vw; height: 100vh;
        background: radial-gradient(circle, transparent 10%, #00ffff 10%, #000 20%);
        background-size: 100px 100px;
        display: flex; justify-content: center; align-items: center;
        color: white; font-size: 3rem; font-family: monospace;
        animation: tunnel-dive 3s linear forwards;
        z-index: 9999; pointer-events: none;
    }

    /* Magi Rukh */
    @keyframes float-up {
        to { transform: translateY(-100vh) rotate(360deg); opacity: 0; }
    }
    .rukh-particle {
        position: fixed; width: 20px; height: 20px;
        background: radial-gradient(circle, gold, transparent);
        box-shadow: 0 0 5px gold;
        border-radius: 50%;
        animation: float-up 2s linear forwards;
        z-index: 9999; pointer-events: none;
    }

    /* Steins;Gate */
    .time-travel-overlay {
        position: fixed; inset: 0; background: #fff;
        mix-blend-mode: difference;
        animation: glitch-anim 0.5s infinite;
        z-index: 9999; pointer-events: none;
    }

    /* Psycho-Pass */
    .dominator-overlay {
        position: fixed; inset: 0; border: 10px solid #00ffcc;
        background: rgba(0, 50, 50, 0.5);
        color: #00ffcc; font-family: sans-serif;
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        z-index: 9999; pointer-events: none;
    }
    .scan-line { width: 100%; height: 5px; background: #00ffcc; animation: slide-down 1s infinite; }
    @keyframes slide-down { from {margin-top: -50vh;} to {margin-top: 50vh;} }

    /* Evangelion AT Field */
    @keyframes hex-pulse {
        0% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1); }
        100% { opacity: 0; transform: scale(1.5); }
    }
    .at-field {
        position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 300px; height: 300px;
        background: rgba(255, 165, 0, 0.4);
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        border: 2px solid orange;
        animation: hex-pulse 1s ease-out infinite; z-index: 9999; pointer-events: none;
    }

    /* Comedy */
    .comedy-effect {
        position: fixed; top: 10%; right: 10%;
        width: 100px; height: 150px;
        background: radial-gradient(circle at 50% 100%, #00f, transparent);
        border-radius: 50% 50% 0 0;
        transform: rotate(-15deg);
        z-index: 9999; opacity: 0.8;
        animation: shake 0.5s infinite;
    }

    /* Romance */
    @keyframes heart-rise {
        to { transform: translateY(-100vh) scale(1.5); opacity: 0; }
    }
    .romance-heart {
        position: fixed; bottom: 0;
        width: 20px; height: 20px; background: pink; transform: rotate(45deg);
        box-shadow: 10px 10px 0 pink;
        z-index: 9999; pointer-events: none;
        animation: heart-rise 3s ease-in forwards;
    }
    .romance-heart::before, .romance-heart::after {
        content:''; position: absolute; width: 20px; height: 20px; background: pink; border-radius: 50%;
    }
    .romance-heart::before { top: -10px; left: 0; }
    .romance-heart::after { left: -10px; top: 0; }
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
