document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".anime-card").forEach((card) => {
        card.addEventListener("mouseenter", () => {
            const overlay = document.createElement("div");
            overlay.classList.add("anime-overlay");
            overlay.style.backgroundImage = `url(${card.dataset.bg})`;
            overlay.style.backgroundSize = "cover";
            overlay.style.backgroundPosition = "center";
            overlay.style.position = "absolute";
            overlay.style.top = "0";
            overlay.style.left = "0";
            overlay.style.width = "100%";
            overlay.style.height = "100%";
            overlay.style.opacity = "1";
            overlay.style.zIndex = "1";
            overlay.style.pointerEvents = "none";
            card.style.position = "relative";
            card.appendChild(overlay);
        });

        card.addEventListener("mouseleave", () => {
            const overlay = card.querySelector(".anime-overlay");
            if (overlay) {
                overlay.remove();
            }
        });

        card.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent instant redirect

            const title = card.querySelector("h2").innerText;

            if (title.includes("Attack on Titan")) {
                document.body.classList.add("screen-shake");
                setTimeout(() => document.body.classList.remove("screen-shake"), 1500);
            } else if (title.includes("Dragon Ball")) {
                showDragonBallEffect();
            } else if (title.includes("Demon Slayer")) {
                showSwordSlashEffect();
            } else if (title.includes("Death Note")) {
                showDeathNoteEffect();
            } else if (title.includes("One Piece")) {
                showBubbleEffect();
            }

            setTimeout(() => {
                window.location.href = card.querySelector("a").href;
            }, 1500);
        });
    });
});

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
    0% {
        transform: rotate(0deg) scale(1);
        top: 50%;
        left: 50%;
        width: 100px;
        height: 100px;
    }
    100% {
        transform: rotate(360deg) scale(15);
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
    }
}

.dragon-ball-effect {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    background: url('dragonball.png') no-repeat center/contain;
    animation: spin-expand 3s ease-in-out forwards;
    pointer-events: none;
    z-index: 9999; /* Ensure the effect is on top */
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
        animation: sword-slash 2s ease-out forwards;
        z-index: 9999;
    }

    @keyframes fade-to-black {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }
    .death-note-fade {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: black;
        opacity: 0;
        animation: fade-to-black 2s ease-in-out forwards;
        z-index: 9999;
    }
`;
document.head.appendChild(style);

function showDragonBallEffect() {
    const ball = document.createElement("div");
    ball.classList.add("dragon-ball-effect");
    document.body.appendChild(ball);
    setTimeout(() => ball.remove(), 2000);
}

function showSwordSlashEffect() {
    const slash = document.createElement("div");
    slash.classList.add("sword-slash-effect");
    document.body.appendChild(slash);
    setTimeout(() => slash.remove(), 800);
}

function showDeathNoteEffect() {
    const overlay = document.createElement("div");
    overlay.classList.add("death-note-fade");
    document.body.appendChild(overlay);
    setTimeout(() => {
        window.location.href = document.querySelector(".anime-card a").href;
    }, 2000);
}

function showBubbleEffect() {
    for (let i = 0; i < 1000; i++) {
        const bubble = document.createElement("div");
        bubble.classList.add("bubble");
        bubble.style.bottom = "0px";
        bubble.style.left = `${Math.random() * 100}vw`; 
        bubble.style.animationDuration = `${2 + Math.random() * 3}s`;
        bubble.style.animationDelay = `${Math.random() * 1.5}s`;
        const size = `${10 + Math.random() * 30}px`;
        bubble.style.width = size;
        bubble.style.height = size;
        document.body.appendChild(bubble);
        setTimeout(() => bubble.remove(), 6000);
    }
}
