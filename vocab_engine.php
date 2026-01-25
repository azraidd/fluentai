<?php
session_start();
require 'db.php';

// Güvenlik
if (!isset($_GET['set_id'])) { header("Location: vocab.php"); exit; }
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$setId = intval($_GET['set_id']);
$userId = $_SESSION['user_id'];

// 1. KULLANICININ VOCAB SEVİYESİNİ ÇEK (Level atlatmak için lazım)
$stmt = $pdo->prepare("SELECT vocab_level FROM users WHERE id = ?");
$stmt->execute([$userId]);
$vocabLevel = $stmt->fetchColumn() ?: 'A1';

// Soruları Çek
$stmt = $pdo->prepare("SELECT * FROM vocab_words WHERE set_id = ?");
$stmt->execute([$setId]);
$questions = $stmt->fetchAll();

if (count($questions) < 2) {
    header("Location: vocab.php"); exit;
}

// Şık Karıştırıcı
function generateOptions($correctWord, $allWords) {
    $options = [$correctWord];
    $attempts = 0;
    while (count($options) < 4 && $attempts < 50) {
        $attempts++;
        $randWord = $allWords[array_rand($allWords)]['word'];
        if (!in_array($randWord, $options)) $options[] = $randWord;
    }
    shuffle($options);
    return $options;
}

// --- SONUÇ HESAPLAMA ---
$showModal = false;
$modalType = ""; 
$modalTitle = "";
$modalMsg = "";
$modalIcon = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correctCount = 0;
    $totalQuestions = count($questions);
    
    foreach ($questions as $index => $w) {
        $userAnswer = $_POST["q$index"] ?? '';
        if ($userAnswer === $w['word']) {
            $correctCount++;
        }
    }

    $showModal = true; 
    
    // XP VE TAMAMLAMA KONTROLÜ
    $categoryName = "vocab-" . $setId;
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM user_results WHERE user_id = ? AND category = ?");
    $checkStmt->execute([$userId, $categoryName]);
    $alreadyCompleted = $checkStmt->fetchColumn() > 0;

    $xpEarned = 0;
    $xpMessage = "";

    if ($correctCount == $totalQuestions) {
        // --- BAŞARILI (10/10) ---
        $modalType = "success";
        $modalTitle = "WORD SPRINT CHAMPION!";
        $modalIcon = "⚡";

        if (!$alreadyCompleted) {
            $xpEarned = 100; // ÖDÜL

            // Kaydet
            $insStmt = $pdo->prepare("INSERT INTO user_results (user_id, category, score) VALUES (?, ?, 100)");
            $insStmt->execute([$userId, $categoryName]);

            // XP ve Streak
            $pdo->exec("CREATE TABLE IF NOT EXISTS user_progress (user_id INT PRIMARY KEY, xp INT DEFAULT 0, streak INT DEFAULT 0, last_active DATE)");
            $today = date("Y-m-d");
            
            // Streak Mantığı
            $pStmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ?");
            $pStmt->execute([$userId]);
            $progress = $pStmt->fetch(PDO::FETCH_ASSOC);
            
            $currentStreak = $progress ? $progress['streak'] : 0;
            $lastActive = $progress ? $progress['last_active'] : null;
            
            if ($lastActive !== $today) {
                $yesterday = date("Y-m-d", strtotime("-1 day"));
                $currentStreak = ($lastActive === $yesterday) ? $currentStreak + 1 : 1;
            }
            
            // Insert/Update Progress
            $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, xp, streak, last_active) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE xp=xp+?, streak=?, last_active=?");
            $stmt->execute([$userId, $xpEarned, $currentStreak, $today, $xpEarned, $currentStreak, $today]);

            $xpMessage = "<br><br>✨ <strong>+$xpEarned XP Earned!</strong><br>🔥 Streak: $currentStreak Days";

            // --- KRİTİK DEĞİŞİKLİK: SADECE VOCAB SEVİYESİNİ YÜKSELT ---
            $setStmt = $pdo->prepare("SELECT level FROM vocab_sets WHERE id = ?");
            $setStmt->execute([$setId]);
            $setInfo = $setStmt->fetch();
            $setLevel = $setInfo['level'] ?? 'A1';

            $newLevel = $vocabLevel;
            if ($vocabLevel == 'A1' && $setLevel == 'A1') $newLevel = 'A2';
            else if ($vocabLevel == 'A2' && $setLevel == 'A2') $newLevel = 'B1';
            else if ($vocabLevel == 'B1' && $setLevel == 'B1') $newLevel = 'B2';
            else if ($vocabLevel == 'B2' && $setLevel == 'B2') $newLevel = 'C1';

            if ($newLevel != $vocabLevel) {
                // SADECE vocab_level GÜNCELLENİYOR
                $pdo->prepare("UPDATE users SET vocab_level = ? WHERE id = ?")->execute([$newLevel, $userId]);
                $xpMessage .= "<br><span class='badge bg-warning text-dark p-2 mt-2'>🚀 VOCAB LEVEL UP: $newLevel</span>";
                $vocabLevel = $newLevel; // Değişkeni güncelle
            }

        } else {
            $xpMessage = "<br><br><span class='text-white-50'>(Already completed. No new XP.)</span>";
        }
        
        $modalMsg = "Amazing speed! You got 10/10.<br>Level Completed!" . $xpMessage;

    } elseif ($correctCount >= ($totalQuestions * 0.6)) {
        $modalType = "warning";
        $modalTitle = "SO FAST, BUT...";
        $modalIcon = "⏱️";
        $modalMsg = "Good speed! But you need a <strong>Perfect Score</strong> to complete this set.<br>
                     ✅ Correct: $correctCount / $totalQuestions<br><span class='text-white-50'>(No XP awarded)</span>";
    } else {
        $modalType = "fail";
        $modalTitle = "TIME PRESSURE?";
        $modalIcon = "🐢";
        $modalMsg = "Don't panic! Focus on accuracy next time.<br>
                     ✅ Correct: $correctCount / $totalQuestions<br><span class='text-white-50'>(No XP awarded)</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Sprint</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: white; padding-bottom: 50px;
        }
        .q-card {
            background: rgba(255,255,255,0.95); color: black;
            padding: 25px; margin-bottom: 20px; border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .options label {
            display: block; padding: 12px; border: 1px solid #eee;
            margin-bottom: 8px; border-radius: 8px; cursor: pointer; transition: 0.2s;
        }
        .options label:hover { background: #e9ecef; border-color: #0d6efd; }
        input[type="radio"] { margin-right: 10px; }
        
        /* ZAMANLAYICI */
        #timer-bar { position: fixed; top: 0; left: 0; width: 100%; height: 6px; background: #dc3545; z-index: 1000; transition: width 1s linear; }
        .timer-badge {
            position: fixed; top: 20px; right: 20px; 
            background: #dc3545; color: white; padding: 10px 20px; 
            border-radius: 30px; font-weight: bold; font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000;
        }
        
        .modal-content { border-radius: 15px; text-align: center; color: #333; }
        .result-icon { font-size: 5rem; margin-bottom: 10px; display: block; }
        .btn-modal-success { background: #198754; color: white; }
        .btn-modal-warning { background: #ffc107; color: black; }
        .btn-modal-fail { background: #dc3545; color: white; }
    </style>
</head>
<body>

<?php if (!$showModal): ?>
    <div id="timer-bar"></div>
    <div class="timer-badge">⏱️ <span id="time-left">60</span>s</div>
<?php endif; ?>

<div class="container mt-5" style="max-width: 700px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">⚡ Word Sprint</h3>
            <p class="mb-0 text-white-50">Current Vocab Level: <span class="badge bg-warning text-dark"><?= $vocabLevel ?></span></p>
        </div>
        <a href="vocab.php" class="btn btn-outline-light btn-sm">Cancel</a>
    </div>
    
    <form method="post" id="quizForm">
        <?php foreach ($questions as $index => $w): ?>
            <?php 
                $hiddenSentence = str_ireplace($w['word'], "<span class='text-primary fw-bold text-decoration-underline'>_______</span>", $w['example']);
                $options = generateOptions($w['word'], $questions);
            ?>
            <div class="q-card">
                <div class="d-flex justify-content-between">
                    <p class="fs-5 mb-3 fw-bold">Question <?= ($index + 1) ?></p>
                    <span class="badge bg-secondary mb-3">Hint: <?= $w['meaning'] ?></span>
                </div>
                <p class="lead mb-4"><?= $hiddenSentence ?></p>
                <div class="options">
                    <?php foreach ($options as $opt): ?>
                        <label>
                            <input type="radio" name="q<?= $index ?>" value="<?= $opt ?>">
                            <?= ucfirst($opt) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow fs-5">🚀 Submit Answers</button>
    </form>
</div>

<div class="modal fade" id="resultModal" data-bs-backdrop="static" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 d-block pt-4">
        <div class="result-icon"><?= $modalIcon ?></div>
        <h2 class="modal-title fw-bold 
            <?php 
                if($modalType=='success') echo 'text-success'; 
                else if($modalType=='warning') echo 'text-warning'; 
                else echo 'text-danger'; 
            ?>">
            <?= $modalTitle ?>
        </h2>
      </div>
      <div class="modal-body fs-5"><p><?= $modalMsg ?></p></div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <a href="vocab.php" class="btn px-5 py-2 fw-bold rounded-pill shadow 
            <?php 
                if($modalType=='success') echo 'btn-modal-success'; 
                else if($modalType=='warning') echo 'btn-modal-warning'; 
                else echo 'btn-modal-fail'; 
            ?>">
            <?= ($modalType == 'success') ? 'Continue' : 'Try Again' ?>
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    <?php if ($showModal): ?>
        var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
        myModal.show();
    <?php else: ?>
        let timeTotal = 60; 
        let timeLeft = timeTotal;
        const timerDisplay = document.getElementById('time-left');
        const timerBar = document.getElementById('timer-bar');
        const form = document.getElementById('quizForm');

        const timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            let percentage = (timeLeft / timeTotal) * 100;
            timerBar.style.width = percentage + "%";
            if(timeLeft <= 10) {
                timerBar.style.background = "#ff0000";
                document.querySelector('.timer-badge').style.animation = "pulse 1s infinite";
            }
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("⏳ TIME'S UP! Submitting your answers...");
                form.submit();
            }
        }, 1000);
        const style = document.createElement('style');
        style.innerHTML = `@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.1); } 100% { transform: scale(1); } }`;
        document.head.appendChild(style);
    <?php endif; ?>
</script>
</body>
</html>