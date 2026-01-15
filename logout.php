<?php
session_start();

/*
=================================================
 FluentAI - Logout
=================================================
 Bu dosya:
 - Tüm session verilerini temizler
 - Kullanıcıyı sistemden güvenli şekilde çıkarır
 - Ana sayfaya yönlendirir
=================================================
*/

// Session içindeki tüm verileri sil
$_SESSION = [];

// Session cookie varsa yok et
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Session'ı tamamen yok et
session_destroy();

// Ana sayfaya yönlendir
header("Location: index.php");
exit;
