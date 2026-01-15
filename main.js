/*
=================================================
 FluentAI - Main JavaScript File
=================================================
 Genel UI davranışları burada toplanır
=================================================
*/

document.addEventListener("DOMContentLoaded", () => {

    /* =========================================
       1️⃣ KİLİTLİ KARTLAR (LOCKED)
       ========================================= */
    const lockedCards = document.querySelectorAll(".locked");

    lockedCards.forEach(card => {
        card.addEventListener("click", (e) => {
            e.preventDefault();
            showToast("🔒 This content is locked for your level.");
        });
    });

    /* =========================================
       2️⃣ BUTON TIKLAMA ANİMASYONU
       ========================================= */
    const buttons = document.querySelectorAll(".btn-primary");

    buttons.forEach(btn => {
        btn.addEventListener("click", () => {
            btn.classList.add("btn-click");
            setTimeout(() => btn.classList.remove("btn-click"), 150);
        });
    });

    /* =========================================
       3️⃣ AUTO SCROLL (CHAT / TEST)
       ========================================= */
    const chatBox = document.getElementById("chatBox");
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    /* =========================================
       4️⃣ CONFIRM LOGOUT
       ========================================= */
    const logoutLink = document.querySelector("a[href='logout.php']");
    if (logoutLink) {
        logoutLink.addEventListener("click", (e) => {
            if (!confirm("Are you sure you want to logout?")) {
                e.preventDefault();
            }
        });
    }

});

/* =============================================
   5️⃣ TOAST MESSAGE (BİLDİRİM)
   ============================================= */
function showToast(message) {

    let toast = document.createElement("div");
    toast.className = "toast-message";
    toast.innerText = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}
