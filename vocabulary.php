<?php
session_start();
include "db.php";

/* ====== GİRİŞ KONTROL ====== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userLevel = $_SESSION['level'];

/*
=================================================
 Vocabulary Testleri (Word Sprint+)
 Okul projesi için statik – DB uyumlu mantık
=================================================
*/
$vocabularyTests = [
    ["id" => 1, "title" => "Basic Everyday Words", "level" => "A1"],
    ["id" => 2, "title" => "Common Verbs", "level" => "A1"],
    ["id" => 3, "title" => "Travel Vocabulary", "level" => "A2"],
    ["id" => 4, "title" => "Work & Office Words", "level" => "A2"],
    ["id" => 5, "title" => "Academic Vocabulary", "level" => "B1"],
    ["id" => 6, "title" => "Advanced Expressions", "level" => "B2"]
];

/* ====== SEVİYE KARŞILAŞTIRMA ====== */
function levelValue($lvl) {
    return match ($lvl) {
        "A1" => 1,
        "A2" => 2,
        "B1" => 3,
        "B2" => 4,
        "C1" => 5,
        default => 1
    };
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vocabulary Practice | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>📗 Vocabulary Practice</h2>
<p>
    Improve your vocabulary with fast and focused word challenges.
</p>

<div class="test-list">

<?php foreach ($vocabularyTests as $test): ?>
    <?php
        $locked = levelValue($test['level']) > levelValue($userLevel);
    ?>

    <div class="card <?= $locked ? 'locked' : '' ?>">
        <h3><?= $test['title'] ?></h3>
        <p>Level: <?= $test['level'] ?></p>

        <?php if (!$locked): ?>
            <a 
                href="test_engine.php?type=vocabulary&test_id=<?= $test['id'] ?>" 
                class="btn-primary"
            >
                Start Sprint
            </a>
        <?php else: ?>
            <span>🔒 Locked</span>
        <?php endif; ?>
    </div>

<?php endforeach; ?>

</div>

<br>
<a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</body>
</html>
