<?php
session_start();
include "db.php";

/* ====== GİRİŞ KONTROL ====== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$message = "";
$error = "";

/* ====== MEVCUT KULLANICI BİLGİLERİ ====== */
$stmt = $conn->prepare("
    SELECT email, password 
    FROM users 
    WHERE id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

/* ====== EMAIL GÜNCELLE ====== */
if (isset($_POST['update_email'])) {

    $newEmail = trim($_POST['new_email']);

    if ($newEmail === "") {
        $error = "Email cannot be empty.";
    } else {
        // Email başka kullanıcıda var mı?
        $stmt = $conn->prepare("
            SELECT id FROM users 
            WHERE email = ? AND id != ?
        ");
        $stmt->bind_param("si", $newEmail, $userId);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            $error = "This email is already in use.";
        } else {
            $stmt = $conn->prepare("
                UPDATE users SET email = ? WHERE id = ?
            ");
            $stmt->bind_param("si", $newEmail, $userId);
            $stmt->execute();

            $message = "Email updated successfully.";
        }
    }
}

/* ====== ŞİFRE GÜNCELLE ====== */
if (isset($_POST['update_password'])) {

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];

    if ($current === "" || $new === "") {
        $error = "Please fill in all password fields.";
    } elseif (!password_verify($current, $user['password'])) {
        $error = "Current password is incorrect.";
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users SET password = ? WHERE id = ?
        ");
        $stmt->bind_param("si", $hashed, $userId);
        $stmt->execute();

        $message = "Password updated successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>⚙ Account Settings</h2>

<?php if ($message): ?>
    <p style="color:green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- ====== EMAIL GÜNCELLE ====== -->
<div class="card">
    <h3>Update Email</h3>

    <form method="post">
        <input 
            type="email" 
            name="new_email" 
            placeholder="New emai
