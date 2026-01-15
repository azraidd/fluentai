<?php
session_start();
include "db.php";

/* ====== GİRİŞ KONTROL ====== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

/* ====== PLAN KAYDET ====== */
if (isset($_POST['save_plan'])) {

    // Önce eski planı temizle (tek plan mantığı)
    $conn->query("DELETE FROM study_planner WHERE user_id = $userId");

    if (!empty($_POST['days'])) {
        foreach ($_POST['days'] as $day => $topic) {
            if ($topic !== "") {
                $stmt = $conn->prepare("
                    INSERT INTO study_planner (user_id, day, topic)
                    VALUES (?, ?, ?)
                ");
                $stmt->bind_param("iss", $userId, $day, $topic);
                $stmt->execute();
            }
        }
    }
}

/* ====== GÜN TAMAMLANDI ====== */
if (isset($_GET['complete'])) {
    $day = $_GET['complete'];
    $conn->query("
        UPDATE study_planner
        SET completed = 1
        WHERE user_id = $userId AND day = '$day'
    ");
}

/* ====== MEVCUT PLAN ====== */
$result = $conn->query("
    SELECT * FROM study_planner
    WHERE user_id = $userId
");
$plan = [];
while ($row = $result->fetch_assoc()) {
    $plan[$row['day']] = $row;
}

/* ====== HAFTANIN GÜNLERİ ====== */
$daysOfWeek = [
    "Monday", "Tuesday", "Wednesday",
    "Thursday", "Friday", "Saturday", "Sunday"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Planner | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>🗓 Weekly Study Planner</h2>
<p>Select what you want to study on each day.</p>

<form method="post">

<table border="1" cellpadding="10">
    <tr>
        <th>Day</th>
        <th>Topic</th>
        <th>Status</th>
    </tr>

    <?php foreach ($daysOfWeek as $day): ?>
        <tr>
            <td><?= $day ?></td>
            <td>
                <select name="days[<?= $day ?>]">
                    <option value="">-- Select --</option>
                    <option value="Grammar" <?= ($plan[$day]['topic'] ?? '') === 'Grammar' ? 'selected' : '' ?>>Grammar</option>
                    <option value="Vocabulary" <?= ($plan[$day]['topic'] ?? '') === 'Vocabulary' ? 'selected' : '' ?>>Vocabulary</option>
                    <option value="Reading" <?= ($plan[$day]['topic'] ?? '') === 'Reading' ? 'selected' : '' ?>>Reading</option>
                    <option value="AI Practice" <?= ($plan[$day]['topic'] ?? '') === 'AI Practice' ? 'selected' : '' ?>>AI Practice</option>
                </select>
            </td>
            <td>
                <?php if (!empty($plan[$day]) && $plan[$day]['completed']): ?>
                    ✅ Completed
                <?php elseif (!empty($plan[$day])): ?>
                    <a href="planner.php?complete=<?= $day ?>" class="btn-primary">Mark Done</a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<br>
<button type="submit" name="save_plan" class="btn-primary">
    Save Plan
</button>

</form>

<br>
<a href="dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</body>
</html>
