<?php
session_start();
include "db.php";

$error = "";

/* ====== FORM GÖNDERİLDİYSE ====== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstName = trim($_POST['first_name']);
    $lastName  = trim($_POST['last_name']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];

    // Boş alan kontrolü
    if ($firstName === "" || $lastName === "" || $email === "" || $password === "") {
        $error = "Please fill in all fields.";
    }
    // Şifre kuralları
    elseif (!preg_match(
        '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/',
        $password
    )) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    }
    else {
        // Email kontrol
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            $error = "This email is already registered.";
        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $username = $firstName . " " . $lastName;

            $stmt = $conn->prepare("
                INSERT INTO users (username, email, password)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            $stmt->execute();

            $_SESSION['user_id']        = $stmt->insert_id;
            $_SESSION['username']       = $username;
            $_SESSION['level']          = "A1";
            $_SESSION['placement_done'] = 0;
            $_SESSION['is_admin']       = 0;

            header("Location: placement_test.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | FluentAI</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">

    <div class="card auth-card">
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">

            <div class="form-row">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>

            <input type="email" name="email" placeholder="Email Address" required>

            <input 
                type="password" 
                name="password" 
                placeholder="Password"
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}"
                title="At least 8 characters, including uppercase, lowercase, number, and special character"
                required
            >

            <small class="password-hint">
                Password must be at least 8 characters and include uppercase, lowercase, number, and special character.
            </small>

            <button type="submit" class="btn-primary">
                Register
            </button>

        </form>

        <p class="auth-link">
            Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </div>

</div>

</body>
</html>
