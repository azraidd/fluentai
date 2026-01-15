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
 Reading Testleri
 (Okul projesi için statik tanım – mantık DB uyumlu)
=================================================
*/
$readingTests = [
    ["id" => 1, "title" => "Daily Life Text", "level" => "A1"],
    ["id" => 2, "title" => "Simple News Article", "level" => "A1"],
    ["id" => 3, "title" => "Short Story", "level" => "A2"],
    ["id" => 4, "title" => "Opinion Paragraph", "level" => "A2"],
    ["id" => 5, "title" => "Article Analysis", "level" => "B1"],
    ["id" => 6, "title" => "Advanced Reading", "level" => "B2"]
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
    <title>Reading Practice | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>📖 Reading Practice</h2>
<p>Read the texts and answer comprehension questions.</p>

<div class="test-list">

<?php foreach ($readingTests as $test): ?>
    <?php
        $locked = levelValue($test['level']) > levelValue($userLevel);
    ?>

    <div class="card <?= $locked ? 'locked' : '' ?>">
        <h3><?= $test['title'] ?></h3>
        <p>Level: <?= $test['level'] ?></p>

        <?php if (!$locked): ?>
            <a 
                href="test_engine.php?type=reading&test_id=<?= $test['id'] ?>" 
                class="btn-primary"
            >
                Start Reading
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
