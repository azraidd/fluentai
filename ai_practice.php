<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userName = $_SESSION['username'] ?? "Student";

/*
Senaryo listesi (Admin panelden DB’ye taşınabilir)
Şimdilik sabit – okul projesi için yeterli
*/
$scenarios = [
    "restaurant" => "You are a waiter in a restaurant.",
    "job" => "You are an interviewer in a job interview.",
    "friend" => "You are a friendly person having a casual conversation."
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>AI Practice</title>
    <link rel="stylesheet" href="style.css">
    <script src="ai_chat.js" defer></script>
</head>
<body>

<h2>🤖 AI Practice</h2>
<p>Practice English with AI roleplay scenarios.</p>

<!-- ====== SENARYO SEÇİMİ ====== -->
<form id="scenarioForm">
    <label>Select Scenario:</label>
    <select id="scenario">
        <?php foreach ($scenarios as $key => $desc): ?>
            <option value="<?= $desc ?>"><?= ucfirst($key) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<!-- ====== CHAT ALANI ====== -->
<div class="chat-container">

    <div id="chatBox" class="chat-box">
        <div class="ai-message">
            <strong>AI:</strong> Hello <?= $userName ?>! Let’s start practicing. 😊
        </div>
    </div>

    <div class="chat-input">
        <input type="text" id="userMessage" placeholder="Type your message..." />
        <button onclick="sendMessage()">Send</button>
    </div>

</div>

<br>
<a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</body>
</html>
