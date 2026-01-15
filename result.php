<?php
session_start();
include "db.php";

$questions = $_SESSION['grammar_questions'];
$userId = $_SESSION['user_id'];

$score = 0;
$wrongTags = [];

foreach($questions as $q){
    $userAnswer = $_POST['q'.$q['id']] ?? '';
    if($userAnswer == $q['correct_answer']){
        $score++;
    } else {
        $wrongTags[] = $q['grammar_tag'];
    }
}

$wrongTagString = implode(",", $wrongTags);

$conn->query("
    INSERT INTO grammar_results (user_id, test_id, score, wrong_tags)
    VALUES ($userId, {$questions[0]['test_id']}, $score, '$wrongTagString')
");
?>

<h2>🎉 Test Completed</h2>

<p>Score: <?= $score ?>/10</p>

<?php if(!empty($wrongTags)): ?>
<p><strong>Weak Topics:</strong> <?= implode(", ", array_unique($wrongTags)) ?></p>
<?php endif; ?>

<a href="grammar.php" class="btn-primary">Back to Grammar</a>
