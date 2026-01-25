<?php
error_reporting(0);
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$userId = $_SESSION['user_id'];

// Seviyeleri Çek
$stmt = $pdo->prepare("SELECT vocab_level, grammar_level, reading_level FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$vocabLvl = $user['vocab_level'] ?? 'A1';
$grammarLvl = $user['grammar_level'] ?? 'A1';
$readingLvl = $user['reading_level'] ?? 'A1';

function getLvlVal($l) { return match($l) { "A1"=>1, "A2"=>2, "B1"=>3, "B2"=>4, "C1"=>5, default=>1 }; }
$minVal = min(getLvlVal($vocabLvl), getLvlVal($grammarLvl), getLvlVal($readingLvl));
$overallLevel = match($minVal) { 1=>"A1", 2=>"A2", 3=>"B1", 4=>"B2", 5=>"C1", default=>"A1" };

// Tamamlananları Çek
$resStmt = $pdo->prepare("SELECT category FROM user_results WHERE user_id = ? AND category LIKE 'reading-%'");
$resStmt->execute([$userId]);
$completedRaw = $resStmt->fetchAll(PDO::FETCH_COLUMN);
$completedSets = [];
foreach($completedRaw as $c) {
    $parts = explode('-', $c); if(isset($parts[1])) $completedSets[] = intval($parts[1]);
}

try {
    $stmt = $pdo->query("SELECT * FROM reading_passages ORDER BY id ASC");
    $passages = $stmt->fetchAll();
} catch (PDOException $e) { die("Veritabanı Hatası."); }

$userReadingVal = getLvlVal($readingLvl);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reading Library - English Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            color: white; 
            min-height: 100vh; 
            padding-bottom: 50px; 
            
            /* --- READING TEMA: BÜYÜLÜ KÜTÜPHANE --- */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Kartlar daha okunaklı olsun diye yarı saydam siyah */
        .reading-card { 
            background: rgba(0, 0, 0, 0.6); 
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1); 
            border-radius: 12px; 
            padding: 25px; 
            text-align: center; 
            height: 100%; 
            display: flex; flex-direction: column; justify-content: space-between; 
            transition: 0.3s; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .reading-card.unlocked:hover { transform: translateY(-5px); background: rgba(0, 0, 0, 0.8); border-color: #ffc107; }
        .reading-card.locked { background: rgba(0, 0, 0, 0.4); color: #6c757d; border-style: dashed; cursor: not-allowed; }
        .reading-card.completed { background: rgba(25, 135, 84, 0.2); border: 1px solid #198754; color: white; cursor: default; }
        .level-badge { background: #ffc107; color: black; padding: 5px 12px; border-radius: 15px; font-weight: bold; margin-bottom: 10px; display: inline-block; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold">📖 Reading Library</h2>
            <p class="mb-0 fs-5">
                <span class="badge bg-secondary border border-light">Overall: <?= $overallLevel ?></span> 
                <span class="badge bg-warning text-dark border border-warning">Reading: <?= $readingLvl ?></span>
            </p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-light">⬅ Dashboard</a>
    </div>

    <div class="row g-4">
        <?php foreach($passages as $p): ?>
            <?php 
                $pid = $p['id']; $lvl = $p['cefr_level']; $title = $p['title'];
                $isLocked = $userReadingVal < getLvlVal($lvl);
                $isCompleted = in_array($pid, $completedSets);
            ?>
            <div class="col-md-4">
                <?php if ($isCompleted): ?>
                    <div class="reading-card completed">
                        <div><span class="level-badge" style="background:#198754;color:white"><?= $lvl ?></span><h5><?= htmlspecialchars($title) ?></h5><h1>✅</h1><p class="small text-white-50">COMPLETED</p></div>
                    </div>
                <?php elseif ($isLocked): ?>
                    <div class="reading-card locked">
                        <div><span class="level-badge" style="background:#495057;color:#adb5bd"><?= $lvl ?></span><h5><?= htmlspecialchars($title) ?></h5><h1>🔒</h1><p class="small">Reach Reading <?= $lvl ?></p></div>
                    </div>
                <?php else: ?>
                    <div class="reading-card unlocked">
                        <div><span class="level-badge"><?= $lvl ?></span><h5><?= htmlspecialchars($title) ?></h5></div>
                        <a href="reading_view.php?id=<?= $pid ?>" class="btn btn-primary w-100 rounded-pill mt-3">Start Reading</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>