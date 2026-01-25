<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) { header("Location: reading.php"); exit; }
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$passageId = intval($_GET['id']);
$userId = $_SESSION['user_id'];

// 1. KULLANICININ READING SEVİYESİNİ ÇEK
$uStmt = $pdo->prepare("SELECT reading_level FROM users WHERE id = ?");
$uStmt->execute([$userId]);
$readingLevel = $uStmt->fetchColumn() ?: 'A1';

// 2. Parçayı Çek
$stmt = $pdo->prepare("SELECT * FROM reading_passages WHERE id = ?");
$stmt->execute([$passageId]);
$passage = $stmt->fetch();

if (!$passage) { header("Location: reading.php"); exit; }

// 3. Soruları ve Şıkları Çek
$qStmt = $pdo->prepare("SELECT * FROM reading_questions WHERE passage_id = ?");
$qStmt->execute([$passageId]);
$questions = $qStmt->fetchAll();

$questionsWithOptions = [];
foreach ($questions as $q) {
    $optStmt = $pdo->prepare("SELECT * FROM question_options WHERE question_id = ?");
    $optStmt->execute([$q['id']]);
    $q['options'] = $optStmt->fetchAll();
    $questionsWithOptions[] = $q;
}

// --- POST İŞLEMİ (TEST SONUCU) ---
$showModal = false;
$modalType = ""; $modalTitle = ""; $modalMsg = ""; $modalIcon = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correctCount = 0;
    $totalQuestions = count($questionsWithOptions);
    
    foreach ($questionsWithOptions as $q) {
        $qid = $q['id'];
        $userAnsId = $_POST["q$qid"] ?? 0;
        foreach ($q['options'] as $opt) {
            if ($opt['id'] == $userAnsId && $opt['is_correct']) {
                $correctCount++;
            }
        }
    }

    $showModal = true;
    
    // XP KONTROLLERİ
    $categoryName = "reading-" . $passageId;
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM user_results WHERE user_id = ? AND category = ?");
    $checkStmt->execute([$userId, $categoryName]);
    $alreadyCompleted = $checkStmt->fetchColumn() > 0;

    $xpEarned = 0;
    $xpMessage = "";

    if ($correctCount == $totalQuestions) {
        $modalType = "success";
        $modalTitle = "READING MASTER!";
        $modalIcon = "📚";

        if (!$alreadyCompleted) {
            $xpEarned = 100; // ÖDÜL

            // Kaydet
            $insStmt = $pdo->prepare("INSERT INTO user_results (user_id, category, score) VALUES (?, ?, 100)");
            $insStmt->execute([$userId, $categoryName]);

            // XP ve Streak
            $pdo->exec("CREATE TABLE IF NOT EXISTS user_progress (user_id INT PRIMARY KEY, xp INT DEFAULT 0, streak INT DEFAULT 0, last_active DATE)");
            $today = date("Y-m-d");
            
            // Streak Hesapla
            $pStmt = $pdo->prepare("SELECT * FROM user_progress WHERE user_id = ?");
            $pStmt->execute([$userId]);
            $progress = $pStmt->fetch(PDO::FETCH_ASSOC);
            
            $currentStreak = $progress ? $progress['streak'] : 0;
            $lastActive = $progress ? $progress['last_active'] : null;
            
            if ($lastActive !== $today) {
                $yesterday = date("Y-m-d", strtotime("-1 day"));
                $currentStreak = ($lastActive === $yesterday) ? $currentStreak + 1 : 1;
            }
            
            $stmt = $pdo->prepare("INSERT INTO user_progress (user_id, xp, streak, last_active) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE xp=xp+?, streak=?, last_active=?");
            $stmt->execute([$userId, $xpEarned, $currentStreak, $today, $xpEarned, $currentStreak, $today]);
            
            $xpMessage = "<br><br>✨ <strong>+$xpEarned XP Earned!</strong><br>🔥 Streak: $currentStreak Days";
            
            // --- SEVİYE YÜKSELTME ---
            $passageLvl = $passage['cefr_level'];
            
            $newLevel = $readingLevel;
            if ($readingLevel == 'A1' && $passageLvl == 'A1') $newLevel = 'A2';
            else if ($readingLevel == 'A2' && $passageLvl == 'A2') $newLevel = 'B1';
            else if ($readingLevel == 'B1' && $passageLvl == 'B1') $newLevel = 'B2';
            else if ($readingLevel == 'B2' && $passageLvl == 'B2') $newLevel = 'C1';

            if ($newLevel != $readingLevel) {
                $pdo->prepare("UPDATE users SET reading_level = ? WHERE id = ?")->execute([$newLevel, $userId]);
                $xpMessage .= "<br><span class='badge bg-warning text-dark mt-2'>🚀 READING LEVEL UP: $newLevel</span>";
                $readingLevel = $newLevel;
            }

        } else {
            $xpMessage = "<br><br><span class='text-muted'>(Already completed)</span>";
        }

        $modalMsg = "Perfect comprehension! 100% Correct." . $xpMessage;

    } else {
        $modalType = "fail";
        $modalTitle = "KEEP READING";
        $modalIcon = "📖";
        $modalMsg = "You need to answer all questions correctly.<br>✅ Correct: $correctCount / $totalQuestions";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($passage['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: white; padding-bottom: 50px;
        }
        .passage-box {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 30px;
            border-radius: 15px;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            border-left: 5px solid #0d6efd;
        }
        .q-card {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
        }
        .q-card label {
            display: block; padding: 10px; border: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 5px; border-radius: 8px; cursor: pointer; transition: 0.2s;
        }
        .q-card label:hover { background: rgba(255,255,255,0.2); }
        input[type="radio"] { margin-right: 10px; }

        /* ZAMANLAYICI STİLLERİ */
        #timer-bar { 
            position: fixed; top: 0; left: 0; width: 100%; height: 6px; 
            background: #0d6efd; z-index: 1000; transition: width 1s linear; 
        }
        .timer-badge {
            position: fixed; top: 20px; right: 20px; 
            background: #0d6efd; color: white; padding: 10px 20px; 
            border-radius: 30px; font-weight: bold; font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 1000;
        }

        /* Modal */
        .modal-content { color: #333; text-align: center; }
        .result-icon { font-size: 4rem; }
        
        .btn-modal-success { background: #198754; color: white; }
        .btn-modal-warning { background: #ffc107; color: black; }
        .btn-modal-fail { background: #dc3545; color: white; }
    </style>
</head>
<body>

<?php if (!$showModal): ?>
    <div id="timer-bar"></div>
    <div class="timer-badge">⏱️ <span id="time-left">120</span>s</div>
<?php endif; ?>

<div class="container py-5" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><?= htmlspecialchars($passage['title']) ?></h3>
        <a href="reading.php" class="btn btn-outline-light btn-sm">Exit</a>
    </div>

    <div class="passage-box shadow">
        <?= nl2br(htmlspecialchars($passage['content'])) ?>
    </div>

    <form method="post" id="readingForm">
        <?php foreach ($questionsWithOptions as $index => $q): ?>
            <div class="q-card">
                <p class="fw-bold mb-3"><?= ($index + 1) ?>. <?= htmlspecialchars($q['question_text']) ?></p>
                
                <?php foreach ($q['options'] as $opt): ?>
                    <label>
                        <input type="radio" name="q<?= $q['id'] ?>" value="<?= $opt['id'] ?>" required>
                        <?= htmlspecialchars($opt['option_text']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-success w-100 py-3 fw-bold fs-5 shadow mt-3">✅ Submit Answers</button>
    </form>

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
        <a href="reading.php" class="btn btn-primary px-5 rounded-pill">Continue</a>
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
        // --- ZAMANLAYICI KODU ---
        let timeTotal = 120; // 120 Saniye (2 Dakika) - İstersen artırabilirsin
        let timeLeft = timeTotal;
        const timerDisplay = document.getElementById('time-left');
        const timerBar = document.getElementById('timer-bar');
        const form = document.getElementById('readingForm');

        const timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            
            // Progress Bar küçültme
            let percentage = (timeLeft / timeTotal) * 100;
            timerBar.style.width = percentage + "%";

            // Renk değiştirme (Son 10 saniye kırmızı)
            if(timeLeft <= 10) {
                timerBar.style.background = "#ff0000";
                document.querySelector('.timer-badge').style.background = "#ff0000";
            }

            // SÜRE BİTTİ
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("⏳ Time's up! Submitting your answers...");
                form.submit(); // Otomatik gönder
            }
        }, 1000);
    <?php endif; ?>
</script>

</body>
</html>