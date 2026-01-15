<?php
/*
=================================================
 FluentAI - Database Connection File
=================================================
 Bu dosya:
 - MySQL bağlantısını yönetir
 - Tüm PHP dosyalarından include edilir
 - Tek merkezden kontrol sağlar
=================================================
*/

// ====== VERİTABANI BİLGİLERİ ======
$db_host = "localhost";
$db_user = "root";
$db_pass = "";          // XAMPP varsayılan
$db_name = "fluentai";

// ====== BAĞLANTI OLUŞTUR ======
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// ====== HATA KONTROL ======
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ====== KARAKTER SET ======
$conn->set_charset("utf8mb4");

/*
=================================================
 KULLANIM:
 include "db.php";
 veya
 include "db.php";
=================================================
*/
?>
