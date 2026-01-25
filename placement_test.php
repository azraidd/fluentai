<?php
session_start();
require 'db.php';

// Güvenlik
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$userId = $_SESSION['user_id'];

/* =========================================================
   PARAGRAFLAR (READING METİNLERİ)
   ========================================================= */
$textA = "<strong>My Friend Emily</strong><br>
Emily is twenty-five years old. She lives in a small apartment in London. She works as a nurse in a big hospital. She loves her job because she likes helping people. In her free time, Emily enjoys reading books and cooking. Every Sunday, she visits her parents who live in the countryside. She usually takes the train because it is fast and comfortable.";

$textB = "<strong>The Rise of Remote Work</strong><br>
In recent years, remote work has become increasingly popular. Advances in technology, such as high-speed internet and video conferencing tools, have made it possible for employees to work from anywhere. While some argue that working from home increases productivity and work-life balance, others believe it leads to isolation and a lack of collaboration. Companies are now trying to find a balance by offering hybrid models, where employees split their time between the office and home.";

$textC = "<strong>The Ethics of Artificial Intelligence</strong><br>
The rapid proliferation of artificial intelligence (AI) has precipitated a profound ethical debate. While AI promises unprecedented efficiency in fields ranging from healthcare to logistics, it also poses significant risks regarding privacy, bias, and accountability. The lack of transparency in 'black box' algorithms raises concerns about decision-making processes that affect human lives. Consequently, scholars and policymakers are advocating for robust regulatory frameworks to ensure that AI development aligns with human values and fundamental rights.";

/* =========================================================
   SORU HAVUZU (30 SORU)
   ========================================================= */
$questions = [
    // --- PART 1: READING (1-10) ---
    ['sec'=>'reading', 'q'=>'Where does Emily live?', 'opts'=>['In the countryside', 'In a big hospital', 'In London', 'On a train'], 'a'=>'In London'],
    ['sec'=>'reading', 'q'=>'Why does she like her job?', 'opts'=>['She makes a lot of money', 'She likes helping people', 'She lives near the hospital', 'It is easy'], 'a'=>'She likes helping people'],
    ['sec'=>'reading', 'q'=>'How does she travel to see her parents?', 'opts'=>['By car', 'By bus', 'By train', 'By plane'], 'a'=>'By train'],
    
    ['sec'=>'reading', 'q'=>'What has made remote work possible?', 'opts'=>['Cheaper offices', 'Technology advances', 'More holidays', 'Less work'], 'a'=>'Technology advances'],
    ['sec'=>'reading', 'q'=>'What is one benefit of working from home mentioned in the text?', 'opts'=>['Better work-life balance', 'More isolation', 'Free food', 'Higher salary'], 'a'=>'Better work-life balance'],
    ['sec'=>'reading', 'q'=>'What is a negative aspect mentioned?', 'opts'=>['Too much noise', 'Lack of internet', 'Isolation', 'Commuting'], 'a'=>'Isolation'],
    ['sec'=>'reading', 'q'=>'What solution are companies offering?', 'opts'=>['Closing offices', 'Hybrid models', 'Banning remote work', 'Ignoring the issue'], 'a'=>'Hybrid models'],

    ['sec'=>'reading', 'q'=>'What is the main concern regarding "black box" algorithms?', 'opts'=>['They are too slow', 'They lack transparency', 'They are expensive', 'They are too simple'], 'a'=>'They lack transparency'],
    ['sec'=>'reading', 'q'=>'Why are policymakers calling for regulations?', 'opts'=>['To stop technology', 'To ensure alignment with human values', 'To increase profits', 'To speed up AI'], 'a'=>'To ensure alignment with human values'],
    ['sec'=>'reading', 'q'=>'The word "precipitated" in the text is closest in meaning to:', 'opts'=>['Stopped', 'Caused', 'Prevented', 'Hidden'], 'a'=>'Caused'],

    // --- PART 2: VOCABULARY (11-20) ---
    ['sec'=>'vocab', 'q'=>'Opposite of "Cold"?', 'opts'=>['Freezing', 'Hot', 'Cool', 'Wet'], 'a'=>'Hot'],
    ['sec'=>'vocab', 'q'=>'You wear this on your feet.', 'opts'=>['Hat', 'Gloves', 'Shoes', 'Scarf'], 'a'=>'Shoes'],
    ['sec'=>'vocab', 'q'=>'My mother’s sister is my...', 'opts'=>['Uncle', 'Aunt', 'Cousin', 'Grandma'], 'a'=>'Aunt'],
    
    ['sec'=>'vocab', 'q'=>'To "postpone" a meeting means to...', 'opts'=>['Cancel it', 'Start it early', 'Delay it', 'Forget it'], 'a'=>'Delay it'],
    ['sec'=>'vocab', 'q'=>'He is extremely "ambitious". He wants to...', 'opts'=>['Sleep all day', 'Succeed and achieve goals', 'Help others', 'Eat food'], 'a'=>'Succeed and achieve goals'],
    ['sec'=>'vocab', 'q'=>'Synonym for "Accurate"?', 'opts'=>['Wrong', 'Correct', 'Vague', 'Fast'], 'a'=>'Correct'],
    ['sec'=>'vocab', 'q'=>'If something is "temporary", it lasts...', 'opts'=>['Forever', 'A long time', 'A short time', 'Never'], 'a'=>'A short time'],
    
    ['sec'=>'vocab', 'q'=>'Synonym for "Obsolete"?', 'opts'=>['New', 'Outdated', 'Trendy', 'Useful'], 'a'=>'Outdated'],
    ['sec'=>'vocab', 'q'=>'To "scrutinize" means to...', 'opts'=>['Ignore completely', 'Examine closely', 'Accept blindly', 'Write quickly'], 'a'=>'Examine closely'],
    ['sec'=>'vocab', 'q'=>'A "meticulous" person is...', 'opts'=>['Careless', 'Very careful and precise', 'Lazy', 'Rude'], 'a'=>'Very careful and precise'],

    // --- PART 3: GRAMMAR (21-30) ---
    ['sec'=>'grammar', 'q'=>'She ___ to the market every Sunday.', 'opts'=>['go', 'going', 'goes', 'gone'], 'a'=>'goes'],
    ['sec'=>'grammar', 'q'=>'They ___ watching TV right now.', 'opts'=>['is', 'are', 'am', 'do'], 'a'=>'are'],
    ['sec'=>'grammar', 'q'=>'I didn\'t ___ the answer.', 'opts'=>['know', 'knew', 'known', 'knows'], 'a'=>'know'],
    
    ['sec'=>'grammar', 'q'=>'I have lived here ___ 2010.', 'opts'=>['for', 'since', 'ago', 'in'], 'a'=>'since'],
    ['sec'=>'grammar', 'q'=>'If it rains, we ___ inside.', 'opts'=>['would stay', 'stayed', 'will stay', 'staying'], 'a'=>'will stay'],
    ['sec'=>'grammar', 'q'=>'The car ___ was stolen belongs to my neighbor.', 'opts'=>['who', 'whose', 'which', 'where'], 'a'=>'which'],
    ['sec'=>'grammar', 'q'=>'She is used to ___ up early.', 'opts'=>['get', 'getting', 'got', 'gets'], 'a'=>'getting'],
    
    ['sec'=>'grammar', 'q'=>'By this time next year, I ___ my degree.', 'opts'=>['will finish', 'will have finished', 'finish', 'have finished'], 'a'=>'will have finished'],
    ['sec'=>'grammar', 'q'=>'Seldom ___ such a beautiful sunset.', 'opts'=>['I have seen', 'have I seen', 'I saw', 'did I saw'], 'a'=>'have I seen'],
    ['sec'=>'grammar', 'q'=>'I would rather you ___ talk so loudly.', 'opts'=>['didn\'t', 'don\'t', 'won\'t', 'not'], 'a'=>'didn\'t'],
];

$showResults = false;
$readingLvl = $vocabLvl = $grammarLvl = $overallLvl = 'A1';
$readingCorrect = $vocabCorrect = $grammarCorrect = $totalScore = 0;

// --- POST İŞLEMİ (TEST BİTİNCE) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($questions as $i => $q) {
        if (isset($_POST["q$i"]) && $_POST["q$i"] === $q['a']) {
            $totalScore++;
            if ($q['sec'] == 'reading') $readingCorrect++;
            if ($q['sec'] == 'vocab') $vocabCorrect++;
            if ($q['sec'] == 'grammar') $grammarCorrect++;
        }
    }

    function calcLevel($score) { // 10 soru üzerinden
        if ($score >= 9) return 'C1';
        if ($score >= 7) return 'B2';
        if ($score >= 5) return 'B1';
        if ($score >= 3) return 'A2';
        return 'A1';
    }

    $readingLvl = calcLevel($readingCorrect);
    $vocabLvl   = calcLevel($vocabCorrect);
    $grammarLvl = calcLevel($grammarCorrect);

    // Genel Seviye (Toplam 30 üzerinden)
    if ($totalScore >= 27) $overallLvl = 'C1';
    elseif ($totalScore >= 21) $overallLvl = 'B2';
    elseif ($totalScore >= 15) $overallLvl = 'B1';
    elseif ($totalScore >= 9) $overallLvl = 'A2';
    else $overallLvl = 'A1';

    $stmt = $pdo->prepare("UPDATE users SET vocab_level=?, grammar_level=?, reading_level=?, has_taken_placement=1 WHERE id=?");
    $stmt->execute([$vocabLvl, $grammarLvl, $readingLvl, $userId]);
    
    $showResults = true; // Sonuç ekranını aktif et
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proficiency Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #1e1e2f; color: white; padding-bottom: 50px; }
        
        /* TEST STİLLERİ */
        .test-container { padding-top: 80px; max-width: 800px; margin: 0 auto; }
        #timer-container {
            position: fixed; top: 0; left: 0; width: 100%; 
            background: #0d6efd; color: white; 
            padding: 15px; z-index: 1000; 
            text-align: center; font-weight: bold; font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .test-card { background: rgba(255,255,255,0.95); color: black; padding: 25px; border-radius: 12px; margin-bottom: 25px; }
        .text-passage { background: #2c3e50; color: #ecf0f1; padding: 20px; border-radius: 10px; border-left: 5px solid #f1c40f; margin-bottom: 20px; line-height: 1.6; }
        .section-header { margin-top: 50px; margin-bottom: 20px; color: #ffc107; border-bottom: 1px solid #ffc107; padding-bottom: 10px; }
        .instruction-text { color: #aaa; margin-bottom: 15px; font-weight: 500; }
        .form-check-input { cursor: pointer; }
        .form-check-label { cursor: pointer; margin-left: 5px; }

        /* SONUÇ EKRANI STİLLERİ */
        .result-container {
            display: flex; justify-content: center; align-items: center; min-height: 100vh;
        }
        .result-card {
            background: #27293d; width: 100%; max-width: 600px;
            border-radius: 20px; padding: 40px; text-align: center;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }
        .score-circle {
            width: 120px; height: 120px; border-radius: 50%;
            background: rgba(13, 202, 240, 0.1); border: 4px solid #0dcaf0;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; font-weight: bold; color: #0dcaf0;
            margin: 0 auto 20px auto;
        }
        .skill-row {
            background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;
            margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;
        }
        .skill-name { font-weight: 600; font-size: 1.1rem; }
        .skill-val { font-weight: bold; color: #ffc107; }
    </style>
</head>
<body>

<?php if ($showResults): ?>
    <div class="result-container">
        <div class="result-card">
            <h2 class="fw-bold mb-4">Assessment Complete! 🎯</h2>
            
            <div class="score-circle">
                <?= $overallLvl ?>
            </div>
            <p class="text-white-50 mb-4">Your Overall Proficiency Level</p>

            <div class="text-start mb-4">
                <div class="skill-row">
                    <span class="skill-name">📖 Reading</span>
                    <span class="skill-val"><?= $readingLvl ?> <span class="small text-muted">(<?= $readingCorrect ?>/10)</span></span>
                </div>
                <div class="skill-row">
                    <span class="skill-name">📗 Vocabulary</span>
                    <span class="skill-val"><?= $vocabLvl ?> <span class="small text-muted">(<?= $vocabCorrect ?>/10)</span></span>
                </div>
                <div class="skill-row">
                    <span class="skill-name">📘 Grammar</span>
                    <span class="skill-val"><?= $grammarLvl ?> <span class="small text-muted">(<?= $grammarCorrect ?>/10)</span></span>
                </div>
            </div>

            <a href="dashboard.php" class="btn btn-success w-100 py-3 fw-bold rounded-pill fs-5 shadow">
                🚀 Continue to Dashboard
            </a>
        </div>
    </div>

<?php else: ?>
    <div id="timer-container">
        ⏱️ Time Left: <span id="time-display">30:00</span>
    </div>

    <div class="test-container">
        <div class="text-center mb-5">
            <h1 class="fw-bold">🎯 Proficiency Assessment</h1>
            <p class="text-white-50">Please answer all questions. The test will auto-submit when time runs out.</p>
        </div>

        <form method="post" id="examForm">
            
            <h3 class="section-header">📘 Part 1: Reading Comprehension</h3>
            
            <p class="instruction-text">Read the text below and answer Questions 1-3:</p>
            <div class="text-passage"><?= $textA ?></div>
            <?php for($i=0; $i<3; $i++): $q=$questions[$i]; ?>
                <div class="test-card">
                    <p class="fw-bold mb-3"><?= ($i + 1) ?>. <?= $q['q'] ?></p>
                    <?php foreach ($q['opts'] as $opt): ?>
                        <div class="form-check"><input class="form-check-input" type="radio" name="q<?= $i ?>" value="<?= $opt ?>"><label class="form-check-label"><?= $opt ?></label></div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>

            <p class="instruction-text mt-5">Read the text below and answer Questions 4-7:</p>
            <div class="text-passage"><?= $textB ?></div>
            <?php for($i=3; $i<7; $i++): $q=$questions[$i]; ?>
                <div class="test-card">
                    <p class="fw-bold mb-3"><?= ($i + 1) ?>. <?= $q['q'] ?></p>
                    <?php foreach ($q['opts'] as $opt): ?>
                        <div class="form-check"><input class="form-check-input" type="radio" name="q<?= $i ?>" value="<?= $opt ?>"><label class="form-check-label"><?= $opt ?></label></div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>

            <p class="instruction-text mt-5">Read the text below and answer Questions 8-10:</p>
            <div class="text-passage"><?= $textC ?></div>
            <?php for($i=7; $i<10; $i++): $q=$questions[$i]; ?>
                <div class="test-card">
                    <p class="fw-bold mb-3"><?= ($i + 1) ?>. <?= $q['q'] ?></p>
                    <?php foreach ($q['opts'] as $opt): ?>
                        <div class="form-check"><input class="form-check-input" type="radio" name="q<?= $i ?>" value="<?= $opt ?>"><label class="form-check-label"><?= $opt ?></label></div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>

            <h3 class="section-header">📗 Part 2: Vocabulary</h3>
            <p class="instruction-text">Select the best option to complete the sentence or answer the question.</p>
            <?php for($i=10; $i<20; $i++): $q=$questions[$i]; ?>
                <div class="test-card">
                    <p class="fw-bold mb-3"><?= ($i + 1) ?>. <?= $q['q'] ?></p>
                    <?php foreach ($q['opts'] as $opt): ?>
                        <div class="form-check"><input class="form-check-input" type="radio" name="q<?= $i ?>" value="<?= $opt ?>"><label class="form-check-label"><?= $opt ?></label></div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>

            <h3 class="section-header">📘 Part 3: Grammar</h3>
            <p class="instruction-text">Choose the grammatically correct option to complete the sentences.</p>
            <?php for($i=20; $i<30; $i++): $q=$questions[$i]; ?>
                <div class="test-card">
                    <p class="fw-bold mb-3"><?= ($i + 1) ?>. <?= $q['q'] ?></p>
                    <?php foreach ($q['opts'] as $opt): ?>
                        <div class="form-check"><input class="form-check-input" type="radio" name="q<?= $i ?>" value="<?= $opt ?>"><label class="form-check-label"><?= $opt ?></label></div>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-success w-100 py-4 fw-bold fs-3 shadow mt-4 mb-5">🚀 Submit Assessment</button>
        </form>
    </div>

    <script>
        let timeLeft = 1800; // 30 Dakika
        const timeDisplay = document.getElementById('time-display');
        const form = document.getElementById('examForm');

        const timerInterval = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timeDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (timeLeft <= 300) {
                document.getElementById('timer-container').style.background = "#dc3545";
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("⏳ Time is up! Submitting your answers automatically.");
                form.submit();
            }
        }, 1000);
    </script>
<?php endif; ?>

</body>
</html>