<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId   = $_SESSION['user_id'];
$username = $_SESSION['username'];
$level    = $_SESSION['level'];

/* ======================
   XP & STREAK ÇEK
   ====================== */
$conn->query("
CREATE TABLE IF NOT EXISTS user_progress (
    user_id INT PRIMARY KEY,
    xp INT DEFAULT 0,
    streak INT DEFAULT 0,
    last_active DATE
)
");

$conn->query("
INSERT IGNORE INTO user_progress (user_id) VALUES ($userId)
");

$progress = $conn->query("
    SELECT xp, streak 
    FROM user_progress 
    WHERE user_id = $userId
")->fetch_assoc();

$xp     = $progress['xp'];
$streak = $progress['streak'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | FluentAI</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f6f7fb;
    margin:0;
}
.header{
    background:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 8px rgba(0,0,0,.1);
}
.container{
    max-width:1000px;
    margin:40px auto;
}
.stats{
    display:flex;
    gap:20px;
    margin-bottom:30px;
}
.stat-box{
    flex:1;
    background:white;
    padding:20px;
    border-radius:15px;
    text-align:center;
}
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}
.card{
    background:white;
    padding:25px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}
.card a{
    display:inline-block;
    margin-top:15px;
    padding:12px 20px;
    background:#58cc02;
    color:white;
    border-radius:10px;
    text-decoration:none;
}
.footer-links{
    text-align:center;
    margin-top:40px;
}
.footer-links a{
    margin:0 10px;
    color:#555;
    text-decoration:none;
}
.level{
    font-size:32px;
    color:#58cc02;
}


.profile{
    position:relative;
    cursor:pointer;
}
.avatar{
    width:80px;
    height:40px;
    border-radius:10%;
    right: 0;
    background:#58cc02;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}
.dropdown{
    display:none;
    position:absolute;
    right:0;
    top:50px;
    background:white;
    width:180px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
    z-index:100;
}
.dropdown a{
    display:block;
    padding:12px;
    text-decoration:none;
    color:#333;
}
.dropdown a:hover{
    background:#f2f2f2;
}




</style>
</head>

<body>

<div class="header">
    <p>Your current level: <span class="level"><?= $level ?></span></p>

    <div class="profile" onclick="toggleMenu()">
    <div class="avatar">Profile</div>
    <div class="dropdown" id="menu">
        <a href="profile.php">👤 My Profile</a>
        <a href="settings.php">⚙ Settings</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>


</div>

<div class="container">

    <!-- STATS -->
    <div class="stats">
        <div class="stat-box">
            <h3>⭐ XP</h3>
            <p style="font-size:24px;"><?= $xp ?></p>
        </div>
        <div class="stat-box">
            <h3>🔥 Streak</h3>
            <p style="font-size:24px;"><?= $streak ?> day(s)</p>
        </div>
    </div>

    <!-- MODULES -->
    <div class="cards">
        <div class="card">
            <h3>📘 Grammar</h3>
            <p>Improve your grammar step by step.</p>
            <a href="grammar.php">Start</a>
        </div>

        <div class="card">
            <h3>📗 Vocabulary</h3>
            <p>Expand your vocabulary with practice.</p>
            <a href="vocabulary.php">Start</a>
        </div>

        <div class="card">
            <h3>📕 Reading</h3>
            <p>Practice reading comprehension.</p>
            <a href="reading.php">Start</a>
        </div>

        <div class="card">
            <h3>🤖 AI Practice</h3>
            <p>Chat with AI and get instant feedback.</p>
            <a href="ai_practice.php">Start</a>
        </div>
    </div>

    <!-- EXTRA LINKS -->
    <div class="footer-links">
        <a href="review_mode.php">🔁 Review Mode</a> |
        <a href="planner.php">🗓 Study Planner</a> |
        <a href="progress.php">📊 Progress</a> |
        <a href="settings.php">⚙ Settings</a> |
        <a href="logout.php">🚪 Logout</a>
    </div>

</div>

</body>
</html>

<script>
function toggleMenu(){
    const menu = document.getElementById("menu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

document.addEventListener("click", function(e){
    if (!e.target.closest(".profile")) {
        const menu = document.getElementById("menu");
        if(menu) menu.style.display = "none";
    }
});
</script>
