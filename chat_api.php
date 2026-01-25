<?php
// Hataları gizle
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

// Kullanıcıdan gelen mesajı al
$message = isset($_POST['message']) ? strtolower(trim($_POST['message'])) : '';
$scenario = isset($_POST['scenario']) ? $_POST['scenario'] : 'waiter';

// Biraz bekleme ekleyelim ki gerçek AI gibi düşündüğü sanılsın (1 saniye)
sleep(1);

$reply = "";
$correction = null;

// --- SENARYOLAR (FAKE AI BEYNİ) ---

// 1. GARSON SENARYOSU
if ($scenario == 'waiter') {
    if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
        $reply = "Hello! Welcome to our cafe. Here is the menu. What would you like to drink?";
    } 
    elseif (strpos($message, 'coffee') !== false) {
        $reply = "Great choice! Would you like milk or sugar with your coffee?";
        // Eğer kullanıcı basit bir cümle kurduysa düzeltme öner
        if (strpos($message, 'want') !== false) {
            $correction = "I would like a coffee, please.";
        }
    } 
    elseif (strpos($message, 'tea') !== false) {
        $reply = "Sure, we have Green Tea and Black Tea. Which one do you prefer?";
    }
    elseif (strpos($message, 'water') !== false) {
        $reply = "Coming right up! Still or sparkling water?";
    }
    elseif (strpos($message, 'yes') !== false || strpos($message, 'please') !== false) {
        $reply = "Perfect! I will bring that to your table in a minute.";
    } 
    elseif (strpos($message, 'how much') !== false || strpos($message, 'cost') !== false) {
        $reply = "It is $5 dollars.";
    }
    else {
        $reply = "I'm sorry, I didn't catch that. Could you repeat your order? We have Coffee and Tea.";
    }
} 

// 2. İŞ GÖRÜŞMESİ SENARYOSU
elseif ($scenario == 'interview') {
    if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
        $reply = "Welcome. Thank you for coming. Can you tell me a little about yourself?";
    }
    elseif (strpos($message, 'name is') !== false || strpos($message, 'student') !== false) {
        $reply = "Nice to meet you. What do you think is your biggest strength?";
        if (strpos($message, 'my name') === false) { // Basit gramer kontrolü
             $correction = "My name is [Name] and I am a student.";
        }
    }
    elseif (strpos($message, 'hard working') !== false || strpos($message, 'team') !== false) {
        $reply = "That is very important for this job. Do you have any experience?";
    }
    else {
        $reply = "That sounds interesting. Why do you want this job?";
    }
}

// 3. TANIŞMA SENARYOSU
else {
    if (strpos($message, 'hello') !== false) {
        $reply = "Hi there! I am new here. What is your name?";
    }
    elseif (strpos($message, 'name is') !== false) {
        $reply = "Nice to meet you! Where are you from?";
    }
    else {
        $reply = "Cool! Do you like learning English?";
    }
}

// Cevabı gönder
echo json_encode([
    'reply' => $reply,
    'correction' => $correction
]);
?>