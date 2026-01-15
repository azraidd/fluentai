<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FluentAI – Learn English Smarter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar">
    <div class="nav-logo">Fluent<span>AI</span></div>
    <div class="nav-actions">
        <a href="login.php" class="nav-link">Login</a>
        <a href="register.php" class="btn-primary">Get Started</a>
    </div>
</nav>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero-content">
        <h1>
            Learn English <br>
            <span>Smarter</span> with AI 🤖
        </h1>
        <p>
            Improve your grammar, vocabulary, and speaking skills
            with level-based lessons and AI-powered practice.
        </p>
        <div class="hero-buttons">
            <a href="register.php" class="btn-primary btn-lg">Start Learning Free</a>
            <a href="login.php" class="btn-outline">I already have an account</a>
        </div>
    </div>

    <div class="hero-card">
        <div class="stat">
            <h3>📊 Level-Based</h3>
            <p>A1 – C1 smart progression</p>
        </div>
        <div class="stat">
            <h3>🤖 AI Practice</h3>
            <p>Instant feedback & roleplay</p>
        </div>
        <div class="stat">
            <h3>🔥 Gamified</h3>
            <p>XP, streaks & progress</p>
        </div>
    </div>
</section>

<!-- ================= FEATURES ================= -->
<section class="features">
    <h2>Why FluentAI?</h2>

    <div class="feature-grid">
        <div class="feature-card">
            <h3>📘 Smart Grammar</h3>
            <p>
                Practice grammar with short tests that adapt
                to your current English level.
            </p>
        </div>

        <div class="feature-card">
            <h3>📗 Vocabulary Sprint</h3>
            <p>
                Learn useful words fast with focused
                Word Sprint challenges.
            </p>
        </div>

        <div class="feature-card">
            <h3>📖 Reading Practice</h3>
            <p>
                Improve comprehension with real-life texts
                and smart questions.
            </p>
        </div>

        <div class="feature-card">
            <h3>🤖 AI Roleplay</h3>
            <p>
                Chat with AI in real-life scenarios
                and get instant corrections.
            </p>
        </div>
    </div>
</section>

<!-- ================= CTA ================= -->
<section class="cta">
    <h2>Start your English journey today</h2>
    <p>No credit card. No pressure. Just learning.</p>
    <a href="register.php" class="btn-primary btn-lg">Create Free Account</a>
</section>

<!-- ================= FOOTER ================= -->
<footer class="footer">
    <p>© <?= date("Y") ?> FluentAI · School Project</p>
</footer>

</body>
</html>
