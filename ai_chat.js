function sendMessage() {
    const input = document.getElementById("userMessage");
    const chatBox = document.getElementById("chatBox");
    const scenario = document.getElementById("scenario").value;

    const userText = input.value.trim();
    if (userText === "") return;

    // === Kullanıcı mesajı ===
    chatBox.innerHTML += `
        <div class="user-message">
            <strong>You:</strong> ${userText}
        </div>
    `;

    input.value = "";

    // === Basit AI analiz & feedback ===
    const analysis = analyzeSentence(userText, scenario);

    // === AI cevabı (roleplay'e uygun) ===
    setTimeout(() => {
        chatBox.innerHTML += `
            <div class="ai-message">
                <strong>AI:</strong> ${analysis.reply}
            </div>
        `;

        if (analysis.feedback.length > 0) {
            analysis.feedback.forEach(fb => {
                chatBox.innerHTML += `
                    <div class="ai-feedback">
                        💡 ${fb}
                    </div>
                `;
            });
        }

        chatBox.scrollTop = chatBox.scrollHeight;
    }, 700);
}

/* =================================================
   BASİT AI ANALİZ MOTORU (DEMO)
   ================================================= */
function analyzeSentence(text, scenario) {
    const lower = text.toLowerCase();
    let feedback = [];
    let reply = "Okay! Let's continue.";

    /* === ROLEPLAY CEVAPLARI === */
    if (scenario.includes("waiter")) {
        reply = "Sure! What would you like to order?";
    } else if (scenario.includes("interviewer")) {
        reply = "Interesting. Can you tell me more about that?";
    } else {
        reply = "Nice! Let’s keep talking 😊";
    }

    /* === GRAMMAR & VOCAB DÜZELTMELERİ === */

    // 1️⃣ Kibar istek
    if (lower.includes("i want")) {
        feedback.push(
            "Instead of **\"I want\"**, you can say **\"I would like\"** to sound more polite."
        );
    }

    // 2️⃣ Eksik article
    if (lower.includes("coffee") && !lower.includes("a coffee")) {
        feedback.push(
            "You can say **\"a coffee\"** instead of just **\"coffee\"**."
        );
    }

    // 3️⃣ Basit tense hatası
    if (lower.includes("he go")) {
        feedback.push(
            "Remember: with **he/she/it**, use **\"goes\"** → *He goes*."
        );
    }

    // 4️⃣ Çok kısa cevap
    if (text.length < 6) {
        feedback.push(
            "Try to give a longer answer to practice more vocabulary."
        );
    }

    // 5️⃣ Doğru ama geliştirilebilir cevap
    if (feedback.length === 0) {
        feedback.push(
            "Good sentence! You can try adding more details next time."
        );
    }

    return {
        reply,
        feedback
    };
}
