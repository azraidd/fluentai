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

$resStmt = $pdo->prepare("SELECT category FROM user_results WHERE user_id = ? AND category LIKE 'grammar-%'");
$resStmt->execute([$userId]);
$completedRaw = $resStmt->fetchAll(PDO::FETCH_COLUMN);
$completedSets = [];
foreach($completedRaw as $c) {
    $parts = explode('-', $c); if(isset($parts[1])) $completedSets[] = intval($parts[1]);
}

try {
    $stmt = $pdo->query("SELECT * FROM grammar_sets ORDER BY id ASC");
    $grammar_sets = $stmt->fetchAll();
} catch (PDOException $e) { die("Veritabanı Hatası."); }

$userGrammarVal = getLvlVal($grammarLvl);

// TEST İŞLEMLERİ
$setId = isset($_GET['set_id']) ? intval($_GET['set_id']) : null;
$showModal = false; $modalType = ""; $modalTitle = ""; $modalMsg = ""; $modalIcon = ""; 
$questions = [];

if ($setId) {
    $qStmt = $pdo->prepare("SELECT * FROM grammar_questions WHERE set_id = ?");
    $qStmt->execute([$setId]);
    $questions = $qStmt->fetchAll();
}

if ($setId && !empty($questions) && $_SERVER["REQUEST_METHOD"] === "POST") {
    $correct = 0; $totalQuestions = count($questions);
    foreach ($questions as $i => $q) {
        if (($_POST["q$i"] ?? "") === $q['correct_answer']) $correct++;
    }

    $showModal = true;
    $categoryName = "grammar-" . $setId;
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM user_results WHERE user_id = ? AND category = ?");
    $checkStmt->execute([$userId, $categoryName]);
    $alreadyCompleted = $checkStmt->fetchColumn() > 0;
    $xpEarned = 0; $xpMessage = "";

    if ($correct == $totalQuestions) {
        $modalType = "success"; $modalTitle = "GRAMMAR MASTER!"; $modalIcon = "🏆";
        if (!$alreadyCompleted) {
            $xpEarned = 100;
            $pdo->prepare("INSERT INTO user_results (user_id, category, score) VALUES (?, ?, 100)")->execute([$userId, $categoryName]);
            $pdo->exec("CREATE TABLE IF NOT EXISTS user_progress (user_id INT PRIMARY KEY, xp INT DEFAULT 0, streak INT DEFAULT 0, last_active DATE)");
            $today = date("Y-m-d");
            $pdo->prepare("INSERT INTO user_progress (user_id, xp, streak, last_active) VALUES (?, ?, 1, ?) ON DUPLICATE KEY UPDATE xp=xp+?, streak=streak, last_active=?")->execute([$userId, $xpEarned, $today, $xpEarned, $today]);
            
            $xpMessage = "<br><br>✨ <strong>+$xpEarned XP</strong>";
            
            $setStmt = $pdo->prepare("SELECT level FROM grammar_sets WHERE id = ?"); $setStmt->execute([$setId]); $setLevel = $setStmt->fetch()['level'];
            $newLevel = $grammarLvl;
            if ($grammarLvl == 'A1' && $setLevel == 'A1') $newLevel = 'A2';
            else if ($grammarLvl == 'A2' && $setLevel == 'A2') $newLevel = 'B1';
            else if ($grammarLvl == 'B1' && $setLevel == 'B1') $newLevel = 'B2';
            else if ($grammarLvl == 'B2' && $setLevel == 'B2') $newLevel = 'C1';

            if ($newLevel != $grammarLvl) {
                $pdo->prepare("UPDATE users SET grammar_level = ? WHERE id = ?")->execute([$newLevel, $userId]);
                $xpMessage .= "<br><span class='badge bg-warning text-dark mt-2'>🚀 GRAMMAR LEVEL UP: $newLevel</span>";
                $grammarLvl = $newLevel;
            }
        } else { $xpMessage = "<br><br><span class='text-white-50'>(Already completed)</span>"; }
        $modalMsg = "Perfect! 10/10." . $xpMessage;
    } else {
        $modalType = "fail"; $modalTitle = "TRY AGAIN"; $modalIcon = "🐢";
        $modalMsg = "You need 10/10 to pass.<br>Correct: $correct / $totalQuestions";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grammar Practice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            color: white; 
            min-height: 100vh; 
            padding-bottom: 50px; 
            
            /* --- GRAMMAR TEMA: YAPI TAŞI / İNŞAAT --- */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85)), 
                        url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .grammar-card { 
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
        .grammar-card.unlocked:hover { transform: translateY(-5px); background: rgba(0, 0, 0, 0.8); border-color: #ffc107; }
        .grammar-card.locked { background: rgba(0, 0, 0, 0.4); color: #6c757d; border-style: dashed; cursor: not-allowed; }
        .grammar-card.completed { background: rgba(25, 135, 84, 0.2); border: 1px solid #198754; color: white; cursor: default; }
        .q-card { background: rgba(255,255,255,0.95); color: black; padding: 20px; margin-bottom: 20px; border-radius: 12px; }
        .q-card label { display: block; padding: 10px; border: 1px solid #eee; margin-bottom: 5px; border-radius: 8px; cursor: pointer; }
        .q-card label:hover { background: #e9ecef; }
        input[type="radio"] { margin-right: 10px; }
        #timer-bar { position: fixed; top: 0; left: 0; width: 100%; height: 6px; background: #0d6efd; z-index: 1000; transition: width 1s linear; }
        .timer-badge { position: fixed; top: 20px; right: 20px; background: #0d6efd; color: white; padding: 10px 20px; border-radius: 30px; font-weight: bold; font-size: 1.2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000; }
        .modal-content { color: #333; text-align: center; }
        .result-icon { font-size: 4rem; }
    </style>
</head>
<body>

<?php if($setId && !empty($questions) && !$showModal): ?>
    <div id="timer-bar"></div>
    <div class="timer-badge">⚡ <span id="time-left">60</span>s</div>
<?php endif; ?>

<div class="container py-5" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold">📘 Grammar Practice</h2>
            <p class="mb-0 fs-5">
                <span class="badge bg-secondary border border-light">Overall: <?= $overallLevel ?></span> 
                <span class="badge bg-warning text-dark border border-warning">Grammar: <?= $grammarLvl ?></span>
            </p>
        </div>
        <a href="<?= $setId ? 'grammar.php' : 'dashboard.php' ?>" class="btn btn-outline-light">⬅ Back</a>
    </div>

    <?php if($setId && !empty($questions)): ?>
        <form method="post" id="grammarForm">
            <?php foreach ($questions as $i => $q): ?>
                <div class="q-card">
                    <p class="fs-5 fw-bold mb-3"><?= ($i+1) ?>. <?= htmlspecialchars($q['question']) ?></p>
                    <label><input type="radio" name="q<?= $i ?>" value="a"> <?= htmlspecialchars($q['option_a']) ?></label>
                    <label><input type="radio" name="q<?= $i ?>" value="b"> <?= htmlspecialchars($q['option_b']) ?></label>
                    <label><input type="radio" name="q<?= $i ?>" value="c"> <?= htmlspecialchars($q['option_c']) ?></label>
                    <label><input type="radio" name="q<?= $i ?>" value="d"> <?= htmlspecialchars($q['option_d']) ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5 shadow">🏁 Finish Sprint</button>
        </form>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($grammar_sets as $set): ?>
                <?php 
                    $sid = $set['id']; $lvl = $set['level']; $title = $set['title'];
                    $isLocked = $userGrammarVal < getLvlVal($lvl);
                    $isCompleted = in_array($sid, $completedSets); 
                ?>
                <div class="col-md-6">
                    <?php if ($isCompleted): ?>
                        <div class="grammar-card completed">
                            <h3 class="mb-3"><?= htmlspecialchars($title) ?></h3>
                            <div style="font-size: 3rem;">✅</div>
                            <p>COMPLETED</p>
                        </div>
                    <?php elseif ($isLocked): ?>
                        <div class="grammar-card locked">
                            <h3 class="mb-3"><?= htmlspecialchars($title) ?></h3>
                            <div style="font-size: 2rem;">🔒</div>
                            <small>Reach Grammar <?= $lvl ?></small>
                        </div>
                    <?php else: ?>
                        <div class="grammar-card unlocked">
                            <h3 class="mb-3"><?= htmlspecialchars($title) ?></h3>
                            <p class="text-white-50 small"><?= htmlspecialchars($set['description']) ?></p>
                            <a href="?set_id=<?= $sid ?>" class="btn btn-primary px-4 rounded-pill mt-3">⚡ Start Sprint</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="resultModal" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 d-block pt-4">
        <div class="result-icon"><?= $modalIcon ?></div>
        <h2 class="modal-title fw-bold text-dark"><?= $modalTitle ?></h2>
      </div>
      <div class="modal-body fs-5"><p><?= $modalMsg ?></p></div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <a href="grammar.php" class="btn px-5 py-2 fw-bold rounded-pill shadow btn-dark">Continue</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    <?php if ($showModal): ?>
        var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
        myModal.show();
    <?php elseif($setId && !empty($questions)): ?>
        let timeTotal = 60; let timeLeft = timeTotal;
        const timerDisplay = document.getElementById('time-left');
        const timerBar = document.getElementById('timer-bar');
        const form = document.getElementById('grammarForm');
        const timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            let percentage = (timeLeft / timeTotal) * 100;
            timerBar.style.width = percentage + "%";
            if(timeLeft <= 5) {
                timerBar.style.background = "#ff0000";
                document.querySelector('.timer-badge').style.background = "#ff0000";
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("⏳ TIME'S UP! Submitting...");
                form.submit();
            }
        }, 1000);
    <?php endif; ?>
</script>
</body>
</html>