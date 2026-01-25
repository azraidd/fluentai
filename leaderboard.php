<?php
// Hataları gizle
error_reporting(0);
session_start();
require 'db.php';

// Güvenlik: Giriş yapmayan göremez
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$currentUserId = $_SESSION['user_id'];
$currentUsername = $_SESSION['username'] ?? 'Student'; // Session'dan adı al

// --- LİDERLİK TABLOSU VERİSİNİ ÇEK ---
$sql = "
    SELECT u.username, COALESCE(p.xp, 0) as xp, COALESCE(p.streak, 0) as streak 
    FROM users u 
    LEFT JOIN user_progress p ON u.id = p.user_id 
    ORDER BY xp DESC, streak DESC 
    LIMIT 50
";
try {
    $stmt = $pdo->query($sql);
    $leaders = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Veritabanı Hatası.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard - Top Learners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e2f; /* Sayfa Arka Planı */
            color: white;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        .leaderboard-card {
            background: #27293d; /* Kart Rengi */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* TABLO STİLLERİ (KARANLIK MOD DÜZELTMESİ) */
        .table {
            color: white;
            margin-top: 20px;
            --bs-table-bg: transparent;       /* Arka planı şeffaf yap */
            --bs-table-color: white;          /* Yazı rengi beyaz */
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05); /* Hover olunca hafif gri */
            --bs-table-hover-color: white;
        }
        
        /* Hücrelerin arka planını zorla şeffaf yap */
        .table td, .table th {
            background-color: transparent !important;
            color: white !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            vertical-align: middle;
            padding: 15px 10px;
        }

        /* Hover efekti (Satır üzerine gelince) */
        .table-hover tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Başlıklar */
        .table thead th {
            border-bottom: 2px solid rgba(255,255,255,0.1);
            color: #aaa !important;
            font-weight: 400;
        }

        /* İlk 3 Sıra İçin Renkler */
        .rank-1 { color: #FFD700 !important; font-weight: bold; font-size: 1.3rem; } /* Altın */
        .rank-2 { color: #C0C0C0 !important; font-weight: bold; font-size: 1.2rem; } /* Gümüş */
        .rank-3 { color: #CD7F32 !important; font-weight: bold; font-size: 1.2rem; } /* Bronz */

        /* Kullanıcının Kendisi */
        .current-user-row td {
            background: rgba(55, 81, 255, 0.2) !important; /* Mavi Vurgu */
            border-top: 1px solid #3751ff;
            border-bottom: 1px solid #3751ff !important;
        }

        .xp-badge {
            background: rgba(88, 204, 2, 0.2);
            color: #58cc02;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            border: 1px solid rgba(88, 204, 2, 0.3);
        }

        .avatar-circle {
            width: 40px; height: 40px; 
            background: #3e4157; 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 900px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">🏆 Leaderboard</h2>
            <p class="text-white-50">Top students this week</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-light px-4">⬅ Dashboard</a>
    </div>

    <div class="leaderboard-card">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" width="10%">Rank</th>
                    <th scope="col">Student</th>
                    <th scope="col" class="text-center">Streak</th>
                    <th scope="col" class="text-end">Total XP</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                foreach($leaders as $user): 
                    $isMe = ($user['username'] == $currentUsername);
                    
                    // Madalya İkonları
                    $rankIcon = "#" . $rank;
                    if($rank == 1) $rankIcon = "🥇";
                    if($rank == 2) $rankIcon = "🥈";
                    if($rank == 3) $rankIcon = "🥉";
                ?>
                <tr class="<?= $isMe ? 'current-user-row' : '' ?>">
                    <td class="rank-<?= $rank ?>"><?php echo $rankIcon; ?></td>
                    
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle">
                                <?= strtoupper(substr($user['username'], 0, 1)) ?>
                            </div>
                            <span class="<?= $isMe ? 'fw-bold text-warning' : '' ?>">
                                <?= htmlspecialchars($user['username']) ?> 
                                <?= $isMe ? '(You)' : '' ?>
                            </span>
                        </div>
                    </td>

                    <td class="text-center">
                        <?php if($user['streak'] > 0): ?>
                            <span class="text-danger fw-bold">🔥 <?= $user['streak'] ?></span>
                        <?php else: ?>
                            <span class="text-white-50">-</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-end">
                        <span class="xp-badge"><?= number_format($user['xp']) ?> XP</span>
                    </td>
                </tr>
                <?php $rank++; endforeach; ?>
            </tbody>
        </table>
        
        <?php if(count($leaders) == 0): ?>
            <p class="text-center text-white-50 mt-4">No users found yet. Start solving quizzes!</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>