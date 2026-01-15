<?php
session_start();
include "db.php";

/*
GÜVENLİK NOTU:
Gerçek projede admin kontrolü yapılmalı.
Şimdilik basit kontrol (okul projesi için yeterli)
*/
if (!isset($_SESSION['is_admin'])) {
    die("Access Denied");
}

/* ====== İSTATİSTİK SORGULARI ====== */

// Toplam kullanıcı
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users")
                   ->fetch_assoc()['total'];

// Seviye dağılımı
$levels = $conn->query("
    SELECT level, COUNT(*) as count 
    FROM users 
    GROUP BY level
");

// Toplam tamamlanan grammar testleri
$totalGrammar = $conn->query("
    SELECT COUNT(*) AS total 
    FROM grammar_results
")->fetch_assoc()['total'];

// Ortalama grammar skoru
$avgGrammar = $conn->query("
    SELECT AVG(score) AS avg_score 
    FROM grammar_results
")->fetch_assoc()['avg_score'];

// Son 7 gün aktivite
$recentActivity = $conn->query("
    SELECT DATE(created_at) as day, COUNT(*) as count
    FROM grammar_results
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>📊 Admin Dashboard</h1>

<!-- ====== GENEL İSTATİSTİKLER ====== -->
<div class="admin-cards">

    <div class="card">
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
    </div>

    <div class="card">
        <h3>Completed Grammar Tests</h3>
        <p><?= $totalGrammar ?></p>
    </div>

    <div class="card">
        <h3>Average Grammar Score</h3>
        <p><?= round($avgGrammar, 2) ?>/10</p>
    </div>

</div>

<!-- ====== SEVİYE DAĞILIMI ====== -->
<h2>User Level Distribution</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Level</th>
        <th>User Count</th>
    </tr>

    <?php while ($row = $levels->fetch_assoc()): ?>
        <tr>
            <td><?= $row['level'] ?></td>
            <td><?= $row['count'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- ====== SON 7 GÜN AKTİVİTE ====== -->
<h2>Activity (Last 7 Days)</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Date</th>
        <th>Completed Tests</th>
    </tr>

    <?php while ($row = $recentActivity->fetch_assoc()): ?>
        <tr>
            <td><?= $row['day'] ?></td>
            <td><?= $row['count'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- ====== YÖNETİM BUTONLARI ====== -->
<div style="margin-top:30px;">
    <a href="content_manager.php" class="btn-primary">Manage Content</a>
    <a href="../dashboard.php" class="btn-primary">Back to Site</a>
</div>

</body>
</html>
