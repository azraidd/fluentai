<?php
session_start();
include "db.php";

/* ====== GİRİŞ KONTROL ====== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

/*
=================================================
 1) KULLANICININ ZAYIF GRAMMAR TAG’LERİNİ BUL
=================================================
*/
$weakTagsResult = $conn->query("
    SELECT wrong_tags 
    FROM grammar_results
    WHERE user_id = $userId
      AND wrong_tags IS NOT NULL
");

$weakTags = [];

while ($row = $weakTagsResult->fetch_assoc()) {
    $tags = explode(",", $row['wrong_tags']);
    foreach ($tags as $tag) {
        $weakTags[] = trim($tag);
    }
}

$weakTags = array_unique($weakTags);

/*
=================================================
 2) BU TAG’LERE AİT SORULARI GETİR
=================================================
*/
$questions = [];

if (!empty($weakTags)) {
    $tagList = "'" . implode("','", $weakTags) . "'";

    $q = $conn->query("
        SELECT * FROM grammar_questions
        WHERE grammar_tag IN ($tagList)
        ORDER BY RAND()
        LIMIT 10
    ");

    while ($row = $q->fetch_assoc()) {
        $questions[] = $row;
    }
}

/*
=================================================
 3) HİÇ HATA YOKSA FALLBACK
=================================================
*/
if (empty($questions)) {
    $q = $conn->query("
        SELECT * FROM grammar_questions
        ORDER BY RAND()
        LIMIT 10
    ");

    while ($row = $q->fetch_assoc()) {
        $questions[] = $row;
    }
}

/*
=================================================
 4) FORM GÖNDERİLDİYSE DEĞERLENDİR
=================================================
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $score = 0;

    foreach ($questions as $q) {
        $userAnswer = $_POST['q' . $q['id']] ?? '';
        if ($userAnswer === $q['correct_answer']) {
            $score++;
        }
    }

    // Review badge otomatik verildi varsayımı
    $message = "🎉 Review Completed! Score: $score / 10";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Mode | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>🔁 Review Mode</h2>
<p>
    This practice is generated based on your previous mistakes.
</p>

<?php if (isset($message)): ?>
    <div class="card">
        <h3><?= $message ?></h3>
        <p>🏅 Review Badge Unlocked!</p>
        <a href="dashboard.php" class="btn-primary">Back to Dashboard</a>
    </div>
<?php else: ?>

<form method="post">

<?php foreach ($questions as $index => $q): ?>
    <div class="question-box">
        <p><strong><?= ($index + 1) ?>.</strong> <?= $q['question'] ?></p>

        <?php if ($q['type'] === 'mcq'): ?>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="A" required> <?= $q['option_a'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="B"> <?= $q['option_b'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="C"> <?= $q['option_c'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="D"> <?= $q['option_d'] ?></label>
        <?php else: ?>
            <input type="text" name="q<?= $q['id'] ?>" placeholder="Type correct sentence" required>
        <?php endif; ?>

        <small>Focus: <?= $q['grammar_tag'] ?></small>
    </div>
<?php endforeach; ?>

<button type="submit" class="btn-primary">
    Finish Review
</button>

</form>

<?php endif; ?>

</body>
</html>
