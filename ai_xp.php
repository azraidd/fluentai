<?php
session_start();
include "db.php";

$userId = $_SESSION['user_id'];
$today = date("Y-m-d");

$conn->query("
INSERT IGNORE INTO user_progress (user_id, xp, streak, last_active)
VALUES ($userId, 0, 0, '$today')
");

$progress = $conn->query("SELECT * FROM user_progress WHERE user_id=$userId")->fetch_assoc();

$streak = $progress['streak'];
if($progress['last_active'] !== $today){
    $streak = ($progress['last_active'] === date("Y-m-d", strtotime("-1 day"))) ? $streak+1 : 1;
}

$conn->query("
UPDATE user_progress
SET xp = xp + 10, streak = $streak, last_active='$today'
WHERE user_id=$userId
");
