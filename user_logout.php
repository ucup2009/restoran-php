<?php
session_start();

// Hapus semua variabel sesi
$_SESSION = [];

// Jika ingin menghancurkan sesi secara penuh, hapus juga cookie sesi.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();

// Redirect user kembali ke halaman beranda (atau login)
header("Location: index.php"); 
exit();
?>