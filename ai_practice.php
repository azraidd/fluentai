<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$level = $_SESSION['level'];
?>

<!DOCTYPE html>
<html>
<head>
<title>AI Roleplay | FluentAI</title>
<style>
body{font-family:Arial;background:#f6f7fb}
.container{max-width:800px;margin:40px auto}
.chat-box{background:white;height:400px;padding:20px;overflow-y:auto;border-radius:12px}
.user{color:#333;margin:10px 0}
.ai{color:#58cc02;margin:10px 0}
.feedback{background:#f0f9eb;padding:10px;border-radius:8px;font-size:14px}
.controls{display:flex;gap:10px;margin-top:15px}
input{flex:1;padding:12px;border-radius:8px;border:1px solid #ccc}
button{padding:12px 20px;border:none;background:#58cc02;color:white;border-radius:8px}
select{padding:10px;border-radius:8px}
</style>
</head>

<body>
<div class="container">
<h2>🤖 AI Roleplay</h2>
<p>Your level: <strong><?= $level ?></strong></p>

<select id="scenario">
    <option value="cafe">☕ Cafe Order</option>
    <option value="job">💼 Job Interview</option>
    <option value="travel">✈️ Travel Conversation</option>
</select>

<div class="chat-box" id="chat"></div>

<div class="controls">
    <input id="msg" placeholder="Type your message...">
    <button onclick="sendMessage()">Send</button>
</div>

<button style="margin-top:15px;background:#444" onclick="endRoleplay()">End Roleplay</button>
</div>

<script>
let msgCount = 0;

function sendMessage(){
    const chat = document.getElementById("chat");
    const input = document.getElementById("msg");
    const scenario = document.getElementById("scenario").value;
    const text = input.value.trim();
    if(!text) return;

    chat.innerHTML += `<div class="user"><b>You:</b> ${text}</div>`;
    input.value="";
    msgCount++;

    let aiReply = "Okay.";
    let feedback = "";

    // ==== SEVİYEYE GÖRE AI ====
    if("<?= $level ?>" === "A1"){
        aiReply = "Okay. Please continue.";
    } else if("<?= $level ?>" === "B1"){
        aiReply = "That sounds good. Can you explain more?";
    } else {
        aiReply = "Interesting point. Could you elaborate further?";
    }

    // ==== FEEDBACK ====
    if(text.toLowerCase().includes("i want coffee")){
        feedback = "💡 Better say: <i>I would like a coffee, please.</i>";
    }

    setTimeout(()=>{
        chat.innerHTML += `<div class="ai"><b>AI:</b> ${aiReply}</div>`;
        if(feedback){
            chat.innerHTML += `<div class="feedback">${feedback}</div>`;
        }
        chat.scrollTop = chat.scrollHeight;
    },600);

    // ==== XP (her 5 mesaj) ====
    if(msgCount % 5 === 0){
        fetch("ai_xp.php");
    }
}

function endRoleplay(){
    const chat = document.getElementById("chat");
    chat.innerHTML += `
        <hr>
        <div class="feedback">
        <b>Session Summary</b><br>
        ✔ Good participation<br>
        ✨ Try using more polite forms<br>
        📈 Keep practicing at <?= $level ?> level
        </div>
    `;
}
</script>

</body>
</html>
