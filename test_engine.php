<?php
session_start();
include "db.php";

$testId = $_GET['test_id'];
$type = $_GET['type'];

$q = $conn->query("
    SELECT * FROM grammar_questions 
    WHERE test_id=$testId 
    ORDER BY RAND() LIMIT 10
");

$questions = [];
while($row = $q->fetch_assoc()){
    $questions[] = $row;
}

$_SESSION['grammar_questions'] = $questions;
?>

<form method="post" action="result.php">
<?php foreach($questions as $index => $q): ?>

    <div class="question-box">
        <p><strong><?= ($index+1) ?>.</strong> <?= $q['question'] ?></p>

        <?php if($q['type'] == 'mcq'): ?>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="A"> <?= $q['option_a'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="B"> <?= $q['option_b'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="C"> <?= $q['option_c'] ?></label><br>
            <label><input type="radio" name="q<?= $q['id'] ?>" value="D"> <?= $q['option_d'] ?></label>

        <?php else: ?>
            <input type="text" name="q<?= $q['id'] ?>" placeholder="Reorder sentence">
        <?php endif; ?>

    </div>

<?php endforeach; ?>

<button type="submit" class="btn-primary">Finish Test</button>
</form>

<body data-test-type="grammar">
