<?php
session_start();
// Güvenlik: Giriş yapmamışsa index'e at
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit; 
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>AI Practice - English Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1920&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .overlay { background: rgba(0, 0, 0, 0.7); position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        
        /* Navbar */
        .navbar { background-color: rgba(0,0,0,0.8); z-index: 2; }
        .nav-link { color: #ccc; } .nav-link:hover { color: #fff; }

        /* Chat Kutusu */
        .chat-container {
            position: relative; z-index: 2; flex: 1; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .chat-card {
            width: 800px; height: 80vh; background: rgba(255, 255, 255, 0.95);
            border-radius: 15px; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .chat-header {
            padding: 15px; border-bottom: 1px solid #ddd; background: #f8f9fa; border-radius: 15px 15px 0 0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .chat-box {
            flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px;
        }
        .chat-input-area {
            padding: 15px; border-top: 1px solid #ddd; background: #fff; border-radius: 0 0 15px 15px; display: flex; gap: 10px;
        }

        /* Mesaj Baloncukları */
        .message { max-width: 70%; padding: 10px 15px; border-radius: 15px; font-size: 0.95rem; line-height: 1.4; }
        .message.bot { align-self: flex-start; background-color: #e9ecef; color: #333; border-bottom-left-radius: 2px; }
        .message.user { align-self: flex-end; background-color: #0d6efd; color: #fff; border-bottom-right-radius: 2px; }
        .correction { font-size: 0.8rem; color: #dc3545; margin-top: 5px; font-weight: bold; display: block; }
    </style>
</head>
<body>

<div class="overlay"></div>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🎓 English Master</a>
        <div class="d-flex text-white">
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Ana Menüye Dön</a>
        </div>
    </div>
</nav>

<div class="chat-container">
    <div class="chat-card">
        <div class="chat-header">
            <div>
                <h5 class="mb-0">🤖 AI Roleplay</h5>
                <small class="text-muted">Senaryo seç ve konuşmaya başla</small>
            </div>
            <select id="scenarioSelect" class="form-select w-auto form-select-sm">
                <option value="waiter">☕ Cafe (Ordering Coffee)</option>
                <option value="interview">💼 Job Interview</option>
                <option value="friend">👋 Meeting a New Friend</option>
            </select>
        </div>

        <div class="chat-box" id="chatBox">
            <div class="message bot">
                Hello! I am your waiter today. What would you like to drink?
            </div>
        </div>

        <div class="chat-input-area">
            <input type="text" id="userMessage" class="form-control" placeholder="Mesajını İngilizce yaz..." onkeypress="handleEnter(event)">
            <button class="btn btn-primary" onclick="sendMessage()">Gönder ➤</button>
        </div>
    </div>
</div>

<script>
    const chatBox = document.getElementById('chatBox');
    
    // Mesaj gönderme fonksiyonu
    function sendMessage() {
        const input = document.getElementById('userMessage');
        const message = input.value.trim();
        const scenario = document.getElementById('scenarioSelect').value;

        if (message === "") return;

        // 1. Kullanıcı mesajını ekrana bas
        appendMessage(message, 'user');
        input.value = ""; // Kutuyu temizle

        // 2. "AI yazıyor..." efekti
        const loadingId = appendLoading();

        // 3. Backend'e (PHP'ye) gönder
        fetch('chat_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(message) + '&scenario=' + encodeURIComponent(scenario)
        })
        .then(response => response.json())
        .then(data => {
            removeLoading(loadingId); // Yükleniyor'u kaldır
            // 4. AI cevabını ekrana bas
            appendMessage(data.reply, 'bot', data.correction);
        })
        .catch(error => {
            console.error('Hata:', error);
            removeLoading(loadingId);
            appendMessage("Sorry, I am having trouble connecting right now.", 'bot');
        });
    }

    function appendMessage(text, sender, correction = null) {
        const div = document.createElement('div');
        div.classList.add('message', sender);
        
        let content = text;
        if (correction) {
            content += `<span class="correction">💡 Correction: ${correction}</span>`;
        }
        
        div.innerHTML = content;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function appendLoading() {
        const id = 'loading-' + Date.now();
        const div = document.createElement('div');
        div.classList.add('message', 'bot');
        div.id = id;
        div.innerText = 'typing...';
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
        return id;
    }

    function removeLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }
</script>

</body>
</html>