<?php
session_start();
include "db.php";

$error = "";

/* ====== FORM GÖNDERİLDİYSE ====== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {

        // Kullanıcıyı bul
        $stmt = $conn->prepare("
            SELECT id, username, password, level, placement_done, is_admin
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            // Şifre doğrulama
            if (password_verify($password, $user['password'])) {

                // ====== SESSION ATA ======
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['level']    = $user['level'];
                $_SESSION['is_admin'] = $user['is_admin'];

                // ====== YÖNLENDİRME ======
                if ($user['placement_done'] == 0) {
                    header("Location: placement_test.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit;

            } else {
                $error = "Invalid email or password.";
            }

        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ====== LOGIN KART ====== -->
<div class="auth-container">

    <div class="card auth-card">
        <h2>Login to FluentAI</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">

            <input 
                type="email" 
                name="email" 
                placeholder="Email address"
                required
            >

            <input 
                type="password" 
                name="password" 
                placeholder="Password"
                required
            >

            <button type="submit" class="btn-primary">
                Login
            </button>

        </form>

        <p class="auth-link">
            Don’t have an account?
            <a href="register.php">Register here</a>
        </p>

    </div>

</div>

</body>
</html>
