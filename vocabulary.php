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
   VOCABULARY BANK (Static Data)
   ====================== */
/* ======================
   VOCABULARY BANK (Tam Liste)
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
]

];

/* ======================
   TEST MODU (SORU EKRANI)
   ====================== */
$level = $_GET['level'] ?? null;

// Eğer bir seviye seçildiyse TESTİ GÖSTER
if($level && isset($vocab[$level])){

    // Test Sonucu Gönderme (POST)
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $correct=0; 
        $wrong=[];
        
        foreach($vocab[$level] as $i=>$w){
            // Kullanıcının cevabını küçült ve boşlukları temizle
            $userAns = strtolower(trim($_POST["q$i"] ?? ""));
            $correctAns = strtolower($w['word']);
            
            if($userAns === $correctAns) {
                $correct++;
            } else {
                $wrong[] = $w['word'];
            }
        }

        // XP Ekleme
        $xp = $correct * 5;
        // Basitçe XP'yi güncelle (User Progress tablosu varsa)
        $conn->query("UPDATE user_progress SET xp=xp+$xp WHERE user_id=$userId");

        // SONUÇ EKRANI
        echo "<!DOCTYPE html><html><head><title>Result</title><link rel='stylesheet' href='style.css'></head><body>";
        echo "<div class='container' style='text-align:center; margin-top:50px;'>";
        echo "<div class='card'>";
        echo "<h2>🎉 Vocabulary Quiz Completed</h2>";
        echo "<h1>$correct / 10</h1>";
        echo "<p style='color:#58cc02; font-weight:bold;'>+$xp XP Earned</p>";
        
        if(!empty($wrong)){
            echo "<div style='text-align:left; margin-top:20px;'>";
            echo "<h4>Words to study:</h4><ul>";
            foreach($wrong as $wr) echo "<li>$wr</li>";
            echo "</ul></div>";
        }

        echo "<br><a href='vocabulary.php' class='btn-primary'>Back to List</a>";
        echo "</div></div></body></html>";
        exit;
    }

    // TEST FORMUNU GÖSTER
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Vocabulary Test (<?= $level ?>)</title>
        <link rel="stylesheet" href="style.css"> </head>
    <body>
    
    <div class="container" style="max-width:800px; margin:40px auto;">
        <h2>📝 Vocabulary Test (<?= $level ?>)</h2>
        <p>Fill in the blanks with the correct word.</p>
        
        <form method="post">
            <?php foreach($vocab[$level] as $i=>$w): ?>
                
                <?php 
                    // SİHİRLİ KISIM: Kelimeyi cümleden gizle
                    // "I eat an apple" -> "I eat an _______"
                    $hiddenSentence = str_ireplace($w['word'], "<span style='border-bottom:2px solid #58cc02; font-weight:bold;'>_______</span>", $w['ex']);
                ?>

                <div class="card">
                    <p style="font-size:18px; margin-bottom:10px;">
                        <strong><?= ($i+1) ?>.</strong> <?= $hiddenSentence ?>
                    </p>
                    
                    <input type="text" name="q<?= $i ?>" placeholder="Type the missing word..." autocomplete="off" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:5px;">
                </div>

            <?php endforeach; ?>
            
            <button type="submit" class="btn-primary" style="width:100%; margin-top:20px;">Finish Test</button>
        </form>
    </div>

    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Vocabulary</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" style="max-width:900px; margin:40px auto;">
    <div class="top-bar">
        <h2>📗 Vocabulary Library</h2>
        <a href="dashboard.php" class="btn-primary">⬅ Dashboard</a>
    </div>

    <div class="dashboard-grid"> <?php foreach($vocab as $lvl=>$words): ?>
            <?php 
               // Kilit Mantığı
               $isLocked = levelValue($userLevel) < levelValue($lvl);
            ?>
            
            <div class="card <?= $isLocked ? 'locked' : '' ?>" style="text-align:center;">
                <h3><?= $lvl ?> Vocabulary</h3>
                <p>Learn 10 new words</p>
                
                <?php if(!$isLocked): ?>
                    <a href="?level=<?= $lvl ?>" class="btn-primary">Start Test</a>
                <?php else: ?>
                    <button class="btn-primary" style="background:#ccc; cursor:not-allowed;">🔒 Locked</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>