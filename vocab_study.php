<?php
session_start();
require 'db.php';

if (!isset($_GET['set_id'])) { header("Location: vocab.php"); exit; }
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$setId = intval($_GET['set_id']);

// Kelimeleri Veritabanından Çek
$stmt = $pdo->prepare("SELECT * FROM vocab_words WHERE set_id = ?");
$stmt->execute([$setId]);
$words = $stmt->fetchAll();

// Set Başlığını Çek
$setStmt = $pdo->prepare("SELECT title, level FROM vocab_sets WHERE id = ?");
$setStmt->execute([$setId]);
$setInfo = $setStmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Mode - English Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            color: white;
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        .word-card {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 7px solid #0d6efd;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .word-card:hover { transform: scale(1.02); }

        .btn-audio {
            background: #e9ecef;
            border: none;
            border-radius: 50%;
            width: 40px; height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex; align-items: center; justify-content: center;
        }
        .btn-audio:hover { background: #0d6efd; color: white; transform: scale(1.1); }

        .example-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px 15px;
            margin-top: 10px;
            font-style: italic;
            color: #555;
            display: flex; align-items: center; gap: 10px;
        }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 800px;">
    
    <div class="text-center mb-5">
        <span class="badge bg-warning text-dark mb-2"><?php echo $setInfo['level']; ?> Level</span>
        <h2 class="fw-bold"><?php echo $setInfo['title']; ?></h2>
        <p class="text-white-50">Listen and repeat the words below.</p>
        <a href="vocab.php" class="btn btn-outline-light btn-sm mt-2">⬅ Back to List</a>
    </div>

    <?php foreach($words as $w): ?>
        <div class="word-card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button onclick="speak('<?php echo addslashes($w['word']); ?>')" class="btn-audio" title="Listen">🔊</button>
                    <div>
                        <h3 class="fw-bold mb-0 text-primary"><?php echo ucfirst($w['word']); ?></h3>
                        <small class="text-muted" style="font-size: 1rem;"><?php echo $w['meaning']; ?></small>
                    </div>
                </div>
            </div>

            <div class="example-box">
                <button onclick="speak('<?php echo addslashes($w['example']); ?>')" class="btn-audio" style="width:30px; height:30px; font-size:0.9rem;">🗣️</button>
                "<?php echo $w['example']; ?>"
            </div>
        </div>
    <?php endforeach; ?>

    <div class="text-center mt-5">
        <a href="vocab_engine.php?set_id=<?php echo $setId; ?>" class="btn btn-success btn-lg px-5 shadow fw-bold">
            📝 I'm Ready for Quiz!
        </a>
    </div>

</div>

<script>
    let voices = [];

    // Sesleri Yükle
    function loadVoices() {
        // Opera bazen sesleri hemen yüklemez, biraz bekler
        voices = window.speechSynthesis.getVoices();
        
        // Seslerin yüklendiğinden emin olmak için konsola yazdıralım (Geliştirici modu için)
        console.log("Yüklenen Ses Sayısı: " + voices.length);
    }

    // Sesler değiştiğinde (Yüklendiğinde) listeyi güncelle
    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = loadVoices;
    }

    function speak(text) {
        // Önceki konuşmayı durdur
        window.speechSynthesis.cancel();

        // Sesler boşsa tekrar yüklemeyi dene
        if (voices.length === 0) {
            voices = window.speechSynthesis.getVoices();
        }

        let utterance = new SpeechSynthesisUtterance(text);

        // --- OPERA & WINDOWS İÇİN ÖZEL SEÇİM ---
        // 1. Önce "Google US English" ara (Belki vardır)
        // 2. Yoksa "Microsoft Zira" (Windows İngilizce Kadın Sesi) ara
        // 3. Yoksa "Microsoft David" (Windows İngilizce Erkek Sesi) ara
        // 4. Yoksa isminde "English" geçen herhangi bir sesi ara
        
        let selectedVoice = voices.find(v => v.name.includes("Google US English")) 
                         || voices.find(v => v.name.includes("Zira") && v.name.includes("English")) 
                         || voices.find(v => v.name.includes("David") && v.name.includes("English")) 
                         || voices.find(v => v.lang.startsWith("en"));

        if (selectedVoice) {
            utterance.voice = selectedVoice;
            utterance.lang = selectedVoice.lang;
            console.log("Seçilen Ses: " + selectedVoice.name); // Hangi sesi seçtiğini görelim
        } else {
            // Hiçbiri yoksa tarayıcıya "Bunu İngilizce oku" diye zorla
            utterance.lang = 'en-US';
        }

        utterance.rate = 0.9; // Hız
        utterance.pitch = 1;  // Ton

        window.speechSynthesis.speak(utterance);
    }

    // Sayfa açıldıktan 500ms sonra sesleri yüklemeyi tetikle (Opera hatasını önler)
    setTimeout(loadVoices, 500);
</script>

</body>
</html>