<?php
session_start();
require 'db.php';

// Güvenlik
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Student';
$userId = $_SESSION['user_id'];

// --- SKIP İŞLEMİ ---
if (isset($_GET['action']) && $_GET['action'] == 'skip_placement') {
    $upd = $pdo->prepare("UPDATE users SET has_taken_placement = 1 WHERE id = ?");
    $upd->execute([$userId]);
    header("Location: dashboard.php");
    exit;
}

// 1. KULLANICININ VERİLERİNİ ÇEK
$stmt = $pdo->prepare("
    SELECT u.vocab_level, u.grammar_level, u.reading_level, u.has_taken_placement, 
           COALESCE(p.xp, 0) as xp 
    FROM users u 
    LEFT JOIN user_progress p ON u.id = p.user_id 
    WHERE u.id = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Varsayılanlar
$vocabLvl = $user['vocab_level'] ?? 'A1';
$grammarLvl = $user['grammar_level'] ?? 'A1';
$readingLvl = $user['reading_level'] ?? 'A1';
$hasTakenPlacement = $user['has_taken_placement'] ?? 0;
$xp = $user['xp'];

// Genel Seviye Hesapla
function getLvlVal($l) { return match($l) { "A1"=>1, "A2"=>2, "B1"=>3, "B2"=>4, "C1"=>5, default=>1 }; }
$minVal = min(getLvlVal($vocabLvl), getLvlVal($grammarLvl), getLvlVal($readingLvl));
$overallLevel = match($minVal) { 1=>"A1", 2=>"A2", 3=>"B1", 4=>"B2", 5=>"C1", default=>"A1" };
$_SESSION['level'] = $overallLevel; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - English Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: white;
            min-height: 100vh;
            
            /* --- ARKA PLAN FOTOĞRAFI --- */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=2041&auto=format&fit=crop');
            
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .navbar { 
            background: rgba(0, 0, 0, 0.2); 
            backdrop-filter: blur(10px); 
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); 
            padding: 15px 0; 
        }

        .xp-display { 
            background: rgba(0, 0, 0, 0.4); 
            color: #58cc02; 
            padding: 8px 15px; 
            border-radius: 20px; 
            font-weight: bold; 
            margin-right: 15px; 
            border: 1px solid rgba(88, 204, 2, 0.5); 
        }

        .module-card {
            background: rgba(255, 255, 255, 0.05); /* Daha şeffaf */
            backdrop-filter: blur(10px); 
            border-radius: 20px; 
            padding: 30px; 
            text-align: center;
            transition: transform 0.3s ease, border-color 0.3s ease, background 0.3s; 
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none; 
            color: white; 
            display: block; 
            height: 100%; 
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .module-card:hover { 
            transform: translateY(-10px); 
            border-color: #ffc107; 
            background: rgba(255, 255, 255, 0.1); 
            color: white; 
        }

        .icon-box { font-size: 3.5rem; margin-bottom: 15px; text-shadow: 0 5px 15px rgba(0,0,0,0.5); }
        .skill-badge { background: rgba(255, 193, 7, 0.15); color: #ffc107; padding: 5px 10px; border-radius: 10px; font-size: 0.9rem; font-weight: bold; border: 1px solid rgba(255, 193, 7, 0.3); }
        
        .btn-leaderboard { 
            background: linear-gradient(45deg, #FFD700, #FFA500); 
            color: black; 
            font-weight: bold; 
            border: none; 
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2); 
        }
        .btn-leaderboard:hover { transform: scale(1.05); color: black; }
        
        .btn-logout { border: 1px solid rgba(255, 255, 255, 0.3); color: #ccc; }
        .btn-logout:hover { background: white; color: black; }

        /* Modal */
        .modal-content { background: #232734; color: white; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 50px rgba(0,0,0,0.5); }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="#">English Master</a>
        <div class="d-flex align-items-center ms-auto">
            <div class="xp-display">⚡ <?= number_format($xp) ?> XP</div>
            <a href="leaderboard.php" class="btn btn-leaderboard rounded-pill px-4 me-3">🏆 Leaderboard</a>
            <a href="logout.php" class="btn btn-sm btn-logout rounded-pill px-3">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row align-items-center mb-5">
        <div class="col-md-8 welcome-text">
            <h2 class="display-5 fw-bold">Welcome back, <?= htmlspecialchars($username); ?>! 👋</h2>
            <p class="fs-5 text-white-50">Your library of knowledge awaits. Ready to learn?</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-block text-center p-3 rounded-3" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <small class="text-white-50 d-block mb-1">OVERALL LEVEL</small>
                <span class="display-4 fw-bold text-warning" style="text-shadow: 0 0 20px rgba(255, 193, 7, 0.3);"><?= $overallLevel ?></span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="vocab.php" class="module-card">
                <div class="icon-box">📗</div>
                <h3>Vocabulary</h3>
                <p class="text-white-50">Current: <span class="text-warning fw-bold"><?= $vocabLvl ?></span></p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="grammar.php" class="module-card">
                <div class="icon-box">📘</div>
                <h3>Grammar</h3>
                <p class="text-white-50">Current: <span class="text-warning fw-bold"><?= $grammarLvl ?></span></p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="reading.php" class="module-card">
                <div class="icon-box">📖</div>
                <h3>Reading</h3>
                <p class="text-white-50">Current: <span class="text-warning fw-bold"><?= $readingLvl ?></span></p>
            </a>
        </div>
    </div>
</div>

<?php if ($hasTakenPlacement == 0): ?>
<div class="modal fade" id="placementModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">🎯 Welcome to English Master!</h5>
      </div>
      <div class="modal-body">
        <p>Would you like to take a quick <strong>Proficiency Test</strong> to determine your starting level?</p>
        <p class="small text-white-50">If you skip, you will start from <strong>A1 (Beginner)</strong>.</p>
      </div>
      <div class="modal-footer">
        <a href="dashboard.php?action=skip_placement" class="btn btn-outline-light">No, Start from A1</a>
        <a href="placement_test.php" class="btn btn-primary fw-bold">🚀 Yes, Take the Test</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var myModal = new bootstrap.Modal(document.getElementById('placementModal'));
    myModal.show();
</script>
<?php endif; ?>

</body>
</html>