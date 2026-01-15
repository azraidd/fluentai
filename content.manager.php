<?php
session_start();
include "db.php";

/* ====== ADMIN KONTROL ====== */
if (!isset($_SESSION['is_admin'])) {
    die("Access Denied");
}

/* ====== GRAMMAR TEST EKLEME ====== */
if (isset($_POST['add_test'])) {
    $title = $_POST['title'];
    $level = $_POST['level'];

    $conn->query("
        INSERT INTO grammar_tests (title, level)
        VALUES ('$title', '$level')
    ");
}

/* ====== GRAMMAR SORU EKLEME ====== */
if (isset($_POST['add_question'])) {
    $test_id = $_POST['test_id'];
    $question = $_POST['question'];
    $type = $_POST['type'];
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];
    $d = $_POST['d'];
    $correct = $_POST['correct'];
    $tag = $_POST['tag'];

    $conn->query("
        INSERT INTO grammar_questions
        (test_id, question, type, option_a, option_b, option_c, option_d, correct_answer, grammar_tag)
        VALUES
        ($test_id, '$question', '$type', '$a', '$b', '$c', '$d', '$correct', '$tag')
    ");
}

/* ====== AI SENARYO EKLEME ====== */
if (isset($_POST['add_scenario'])) {
    $title = $_POST['scenario_title'];
    $prompt = $_POST['scenario_prompt'];

    $conn->query("
        INSERT INTO ai_scenarios (title, prompt)
        VALUES ('$title', '$prompt')
    ");
}

/* ====== MEVCUT TESTLER ====== */
$tests = $conn->query("SELECT * FROM grammar_tests");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Content Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>🛠 Content Manager</h1>

<!-- ====== GRAMMAR TEST EKLE ====== -->
<h2>Add Grammar Test</h2>
<form method="post">
    <input type="text" name="title" placeholder="Test Title" required>
    <select name="level" required>
        <option value="A1">A1</option>
        <option value="A2">A2</option>
        <option value="B1">B1</option>
    </select>
    <button type="submit" name="add_test" class="btn-primary">Add Test</button>
</form>

<hr>

<!-- ====== GRAMMAR SORU EKLE ====== -->
<h2>Add Grammar Question</h2>
<form method="post">
    <select name="test_id" required>
        <?php while ($t = $tests->fetch_assoc()): ?>
            <option value="<?= $t['id'] ?>">
                <?= $t['title'] ?> (<?= $t['level'] ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <textarea name="question" placeholder="Question text" required></textarea>

    <select name="type">
        <option value="mcq">Multiple Choice</option>
        <option value="reorder">Reorder Sentence</option>
    </select>

    <input type="text" name="a" placeholder="Option A">
    <input type="text" name="b" placeholder="Option B">
    <input type="text" name="c" placeholder="Option C">
    <input type="text" name="d" placeholder="Option D">

    <input type="text" name="correct" placeholder="Correct Answer (A/B/C/D or full sentence)" required>
    <input type="text" name="tag" placeholder="Grammar Tag (tense, preposition)" required>

    <button type="submit" name="add_question" class="btn-primary">Add Question</button>
</form>

<hr>

<!-- ====== AI PRACTICE SENARYO EKLE ====== -->
<h2>Add AI Practice Scenario</h2>
<form method="post">
    <input type="text" name="scenario_title" placeholder="Scenario Title" required>
    <textarea name="scenario_prompt" placeholder="AI Prompt (You are a waiter...)" required></textarea>
    <button type="submit" name="add_scenario" class="btn-primary">Add Scenario</button>
</form>

<br>
<a href="admin_dashboard.php" class="btn-primary">⬅ Back to Dashboard</a>

</body>
</html>
