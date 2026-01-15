/*
=================================================
 FluentAI - Test Engine JavaScript
=================================================
 Grammar, Vocabulary, Reading, Review, Placement
=================================================
*/

let testTimer;
let timeLeft;

/* =============================================
   1️⃣ TIMER BAŞLAT
   ============================================= */
function startTimer(seconds) {
    const timerBox = document.getElementById("timer");

    if (!timerBox) return;

    timeLeft = seconds;
    timerBox.innerText = formatTime(timeLeft);

    testTimer = setInterval(() => {
        timeLeft--;
        timerBox.innerText = formatTime(timeLeft);

        if (timeLeft <= 0) {
            clearInterval(testTimer);
            autoSubmitTest();
        }
    }, 1000);
}

/* =============================================
   2️⃣ ZAMANI FORMATLA
   ============================================= */
function formatTime(sec) {
    let m = Math.floor(sec / 60);
    let s = sec % 60;
    return `${m}:${s < 10 ? "0" + s : s}`;
}

/* =============================================
   3️⃣ TESTİ OTOMATİK GÖNDER
   ============================================= */
function autoSubmitTest() {
    const form = document.querySelector("form");
    if (!form) return;

    alert("⏰ Time is up! Test submitted automatically.");
    form.submit();
}

/* =============================================
   4️⃣ CEVAP SEÇİMİ HIGHLIGHT
   ============================================= */
document.addEventListener("change", (e) => {

    if (e.target.type === "radio") {

        const name = e.target.name;
        const options = document.querySelectorAll(`input[name="${name}"]`);

        options.forEach(opt => {
            opt.parentElement.classList.remove("selected-answer");
        });

        e.target.parentElement.classList.add("selected-answer");
    }
});

/* =============================================
   5️⃣ SAYFADAN ÇIKMA KORUMASI
   ============================================= */
window.addEventListener("beforeunload", function (e) {
    const form = document.querySelector("form");
    if (!form) return;

    e.preventDefault();
    e.returnValue = "Your test progress will be lost.";
});

/* =============================================
   6️⃣ TEST GÖNDERİLDİKTEN SONRA KORUMAYI KALDIR
   ============================================= */
document.addEventListener("submit", () => {
    window.removeEventListener("beforeunload", () => {});
});

/* =============================================
   7️⃣ SAYFA YÜKLENİNCE TIMER OTOMATİK BAŞLAT
   ============================================= */
document.addEventListener("DOMContentLoaded", () => {

    /*
    Grammar Sprint → 60 sn
    Vocabulary Sprint → 30 sn
    Reading / Review / Placement → timer yok
    */

    const testType = document.body.getAttribute("data-test-type");

    if (testType === "grammar") {
        startTimer(60);
    }

    if (testType === "vocabulary") {
        startTimer(30);
    }

});
