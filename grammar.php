<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId    = $_SESSION['user_id'];
$userLevel = $_SESSION['level'];

/* ======================
   SEVİYE DEĞERİ
   ====================== */
function levelValue($l) {
    return match($l) {
        "A1"=>1,"A2"=>2,"B1"=>3,"B2"=>4,"C1"=>5, default=>1
    };
}

/* ======================
   XP & STREAK TABLOSU
   ====================== */
$conn->query("
CREATE TABLE IF NOT EXISTS user_progress (
    user_id INT PRIMARY KEY,
    xp INT DEFAULT 0,
    streak INT DEFAULT 0,
    last_active DATE
)
");

$conn->query("
INSERT IGNORE INTO user_progress (user_id) VALUES ($userId)
");

/* ======================
   GRAMMAR SORULARI
   ====================== */
$grammarTests = [
"A1"=>[
["q"=>"She ___ to school.","a"=>"go","b"=>"goes","c"=>"going","d"=>"gone","cA"=>"b","tag"=>"present_simple"],
["q"=>"I ___ a student.","a"=>"is","b"=>"are","c"=>"am","d"=>"be","cA"=>"c","tag"=>"be"],
["q"=>"Plural of child?","a"=>"childs","b"=>"childes","c"=>"children","d"=>"childrens","cA"=>"c","tag"=>"plural"],
["q"=>"He ___ TV now.","a"=>"watch","b"=>"watches","c"=>"is watching","d"=>"watched","cA"=>"c","tag"=>"present_cont"],
["q"=>"Correct article","a"=>"a apple","b"=>"an apple","c"=>"the apple","d"=>"apple","cA"=>"b","tag"=>"article"],
["q"=>"I ___ coffee daily.","a"=>"drink","b"=>"drinks","c"=>"drinking","d"=>"drank","cA"=>"a","tag"=>"present_simple"],
["q"=>"Opposite of big?","a"=>"small","b"=>"tall","c"=>"fat","d"=>"long","cA"=>"a","tag"=>"vocab"],
["q"=>"He ___ a car.","a"=>"have","b"=>"has","c"=>"having","d"=>"had","cA"=>"b","tag"=>"have"],
["q"=>"She ___ happy.","a"=>"are","b"=>"is","c"=>"be","d"=>"been","cA"=>"b","tag"=>"be"],
["q"=>"We ___ friends.","a"=>"is","b"=>"are","c"=>"am","d"=>"be","cA"=>"b","tag"=>"be"],
],

"A2"=>[
["q"=>"She ___ here since 2020.","a"=>"lives","b"=>"lived","c"=>"has lived","d"=>"living","cA"=>"c","tag"=>"present_perfect"],
["q"=>"I didn’t ___.","a"=>"went","b"=>"go","c"=>"gone","d"=>"going","cA"=>"b","tag"=>"past_simple"],
["q"=>"Faster is ___ form.","a"=>"comparative","b"=>"superlative","c"=>"base","d"=>"noun","cA"=>"a","tag"=>"comparison"],
["q"=>"There ___ many people.","a"=>"is","b"=>"are","c"=>"be","d"=>"was","cA"=>"b","tag"=>"there_is"],
["q"=>"He speaks ___ than me.","a"=>"good","b"=>"better","c"=>"best","d"=>"well","cA"=>"b","tag"=>"comparison"],
["q"=>"If it rains, I ___ stay.","a"=>"will","b"=>"would","c"=>"was","d"=>"am","cA"=>"a","tag"=>"conditional"],
["q"=>"She enjoys ___.","a"=>"read","b"=>"to read","c"=>"reading","d"=>"reads","cA"=>"c","tag"=>"gerund"],
["q"=>"Looking ___ my keys.","a"=>"to","b"=>"for","c"=>"at","d"=>"on","cA"=>"b","tag"=>"preposition"],
["q"=>"He ___ finished.","a"=>"hasn’t","b"=>"didn’t","c"=>"isn’t","d"=>"wasn’t","cA"=>"a","tag"=>"present_perfect"],
["q"=>"She asked me ___ I was.","a"=>"what","b"=>"where","c"=>"why","d"=>"who","cA"=>"b","tag"=>"reported"],
],

"B1"=>[
["q"=>"If I ___ more time, I would travel.","a"=>"have","b"=>"had","c"=>"will have","d"=>"has","cA"=>"b","tag"=>"second_conditional"],
["q"=>"He suggested that we ___ earlier.","a"=>"leave","b"=>"left","c"=>"leaving","d"=>"will leave","cA"=>"a","tag"=>"subjunctive"],
["q"=>"She speaks English very ___.","a"=>"fluent","b"=>"fluency","c"=>"fluently","d"=>"more fluent","cA"=>"c","tag"=>"adverbs"],
["q"=>"The report ___ by the manager.","a"=>"wrote","b"=>"was written","c"=>"has write","d"=>"is writing","cA"=>"b","tag"=>"passive_voice"],
["q"=>"I’m not used to ___ up early.","a"=>"get","b"=>"getting","c"=>"got","d"=>"gets","cA"=>"b","tag"=>"used_to"],
["q"=>"He denied ___ the documents.","a"=>"steal","b"=>"to steal","c"=>"stealing","d"=>"stolen","cA"=>"c","tag"=>"gerund_infinitive"],
["q"=>"Rarely ___ such dedication.","a"=>"I see","b"=>"see I","c"=>"do I see","d"=>"I do see","cA"=>"c","tag"=>"inversion"],
["q"=>"She acted as if she ___ everything.","a"=>"knows","b"=>"knew","c"=>"has known","d"=>"know","cA"=>"b","tag"=>"as_if"],
["q"=>"The meeting was postponed ___ the strike.","a"=>"because","b"=>"because of","c"=>"although","d"=>"despite","cA"=>"b","tag"=>"connectors"],
["q"=>"He apologized ___ being late.","a"=>"for","b"=>"to","c"=>"about","d"=>"with","cA"=>"a","tag"=>"prepositions"],
],

"B2"=>[
["q"=>"Had I known earlier, I ___ differently.","a"=>"will act","b"=>"would act","c"=>"would have acted","d"=>"acted","cA"=>"c","tag"=>"third_conditional"],
["q"=>"The more you practice, ___ you become.","a"=>"better","b"=>"the better","c"=>"the best","d"=>"more better","cA"=>"b","tag"=>"comparative_structures"],
["q"=>"She objected to ___ treated unfairly.","a"=>"be","b"=>"being","c"=>"been","d"=>"have been","cA"=>"b","tag"=>"passive_gerund"],
["q"=>"Not only ___ late, but he also forgot the files.","a"=>"he was","b"=>"was he","c"=>"he is","d"=>"is he","cA"=>"b","tag"=>"inversion"],
["q"=>"He is said ___ the company.","a"=>"run","b"=>"to run","c"=>"running","d"=>"ran","cA"=>"b","tag"=>"reporting_verbs"],
["q"=>"No sooner had we arrived ___ it started raining.","a"=>"when","b"=>"than","c"=>"then","d"=>"while","cA"=>"b","tag"=>"no_sooner_than"],
["q"=>"She has her car ___.","a"=>"repair","b"=>"repaired","c"=>"repairing","d"=>"to repair","cA"=>"b","tag"=>"causative_have"],
["q"=>"The proposal was rejected ___ its high cost.","a"=>"due","b"=>"because","c"=>"due to","d"=>"although","cA"=>"c","tag"=>"formal_connectors"],
["q"=>"He speaks as though he ___ the expert.","a"=>"is","b"=>"was","c"=>"were","d"=>"be","cA"=>"c","tag"=>"subjunctive_were"],
["q"=>"Little ___ about the consequences.","a"=>"he knew","b"=>"did he know","c"=>"he knows","d"=>"knows he","cA"=>"b","tag"=>"negative_inversion"],
],

"C1"=>[
["q"=>"Scarcely ___ the announcement when reactions followed.","a"=>"had they made","b"=>"they had made","c"=>"have they made","d"=>"they make","cA"=>"a","tag"=>"advanced_inversion"],
["q"=>"The committee recommended that he ___ immediately.","a"=>"resigns","b"=>"resigned","c"=>"resign","d"=>"resigning","cA"=>"c","tag"=>"mandative_subjunctive"],
["q"=>"It is high time we ___ action.","a"=>"take","b"=>"took","c"=>"have taken","d"=>"will take","cA"=>"b","tag"=>"high_time"],
["q"=>"She is widely regarded ___ the leading expert.","a"=>"as","b"=>"to","c"=>"for","d"=>"like","cA"=>"a","tag"=>"collocations"],
["q"=>"Hardly ___ finished speaking when objections arose.","a"=>"he had","b"=>"had he","c"=>"he has","d"=>"has he","cA"=>"b","tag"=>"hardly_when"],
["q"=>"The findings bear little ___ to the initial hypothesis.","a"=>"relation","b"=>"connection","c"=>"similar","d"=>"comparison","cA"=>"a","tag"=>"academic_vocab"],
["q"=>"Were it not for her help, we ___ failed.","a"=>"will have","b"=>"would have","c"=>"would","d"=>"had","cA"=>"b","tag"=>"formal_conditional"],
["q"=>"He tends to overestimate his abilities, ___?","a"=>"doesn’t he","b"=>"isn’t he","c"=>"hasn’t he","d"=>"won’t he","cA"=>"a","tag"=>"tag_questions"],
["q"=>"The policy is intended to ___ growth.","a"=>"foster","b"=>"gain","c"=>"rise","d"=>"improve","cA"=>"a","tag"=>"advanced_vocab"],
["q"=>"So complex ___ the issue that few understood it.","a"=>"is","b"=>"was","c"=>"were","d"=>"be","cA"=>"a","tag"=>"so_adjective_inversion"],
]

];

/* ======================
   TEST SEÇİLDİ
   ====================== */
$level = $_GET['level'] ?? null;

if ($level && isset($grammarTests[$level]) && levelValue($userLevel) >= levelValue($level)) {

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $correct = 0;
        $wrongTags = [];

        foreach ($grammarTests[$level] as $i=>$q) {
            if (($_POST["q$i"] ?? "") === $q['cA']) {
                $correct++;
            } else {
                $wrongTags[] = $q['tag'];
            }
        }

        /* XP & STREAK */
        $xpEarned = $correct * 10;
        $today = date("Y-m-d");

        $progress = $conn->query("SELECT * FROM user_progress WHERE user_id=$userId")->fetch_assoc();

        $streak = $progress['streak'];
        if ($progress['last_active'] !== $today) {
            $streak = ($progress['last_active'] === date("Y-m-d", strtotime("-1 day"))) ? $streak+1 : 1;
        }

        $conn->query("
            UPDATE user_progress 
            SET xp = xp + $xpEarned, streak=$streak, last_active='$today'
            WHERE user_id=$userId
        ");

        $_SESSION['review_tags'] = array_unique($wrongTags);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <title>Grammar Result</title>
        <style>
        body{font-family:Arial;background:#f6f7fb;text-align:center}
        .box{background:white;width:420px;margin:100px auto;padding:30px;border-radius:15px}
        .xp{color:#58cc02;font-size:22px}
        </style>
        </head>
        <body>
        <div class="box">
            <h2>Grammar Completed 🎉</h2>
            <h1><?= $correct ?>/10</h1>
            <p class="xp">+<?= $xpEarned ?> XP</p>
            <p>🔥 Streak: <?= $streak ?> day(s)</p>
            <a href="review_mode.php">🔁 Review Mistakes</a><br><br>
            <a href="grammar.php">⬅ Back</a>
        </div>
        </body>
        </html>
        <?php exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title>Grammar Test</title>
    <style>
    body{font-family:Arial;background:#f6f7fb}
    .container{max-width:800px;margin:30px auto}
    .card{background:white;padding:20px;margin-bottom:15px;border-radius:12px}
    .progress{height:8px;background:#ddd;border-radius:10px;margin-bottom:20px}
    .fill{height:100%;background:#58cc02;width:0%}
    button{width:100%;padding:15px;background:#58cc02;color:white;border:none;border-radius:10px}
    </style>
    <script>
    function updateProgress(i){document.getElementById('fill').style.width=(i/10*100)+'%';}
    </script>
    </head>
    <body>
    <div class="container">
    <h2>Grammar Test (<?= $level ?>)</h2>
    <div class="progress"><div id="fill" class="fill"></div></div>
    <form method="post">
    <?php foreach ($grammarTests[$level] as $i=>$q): ?>
    <div class="card">
        <b><?= ($i+1) ?>. <?= $q['q'] ?></b><br><br>
        <?php foreach(['a','b','c','d'] as $o): ?>
        <label>
        <input onclick="updateProgress(<?= $i+1 ?>)" type="radio" name="q<?= $i ?>" value="<?= $o ?>" required>
        <?= $q[$o] ?>
        </label><br>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <button>Finish Test</button>
    </form>
    </div>
    </body>
    </html>
    <?php exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Grammar</title>
<style>
body{font-family:Arial;background:#f6f7fb}
.container{max-width:900px;margin:50px auto}
.card{background:white;padding:25px;border-radius:15px;margin-bottom:20px}
.locked{opacity:.4}
a.btn{background:#58cc02;color:white;padding:12px 20px;border-radius:8px;text-decoration:none}
</style>
</head>
<body>
<div class="container">
<h2>📘 Grammar Practice</h2>

<?php foreach ($grammarTests as $lvl=>$_): ?>
<div class="card <?= levelValue($userLevel)<levelValue($lvl)?'locked':'' ?>">
<h3><?= $lvl ?> Grammar</h3>
<?php if(levelValue($userLevel)>=levelValue($lvl)): ?>
<a class="btn" href="?level=<?= $lvl ?>">Start</a>
<?php else: ?>
<p>🔒 Locked</p>
<?php endif; ?>
</div>
<?php endforeach; ?>

<a href="dashboard.php">⬅ Dashboard</a>
</div>
</body>
</html>
