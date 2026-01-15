<?php
session_start();
include "db.php";

if (!isset($_GET['set_id'])) {
    header("Location: vocabulary.php");
    exit;
}

$setId = intval($_GET['set_id']);

// 1. Bu setteki kelimeleri çek
$wordsQuery = $conn->query("SELECT * FROM vocab_words WHERE set_id = $setId");
$words = [];
while ($w = $wordsQuery->fetch_assoc()) {
    $words[] = $w;
}

// Yeterli kelime yoksa geri at
if (count($words) < 3) {
    die("Not enough words in this set yet! <a href='vocabulary.php'>Go back</a>");
}

// 2. Rastgele 3 Yanlış Cevap Üretme Fonksiyonu
function generateOptions($correctWord, $allWords) {
    $options = [$correctWord];
    while (count($options) < 4) {
        $rand = $allWords[array_rand($allWords)]['word'];
        if (!in_array($rand, $options)) {
            $options[] = $rand;
        }
    }
    shuffle($options);
    return $options;
}

// 3. Test Gönderildiyse Sonucu Hesapla
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;
    $total = count($words);
    
    foreach ($words as $index => $w) {
        $userAnswer = $_POST["q$index"] ?? '';
        if ($userAnswer === $w['word']) {
            $score++;
        }
    }
    
    // XP Ekleme (Basit Mantık)
    $xp = $score * 5;
    $userId = $_SESSION['user_id'];
    $conn->query("UPDATE user_progress SET xp = xp + $xp WHERE user_id = $userId");
    
    echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>
            <h1>🎉 Completed!</h1>
            <h2>Score: $score / $total</h2>
            <p style='color:#58cc02; font-weight:bold;'>+$xp XP Earned</p>
            <a href='vocabulary.php' style='padding:10px 20px; background:#58cc02; color:white; text-decoration:none; border-radius:5px;'>Back to List</a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vocabulary Quiz</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .quiz-container { max-width: 600px; margin: 40px auto; }
        .q-card { background: white; padding: 25px; margin-bottom: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .sentence { font-size: 18px; margin-bottom: 15px; font-weight: 500; }
        .options label { display: block; padding: 10px; border: 1px solid #eee; margin-bottom: 5px; border-radius: 8px; cursor: pointer; transition: 0.2s; }
        .options label:hover { background: #f9f9f9; border-color: #58cc02; }
        input[type="radio"] { margin-right: 10px; }
    </style>
</head>
<body>

<div class="quiz-container">
    <h2>📝 Quiz Time!</h2>
    
    <form method="post">
        <?php foreach ($words as $index => $w): ?>
            <?php 
                // Cümledeki kelimeyi gizle (Case insensitive replace)
                $hiddenSentence = str_ireplace($w['word'], "_______", $w['example']);
                $options = generateOptions($w['word'], $words);
            ?>
            
            <div class="q-card">
                <p class="sentence"><?= ($index + 1) ?>. <?= $hiddenSentence ?></p>
                
                <div class="options">
                    <?php foreach ($options as $opt): ?>
                        <label>
                            <input type="radio" name="q<?= $index ?>" value="<?= $opt ?>" required>
                            <?= $opt ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn-primary" style="width:100%;">Finish Test</button>
    </form>
</div>

</body>
</html>