<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Zaten yapılmışsa tekrar sokma
if ($_SESSION['placement_done'] ?? 0) {
    header("Location: dashboard.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* =========================
   20 SORULUK PLACEMENT TEST
   ========================= */
$questions = [
    ["q"=>"Choose the correct sentence:", "a"=>"She go to school every day.", "b"=>"She goes to school every day.", "c"=>"She going to school every day.", "d"=>"She gone to school every day.", "cA"=>"b"],
    ["q"=>"I ___ finished my homework.", "a"=>"has", "b"=>"have", "c"=>"am", "d"=>"was", "cA"=>"b"],
    ["q"=>"What does 'reliable' mean?", "a"=>"fast", "b"=>"cheap", "c"=>"can be trusted", "d"=>"angry", "cA"=>"c"],
    ["q"=>"Choose the correct option:", "a"=>"He don’t like coffee.", "b"=>"He doesn’t like coffee.", "c"=>"He not like coffee.", "d"=>"He didn’t like coffee.", "cA"=>"b"],
    ["q"=>"Main idea: John wakes up early and goes to work every day.", "a"=>"John is lazy", "b"=>"John has a routine", "c"=>"John hates work", "d"=>"John sleeps a lot", "cA"=>"b"],
    ["q"=>"She has lived here ___ 2015.", "a"=>"since", "b"=>"for", "c"=>"from", "d"=>"during", "cA"=>"a"],
    ["q"=>"Opposite of 'cheap'?", "a"=>"small", "b"=>"fast", "c"=>"expensive", "d"=>"new", "cA"=>"c"],
    ["q"=>"If it rains, I ___ stay home.", "a"=>"will", "b"=>"would", "c"=>"am", "d"=>"was", "cA"=>"a"],
    ["q"=>"What is the text mostly about?", "a"=>"Weather", "b"=>"Daily habits", "c"=>"Sports", "d"=>"Food", "cA"=>"b"],
    ["q"=>"She speaks ___ than her sister.", "a"=>"more confident", "b"=>"confident", "c"=>"most confident", "d"=>"confidence", "cA"=>"a"],

    ["q"=>"Choose correct:", "a"=>"There is many people.", "b"=>"There are many people.", "c"=>"There be many people.", "d"=>"There was many people.", "cA"=>"b"],
    ["q"=>"Meaning of 'improve'?", "a"=>"make worse", "b"=>"stay same", "c"=>"get better", "d"=>"stop", "cA"=>"c"],
    ["q"=>"I didn’t see ___ yesterday.", "a"=>"nobody", "b"=>"anybody", "c"=>"somebody", "d"=>"everybody", "cA"=>"b"],
    ["q"=>"He asked me where I ___ from.", "a"=>"am", "b"=>"was", "c"=>"were", "d"=>"be", "cA"=>"b"],
    ["q"=>"Main idea: Technology helps people communicate faster.", "a"=>"Tech is dangerous", "b"=>"Communication improved", "c"=>"People are lonely", "d"=>"Phones are bad", "cA"=>"b"],
    ["q"=>"Choose correct:", "a"=>"She enjoys to read.", "b"=>"She enjoys reading.", "c"=>"She enjoy reading.", "d"=>"She enjoying read.", "cA"=>"b"],
    ["q"=>"Synonym of 'quick'?", "a"=>"slow", "b"=>"late", "c"=>"fast", "d"=>"weak", "cA"=>"c"],
    ["q"=>"I have never ___ sushi.", "a"=>"eat", "b"=>"ate", "c"=>"eaten", "d"=>"eating", "cA"=>"c"],
    ["q"=>"What is implied? He studied hard and passed.", "a"=>"He failed", "b"=>"He was lucky", "c"=>"Hard work helped", "d"=>"He cheated", "cA"=>"c"],
    ["q"=>"Choose correct:", "a"=>"Despite tired, he worked.", "b"=>"Despite of tired, he worked.", "c"=>"Despite being tired, he worked.", "d"=>"Despite tiredness he work.", "cA"=>"c"],
];

/* =========================
   TEST GÖNDERİLDİYSE
   ========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correct = 0;
    foreach ($questions as $i => $q) {
        if (($_POST["q$i"] ?? "") === $q['cA']) {
            $correct++;
        }
    }

    $percentage = ($correct / count($questions)) * 100;

    if ($percentage < 30) $level = "A1";
    elseif ($percentage < 45) $level = "A2";
    elseif ($percentage < 65) $level = "B1";
    elseif ($percentage < 80) $level = "B2";
    else $level = "C1";

    // DB güncelle
    $stmt = $conn->prepare("UPDATE users SET level=?, placement_done=1 WHERE id=?");
    $stmt->bind_param("si", $level, $userId);
    $stmt->execute();

    $_SESSION['level'] = $level;
    $_SESSION['placement_done'] = 1;
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Placement Result</title>
        <style>
            body { font-family: Arial; background:#f6f7fb; text-align:center; }
            .result-box {
                background:white;
                width:400px;
                margin:100px auto;
                padding:30px;
                border-radius:15px;
                box-shadow:0 5px 20px rgba(0,0,0,.1);
            }
            .level {
                font-size:48px;
                color:#58cc02;
            }
            button {
                padding:12px 25px;
                border:none;
                background:#58cc02;
                color:white;
                border-radius:8px;
                font-size:16px;
                cursor:pointer;
            }
        </style>
    </head>
    <body>
        <div class="result-box">
            <h2>Your English Level</h2>
            <div class="level"><?= $level ?></div>
            <p><?= round($percentage) ?>% correct</p>
            <form action="dashboard.php">
                <button>Go to Dashboard</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Placement Test</title>
<style>
body {
    font-family: Arial;
    background:#f6f7fb;
}
.container {
    max-width:800px;
    margin:40px auto;
}
.card {
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:12px;
    box-shadow:0 3px 10px rgba(0,0,0,.08);
}
button {
    background:#58cc02;
    color:white;
    padding:15px;
    width:100%;
    border:none;
    border-radius:10px;
    font-size:18px;
}
</style>
</head>

<body>
<div class="container">
<h2>📘 Placement Test</h2>
<p>Answer the questions to determine your English level.</p>

<form method="post">
<?php foreach ($questions as $i => $q): ?>
<div class="card">
    <strong><?= ($i+1) ?>. <?= $q['q'] ?></strong><br><br>
    <?php foreach (['a','b','c','d'] as $opt): ?>
        <label>
            <input type="radio" name="q<?= $i ?>" value="<?= $opt ?>" required>
            <?= $q[$opt] ?>
        </label><br>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<button>Finish Test</button>
</form>
</div>
</body>
</html>
