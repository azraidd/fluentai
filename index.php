<?php
session_start();
require 'db.php';

$message = "";
$messageType = ""; 
$active_form = 'login'; 

// URL kontrolü
if (isset($_GET['form']) && $_GET['form'] == 'register') {
    $active_form = 'register';
}

// --- REGISTER PROCESS ---
if (isset($_POST['register'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    $email = trim($_POST['email']);
    $active_form = 'register'; 

    if (empty($user) || empty($pass) || empty($email)) {
        $message = "Please fill in all fields!";
        $messageType = "warning";
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            // Köşeli parantez yerine array() kullandım, hata vermez
            if ($stmt->execute(array($user, $email, $hashed_password))) {
                $message = "Registration successful! Please login.";
                $messageType = "success";
                $active_form = 'login'; 
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "This username is already taken!";
            } else {
                $message = "An error occurred: " . $e->getMessage();
            }
            $messageType = "danger";
        }
    }
}

// --- LOGIN PROCESS ---
if (isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (empty($user) || empty($pass)) {
        $message = "Username and password cannot be empty!";
        $messageType = "warning";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        // Burayı da array() yaptım
        $stmt->execute(array($user));
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userRow) {
            if (password_verify($pass, $userRow['password'])) {
                $_SESSION['user_id'] = $userRow['id'];
                $_SESSION['username'] = $userRow['username'];
                $_SESSION['level'] = $userRow['level'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $message = "Incorrect password!";
                $messageType = "danger";
            }
        } else {
            $message = "User not found.";
            $messageType = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - English Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1507842217121-9d5961143698?q=80&w=1920&auto=format&fit=crop');
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .card {
            width: 400px;
            padding: 40px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 15px;
        }
        
        .btn-primary {
            padding: 12px; border-radius: 10px; font-weight: 600;
            background: linear-gradient(45deg, #0d6efd, #0043a8); border: none;
        }
        
        .btn-success {
            padding: 12px; border-radius: 10px; font-weight: 600;
            background: linear-gradient(45deg, #198754, #146c43); border: none;
        }
        
        .nav-link { text-decoration: none; cursor: pointer; font-weight: 500; }
    </style>
</head>
<body>

<div class="card">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-dark">🎓 English Master</h2>
        <p class="text-muted">Login to start learning</p>
    </div>
    
    <?php if($message): ?>
        <div class="alert alert-<?php echo $messageType; ?> text-center">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($active_form == 'login'): ?>
        <form method="POST" action="index.php?form=login">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" name="login" class="btn btn-primary w-100 shadow">Login</button>
        </form>
        <div class="text-center mt-4">
            <small class="text-muted">Don't have an account?</small><br>
            <a href="index.php?form=register" class="nav-link">Register Now</a>
        </div>

    <?php else: ?>
        <form method="POST" action="index.php?form=register">
            <input type="text" name="username" class="form-control" placeholder="Choose Username" required>
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            <input type="password" name="password" class="form-control" placeholder="Set Password" required>
            <button type="submit" name="register" class="btn btn-success w-100 shadow">Create Account</button>
        </form>
        <div class="text-center mt-4">
            <small class="text-muted">Already have an account?</small><br>
            <a href="index.php?form=login" class="nav-link">Login</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>