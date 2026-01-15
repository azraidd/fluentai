<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userLevel = $_SESSION['level'];

function levelValue($l){
    return match($l){
        "A1"=>1,"A2"=>2,"B1"=>3,"B2"=>4,"C1"=>5, default=>1
    };
}

/* ======================
   VOCABULARY BANK
   ====================== */
$vocab = [

"A1"=>[
["word"=>"apple","tr"=>"elma","def"=>"a round fruit","ex"=>"I eat an apple every day."],
["word"=>"house","tr"=>"ev","def"=>"a place where people live","ex"=>"My house is big."],
["word"=>"teacher","tr"=>"öğretmen","def"=>"a person who teaches","ex"=>"She is my English teacher."],
["word"=>"happy","tr"=>"mutlu","def"=>"feeling good","ex"=>"I feel happy today."],
["word"=>"book","tr"=>"kitap","def"=>"a set of pages","ex"=>"This book is interesting."],
["word"=>"water","tr"=>"su","def"=>"a clear liquid","ex"=>"Drink water every day."],
["word"=>"friend","tr"=>"arkadaş","def"=>"a person you like","ex"=>"He is my best friend."],
["word"=>"city","tr"=>"şehir","def"=>"a large town","ex"=>"I live in a big city."],
["word"=>"car","tr"=>"araba","def"=>"a vehicle","ex"=>"My car is red."],
["word"=>"food","tr"=>"yemek","def"=>"things people eat","ex"=>"This food is delicious."]
],

"A2"=>[
["word"=>"improve","tr"=>"geliştirmek","def"=>"to make better","ex"=>"I want to improve my English."],
["word"=>"decide","tr"=>"karar vermek","def"=>"to choose","ex"=>"She decided to study."],
["word"=>"travel","tr"=>"seyahat etmek","def"=>"to go to places","ex"=>"I like to travel abroad."],
["word"=>"comfortable","tr"=>"rahat","def"=>"feeling relaxed","ex"=>"This chair is comfortable."],
["word"=>"important","tr"=>"önemli","def"=>"having value","ex"=>"This exam is important."],
["word"=>"experience","tr"=>"deneyim","def"=>"knowledge from doing","ex"=>"I have work experience."],
["word"=>"difference","tr"=>"fark","def"=>"not the same","ex"=>"There is a difference."],
["word"=>"success","tr"=>"başarı","def"=>"achieving a goal","ex"=>"Success needs effort."],
["word"=>"problem","tr"=>"problem","def"=>"something wrong","ex"=>"We solved the problem."],
["word"=>"opinion","tr"=>"fikir","def"=>"what you think","ex"=>"In my opinion, it’s good."]
],

"B1"=>[
["word"=>"challenge","tr"=>"zorluk","def"=>"something difficult","ex"=>"Learning English is a challenge."],
["word"=>"opportunity","tr"=>"fırsat","def"=>"a good chance","ex"=>"This job is an opportunity."],
["word"=>"manage","tr"=>"yönetmek","def"=>"to control","ex"=>"She manages the team."],
["word"=>"develop","tr"=>"geliştirmek","def"=>"to grow","ex"=>"Skills develop with practice."],
["word"=>"environment","tr"=>"çevre","def"=>"natural world","ex"=>"Protect the environment."],
["word"=>"solution","tr"=>"çözüm","def"=>"answer to a problem","ex"=>"We found a solution."],
["word"=>"effective","tr"=>"etkili","def"=>"works well","ex"=>"This method is effective."],
["word"=>"increase","tr"=>"artmak","def"=>"to become more","ex"=>"Sales increased."],
["word"=>"reduce","tr"=>"azaltmak","def"=>"to make less","ex"=>"Reduce stress."],
["word"=>"consider","tr"=>"düşünmek","def"=>"think about","ex"=>"Consider the options."]
],

"B2"=>[
["word"=>"significant","tr"=>"önemli","def"=>"very important","ex"=>"There is a significant change."],
["word"=>"maintain","tr"=>"sürdürmek","def"=>"keep the same","ex"=>"Maintain quality."],
["word"=>"assume","tr"=>"varsaymak","def"=>"think something is true","ex"=>"I assume he knows."],
["word"=>"efficient","tr"=>"verimli","def"=>"works with little waste","ex"=>"An efficient system."],
["word"=>"consequence","tr"=>"sonuç","def"=>"result of action","ex"=>"Actions have consequences."],
["word"=>"priority","tr"=>"öncelik","def"=>"most important thing","ex"=>"Safety is a priority."],
["word"=>"commitment","tr"=>"bağlılık","def"=>"strong dedication","ex"=>"Show commitment."],
["word"=>"achieve","tr"=>"başarmak","def"=>"reach a goal","ex"=>"Achieve success."],
["word"=>"impact","tr"=>"etki","def"=>"strong effect","ex"=>"Social impact matters."],
["word"=>"strategy","tr"=>"strateji","def"=>"plan to succeed","ex"=>"Use a strategy."]
],

"C1"=>[
["word"=>"inevitable","tr"=>"kaçınılmaz","def"=>"cannot be avoided","ex"=>"Change is inevitable."],
["word"=>"ambiguous","tr"=>"belirsiz","def"=>"unclear meaning","ex"=>"An ambiguous answer."],
["word"=>"comprehensive","tr"=>"kapsamlı","def"=>"complete and detailed","ex"=>"A comprehensive report."],
["word"=>"sophisticated","tr"=>"karmaşık","def"=>"advanced","ex"=>"A sophisticated system."],
["word"=>"phenomenon","tr"=>"olay","def"=>"observable fact","ex"=>"A social phenomenon."],
["word"=>"hypothesis","tr"=>"hipotez","def"=>"theory to test","ex"=>"Test the hypothesis."],
["word"=>"substantial","tr"=>"önemli","def"=>"large in amount","ex"=>"Substantial improvement."],
["word"=>"implication","tr"=>"çıkarım","def"=>"suggested meaning","ex"=>"Political implications."],
["word"=>"notion","tr"=>"kavram","def"=>"idea","ex"=>"Reject the notion."],
["word"=>"articulate","tr"=>"ifade etmek","def"=>"express clearly","ex"=>"She articulated her ideas."]
],
];

/* ======================
   TEST MODE
   ====================== */
$level = $_GET['level'] ?? null;

if($level && isset($vocab[$level]) && levelValue($userLevel)>=levelValue($level)){

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $correct=0; $wrong=[];
        foreach($vocab[$level] as $i=>$w){
            if(($_POST["q$i"]??"")===$w['word']) $correct++;
            else $wrong[]=$w['word'];
        }

        $xp = $correct * 5;
        $_SESSION['review_words']=$wrong;
        $conn->query("UPDATE user_progress SET xp=xp+$xp WHERE user_id=$userId");

        echo "<h2 style='text-align:center'>Vocabulary Result</h2>";
        echo "<p style='text-align:center'>$correct / 10 correct</p>";
        echo "<p style='text-align:center'>+$xp XP</p>";
        echo "<p style='text-align:center'><a href='vocabulary.php'>Back</a></p>";
        exit;
    }

    echo "<h2>Vocabulary Test ($level)</h2><form method='post'>";
    foreach($vocab[$level] as $i=>$w){
        echo "<p>{$w['ex']}<br>";
        echo "<input name='q$i' placeholder='Type correct word'></p>";
    }
    echo "<button>Finish Test</button></form>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vocabulary</title>
<style>
body{font-family:Arial;background:#f6f7fb}
.container{max-width:900px;margin:40px auto}
.card{background:white;padding:25px;border-radius:15px;margin-bottom:20px}
.locked{opacity:.4}
</style>
</head>
<body>

<div class="container">
<h2>📗 Vocabulary Library</h2>

<?php foreach($vocab as $lvl=>$words): ?>
<div class="card <?= levelValue($userLevel)<levelValue($lvl)?'locked':'' ?>">
<h3><?= $lvl ?> Vocabulary</h3>

<ul>
<?php foreach($words as $w): ?>
<li>
<b><?= $w['word'] ?></b> (<?= $w['tr'] ?>) – <?= $w['def'] ?><br>
<small><?= $w['ex'] ?></small>
</li>
<?php endforeach; ?>
</ul>

<?php if(levelValue($userLevel)>=levelValue($lvl)): ?>
<a href="?level=<?= $lvl ?>">📝 Take Test</a>
<?php else: ?>
<p>🔒 Locked</p>
<?php endif; ?>
</div>
<?php endforeach; ?>

<a href="dashboard.php">⬅ Dashboard</a>
</div>
</body>
</html>
