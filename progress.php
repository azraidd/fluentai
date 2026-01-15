<?php
session_start();
include "db.php";

/* ====== GİRİŞ KONTROL ====== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$level = $_SESSION['level'];

/* ====== SKILL ORTALAMALARI ====== */

// Grammar
$grammar = $conn->query("
    SELECT 
        COUNT(*) as total,
        AVG(score) as avg_score
    FROM grammar_results
    WHERE user_id = $userId
")->fetch_assoc();

// (Vocabulary & Reading ileride eklenecek – placeholder mantık)
$vocabAvg = 0;
$readingAvg = 0;

/* ====== SEVİYE BAR HESABI ====== */
$levelMap = [
    "A1" => 20,
    "A2" => 40,
    "B1" => 60,
    "B2" => 80,
    "C1" => 100
];
$levelProgress = $levelMap[$level] ?? 20;

/* ====== HAFTALIK PERFORMANS ====== */
$weekly = $conn->query("
    SELECT 
        DATE(created_at) as day,
        AVG(score) as avg_score
    FROM grammar_results
    WHERE user_id = $userId
      AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Progress | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>📊 My Progress</h2>
<p>Hello <strong><?= htmlspecialchars($username) ?></strong>, here is your learning progress.</p>

<!-- ====== LEVEL BAR ====== -->
<h3>Current Level: <?= $level ?></h3>
<div class="progress-bar">
    <div class="progress-fill" style="width: <?= $levelProgress ?>%"></div>
</div>
<p><?= $levelProgress ?>% towards next level</p>

<hr>

<!-- ====== SKILL SCORES ====== -->
<h3>Skill Overview</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>Skill</th>
        <th>Completed Tests</th>
        <th>Average Score</th>
    </tr>
    <tr>
        <td>Grammar</td>
        <td><?= $grammar['total'] ?? 0 ?></td>
        <td><?= round($grammar['avg_score'] ?? 0, 1) ?>/10</td>
    </tr>
    <tr>
        <td>Vocabulary</td>
        <td>—</td>
        <td><?= $vocabAvg ?>/10</td>
    </tr>
    <tr>
        <td>Reading</td>
        <td>—</td>
        <td><?= $readingAvg ?>/10</td>
    </tr>
</table>

<hr>

<!-- ====== HAFTALIK AKTİVİTE ====== -->
<h3>Weekly Activity (Last 7 Days)</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>Date</th>
        <th>Average Grammar Score</th>
    </tr>

    <?php if ($weekly->num_rows > 0): ?>
        <?php while ($row = $weekly->fetch_assoc()): ?>
            <tr>
                <td><?= $row['day'] ?></td>
                <td><?= round($row['avg_score'], 1) ?>/10</td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="2">No activity this week.</td>
        </tr>
    <?php endif; ?>
</table>

<hr>

<!-- ====== RAPOR İNDİRME (FR20) ====== -->
<form method="post" action="download_report.php">
    <button type="submit" class="btn-primary">
        📄 Download Weekly Progress (PDF)
    </button>
</form>

<br>
<a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</body>
</html>
